<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    protected $pageTitle;

    public function all()
    {
        $pageTitle   = 'Manage All Contacts';
        $contactType = 'all';
        $contacts    = Contact::filter(['name', 'email', 'mobile', 'status'])->orderBy('id', 'DESC')->paginate(getPaginate());
        $columns     = Contact::getColumNames();
        return view('admin.contact.list', compact('pageTitle', 'contacts', 'contactType', 'columns'));
    }

    public function emailContact()
    {
        $pageTitle   = 'Manage Email Contacts';
        $contactType = "email";
        $contacts    = Contact::filter(['name', 'email', 'status'])->whereNotNull('email')->orderBy('id', 'DESC')->paginate(getPaginate());
        $columns     = Contact::getColumNames();
        return view('admin.contact.list', compact('pageTitle', 'contacts', 'contactType', 'columns'));
    }

    public function smsContact()
    {
        $pageTitle   = 'Manage SMS Contact';
        $contactType = "mobile";
        $filterColumns = ['name', 'mobile', 'status'];
        $contacts    = Contact::filter(['name', 'mobile', 'status'])->whereNotNull('mobile')->orderBy('id', 'DESC')->paginate(getPaginate());
        $columns     = Contact::getColumNames();
        return view('admin.contact.list', compact('pageTitle', 'contacts', 'contactType', 'columns'));
    }


    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'contact_type' => 'required|in:all,mobile,email',
            'name'         => 'nullable|string',
            'mobile'        => "required_if:contact_type,mobile,all|unique:contacts,mobile," . $id,
            'email'        => "required_if:contact_type,email,all|email|unique:contacts,email," . $id,
        ], [
            "required_if" => "The :attribute filed is required",
        ]);

        if ($id) {
            $contact         = Contact::findOrFail($id);
            $contact->status = $request->status ? 1 : 0;
            $notification    = 'Contact updated successfully';
        } else {
            $notification = 'New contact added successfully';
            $contact      = new Contact();
        }

        $contact->name  = $request->name;
        $contact->mobile = $request->mobile;
        $contact->email = $request->email;
        $contact->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }



    public function importContact(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file'         => ['required', 'file', 'max:3072', new FileTypeValidate(['csv', 'xlsx', 'txt'])],
            'contact_type' => 'required|in:all,mobile,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Validation Error",
                'errors'  => $validator->errors()->all()
            ]);
        }

        if ($request->contact_type == 'email') {
            $columns       = ['email'];
            $uniqueColumns = ['email'];
        } elseif ($request->contact_type == 'mobile') {
            $columns       = ['mobile'];
            $uniqueColumns = ['mobile'];
        } else {
            $columns       = ['name', 'email', 'mobile'];
            $uniqueColumns = ['email', 'mobile'];
        }

        $notify = [];

        try {
            $import = importFileReader($request->file, $columns, $uniqueColumns);
            $notify = $import->notifyMessage();
        } catch (Exception $ex) {
            $notify['success'] = false;
            $notify['message'] = $ex->getMessage();
        }
        return response()->json($notify);
    }

    public function validateImportFile($request)
    {
        $validator = Validator::make($request->all(), [
            'file'         => ['required', 'file', 'max:3072', new FileTypeValidate(['csv', 'xlsx', 'txt'])],
            'contact_type' => 'required|in:all,mobile,email',
        ]);

        if ($validator->fails()) {
            $notify = [
                'success' => false,
                'errors'  => $validator->errors()->all()
            ];
        } else {
            $notify = ['success'       => true];
        }

        return (object) $notify;
    }

    public  function exportContact(Request $request)
    {
        $request->validate([
            'columns'                   => 'required|array',
            'export_item'               => 'required|integer',
            'contact_type'              => 'required|in:all,mobile,email',
        ]);

        $contact                        = new Contact();
        $contact->exportColumns         = $request->columns;
        $contact->fileName              = $request->contact_type . '_contact.csv';
        $contact->exportItem            = $request->export_item;
        $contact->orderBy               = $request->order_by ? "ASC" : 'DESC';
        return  $contact->export();
    }

    public function contactSearch($type)
    {
        $type = strtolower($type);
        $query = Contact::active()->whereNotNull($type);

        if (request()->group_id) {
            $query->whereDoesntHave('groupContact',function($q){
                $q->where('group_id',request()->group_id);
            });
        }

        if (request()->search) {
            $query->where($type, "Like", "%" . request()->search . "%");
        }

        $contacts = $query->paginate(getPaginate());

        if (request()->forSelect2) {
            $response = [];
            foreach ($contacts as $contact) {
                $response[]             = [
                    "id"   => $contact->id,
                    "text" => $contact->$type,
                    $type  => $contact->$type
                ];
            }
            return response()->json($response);
        }
        return response()->json([
            'success'                   => true,
            'contacts'                  => $contacts
        ]);
    }
}
