<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Group;
use App\Models\Contact;
use App\Models\SmsHistory;
use App\Models\GroupContact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{

    public function sendSms()
    {

        $pageTitle  = 'Send SMS';
        $groups     = Group::sms()->active()->get();
        return view('admin.sms.send', compact('pageTitle', 'groups'));
    }

    public function history()
    {
        $pageTitle     = 'SMS History';
        $logs          = SmsHistory::filter(['mobile', 'batch_id', 'sender', 'status'])
            ->dateFilter()
            ->orderBy('id', 'DESC')
            ->paginate(getPaginate());
        if (@$_GET['batch_id']) {
            $batch     = Batch::where('id', $_GET['batch_id'])->firstOrFail();
            $pageTitle = "SMS History For Batch: " . $batch->batch_id;
        }
        return view('admin.sms.history', compact('pageTitle', 'logs'));
    }

    public function mobileNumberMerge(Request $request)
    {
        $validator                      = Validator::make($request->all(), [
            'contact_id'                => 'nullable|array',
            'group_id'                  => 'nullable|array',
            'file'                      => ['nullable', 'file', 'max:3072', new FileTypeValidate(['csv', 'xlsx', 'txt'])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'               => false,
                'errors'                => $validator->errors()->all()
            ]);
        }

        $mobileFromFile                  = [];

        if ($request->hasFile('file')) {
            try {
                $fileRead          = importFileReader($request->file, ['mobile'], [], false);
                $mobileReadFromFile = $fileRead->getReadData();

                if (count($mobileReadFromFile)) {
                    foreach ($mobileReadFromFile as $mobile) {
                        $mobileFromFile[] = @$mobile[0];
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    'success' => false,
                    'errors'  => $ex->getMessage()
                ]);
            }
        }

        $contactId                      = $request->contact_id ?? [];
        $mobileFromContact               = Contact::whereIn('id', $contactId)->pluck('mobile')->toArray();
        $groupId                        = $request->group_id ?? [];
        $mobileFromGroup                 = GroupContact::with('contact')->whereIn('group_id', $groupId)->get()->pluck('contact.mobile')->toArray();
        $uniqueMobiles                   = array_unique(array_merge($mobileFromContact, $mobileFromGroup, $mobileFromFile));

        if (count($uniqueMobiles) <= 0) {
            return response()->json([
                'success'               => false,
                'errors'                => "At least one mobile number required"
            ]);
        }

        session()->put('MOBILE_NUMBER_FOR_SEND', $uniqueMobiles);

        return response()->json([
            'success'                    => true,
            'mobileNumbers'               => collect($uniqueMobiles)->values()
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
            $batch->type                = 2;
            $batch->batch_id            = $request->batch_id;
            $batch->total               = count(session()->get('MOBILE_NUMBER_FOR_SEND'));
            $batch->schedule            = Carbon::now();
            $batch->sender              = @$general->sms_config->name;
            $batch->save();
        }

        $contact                        = Contact::where('mobile', $request->mobile)->first();
        $smsHistory                     = new SmsHistory();
        $smsHistory->contact_id         = $contact ? $contact->id : 0;
        $smsHistory->mobile             = $request->mobile;
        $smsHistory->message            = $request->message;
        $smsHistory->schedule           = Carbon::parse($request->date);
        $smsHistory->batch_id           = $batch->id;
        $smsHistory->sender             = @$general->sms_config->name;
        $smsHistory->save();

        if ($request->will_be_sent == 1) {
            $user = [
                'username'              => $smsHistory->mobile,
                'mobile'                => $smsHistory->mobile,
                'fullname'              => $smsHistory->mobile,
            ];

            notify($user, "DEFAULT", [
                'subject'               => $smsHistory->subject,
                'message'               => $smsHistory->message
            ], ['sms'], false);


            if (session()->has('sms_error')) {
                $smsHistory->status   = 9;
                $smsHistory->fail_reason = session()->get('sms_error');
                session()->forget('sms_error');
                $batch->total_fail++;
            } else {
                $smsHistory->status  = 1;
                $batch->total_success++;
            }
            $smsHistory->sent_time   = Carbon::now();
            $smsHistory->save();
            $batch->status           = 1;
            $batch->save();
        }
        return response()->json([
            'success'                   => true
        ]);
    }





    public function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'will_be_sent' => 'required|in:1,2',
            'date'         => "required_if:will_be_sent,==,2|nullable|date|date_format:Y-m-d h:i a|after_or_equal:today",
            'message'      => 'required',
        ], [
            "date.required_if"    => "The date filed is required",
            "time.required_if"    => "The time filed is required",
            "date.after_or_equal" => "The date must be today or future date",
            "date.date_format"    => "The date format invalid",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->all()
            ]);
        }

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

    public function view($id)
    {
        $sms                              = SmsHistory::findOrFail($id);
        $pageTitle                          = "SMS Details";
        return view('admin.sms.details', compact('pageTitle', 'sms'));
    }
}
