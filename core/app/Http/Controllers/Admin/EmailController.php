<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Group;
use App\Models\Contact;
use App\Models\EmailHistory;
use App\Models\GroupContact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class EmailController extends Controller
{

    public function history()
    {
        $pageTitle = 'Email History';
        $logs      = EmailHistory::filter(['email', 'batch_id', 'sender', 'status'])
            ->dateFilter()->orderBy('id', 'DESC')
            ->with('smtp')
            ->paginate(getPaginate());

        if (request()->batch_id) {
            $batch     = Batch::where('id', request()->batch_id)->firstOrFail(['batch_id']);
            $pageTitle = "Email History For Batch: " . $batch->batch_id;
        }

        return view('admin.email.history', compact('pageTitle', 'logs'));
    }

    public function sendEmail()
    {
        $pageTitle = 'Send Email';
        $groups    = Group::email()->where('status', 1)->get();
        return view('admin.email.send', compact('pageTitle', 'groups'));
    }


    public function merge(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'contact_id' => 'nullable|array',
            'group_id'   => 'nullable|array',
            'file'       => ['nullable', 'file', 'max:3072', new FileTypeValidate(['csv', 'xlsx', 'csv'])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->all()
            ]);
        }

        $emailFromFile = [];

        if ($request->hasFile('file')) {
            try {
                $fileRead = importFileReader($request->file, ['email'], [], false);
                $emailReadFromFile = $fileRead->getReadData();

                if (count($emailReadFromFile)) {
                    foreach ($emailReadFromFile as $email) {
                        $emailFromFile[] = @$email[0];
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    'success' => false,
                    'errors'  => $ex->getMessage()
                ]);
            }
        }

        $contactId        = $request->contact_id ?? [];
        $emailFromContact = Contact::whereIn('id', $contactId)->pluck('email')->toArray();
        $groupId          = $request->group_id ?? [];
        $emailFromGroup   = GroupContact::with('contact')->whereIn('group_id', $groupId)->get()->pluck('contact.email')->toArray();
        $uniqueEmails       = array_merge($emailFromContact, $emailFromGroup, $emailFromFile);
        $uniqueEmails       = array_unique($uniqueEmails);
        $uniqueEmails       = array_filter($uniqueEmails);

        if (count($uniqueEmails) <= 0) {
            return response()->json([
                'success'                    => false,
                'message'                     => "At least one email required"
            ]);
        }

        session()->put('EMAIL_FOR_SEND', $uniqueEmails);

        return response()->json([
            'success'                    => true,
            'emails'                     => collect($uniqueEmails)->values()
        ]);
    }

    public function send(Request $request)
    {

        $validation = $this->validation($request);

        if (!$validation->success) {
            return response()->json([
                'success' => false,
                'errors'  => $validation->errors
            ]);
        }

        $general                        = generalSetting();

        $batch                          = Batch::where('batch_id', $request->batch_id)->first();

        if (!$batch) {
            $batch                      = new Batch();
            $batch->type                = 1;
            $batch->batch_id            = $request->batch_id;
            $batch->total               = count(session()->get('EMAIL_FOR_SEND'));
            $batch->schedule            =  Carbon::now();
            $batch->sender              = @$general->mail_config->name;
            $batch->save();
        }

        $contact                        = Contact::where('email', $request->email)->first();

        $emailHistory                   = new EmailHistory();
        $emailHistory->contact_id       = $contact ? $contact->id : 0;
        $emailHistory->email            = $request->email;
        $emailHistory->message          = $request->message;
        $emailHistory->subject          = $request->subject;
        $emailHistory->schedule         = Carbon::parse($request->date)->format('Y-m-d h:i');
        $emailHistory->batch_id         = $batch->id;
        $emailHistory->sender           = @$general->mail_config->name;

        $emailHistory->save();

        if ($request->will_be_sent == 1) {
            $user = [
                'username'              => $emailHistory->email,
                'email'                 => $emailHistory->email,
                'fullname'              => $emailHistory->email,
            ];

            notify($user, "DEFAULT", [
                'subject'               => $emailHistory->subject,
                'message'               => $emailHistory->message
            ], ['email'], false);

            if (session()->has('mail_error')) {
                $emailHistory->status   = 9;
                $emailHistory->fail_reason = session()->get('mail_error');
                session()->forget('mail_error');
                $batch->total_fail++;
            } else {
                $emailHistory->status  = 1;
                $batch->total_success++;
            }
            $emailHistory->sent_time   = Carbon::now();
            $emailHistory->save();

            $batch->status              = 1;
            $batch->save();
        }
        return response()->json([
            'success'                   => true,
        ]);
    }



    public function view($id)
    {
        $email                              = EmailHistory::findOrFail($id);
        $pageTitle                          = "Email Details";
        return view('admin.email.details', compact('pageTitle', 'email'));
    }

    protected function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'will_be_sent' => 'required|in:1,2',
            'date'         => "required_if:will_be_sent,==,2|nullable|date|date_format:Y-m-d h:i a|after_or_equal:today",
            'message'      => 'required',
            'subject'      => 'required'
        ], [
            "date.required_if"    => "The date filed is required",
            "date.after_or_equal" => "The date must be today or future date",
            "date.date_format"    => "The date format invalid",
        ]);


        if ($validator->fails()) {
            return (object) [
                'success' => false,
                'errors'  => $validator->errors()->all()
            ];
        }
        return (object) [
            'success' => true,
        ];
    }
}
