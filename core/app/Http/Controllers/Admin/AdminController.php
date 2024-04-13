<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Contact;
use App\Models\EmailHistory;
use App\Models\GeneralSetting;
use App\Models\Group;
use App\Models\SmsHistory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {

        $pageTitle   = "Dashboard";
        $smsGateway  = ['clickatell', 'infobip', 'messageBird', 'nexmo', 'smsBroadcast', 'twilio', 'textMagic', 'custom'];
        $emailSender = ['phpmail', 'smtp', 'mailjet', 'sendgrid'];
        $widget      = [];

        $widget['total_email_sent']         = EmailHistory::where('status', '!=', 2)->count();
        $widget['total_success_email_sent'] = EmailHistory::where('status',  1)->count();
        $widget['total_schedule_email']     = EmailHistory::where('status',  2)->count();
        $widget['total_failed_email']       = EmailHistory::where('status',  9)->count();

        $widget['total_sms_sent']         = SmsHistory::where('status', '!=', 2)->count();
        $widget['total_success_sms_sent'] = SmsHistory::where('status',  1)->count();
        $widget['total_schedule_sms']     = SmsHistory::where('status',  2)->count();
        $widget['total_sms_fail']         = SmsHistory::where('status',  9)->count();

        $widget['total_contacts']        = Contact::count();
        $widget['total_email_contacts']  = Contact::whereNotNull('email')->count();
        $widget['total_mobile_contacts']  = Contact::whereNotNull('mobile')->count();
        $widget['total_banned_contacts'] = Contact::where('status', 0)->count();

        $widget['total_groups']        = Group::count();
        $widget['total_email_groups']  = Group::email()->count();
        $widget['total_mobile_groups']  = Group::sms()->count();
        $widget['total_banned_groups'] = Group::where('status', 0)->count();

        return view('admin.dashboard', compact('pageTitle', 'smsGateway', 'emailSender', 'widget'));
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin     = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[]    = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[]    = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin     = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->paginate(getPaginate());
        $pageTitle     = 'Notifications';
        return view('admin.notifications', compact('pageTitle', 'notifications'));
    }


    public function notificationRead($id)
    {
        $notification              = AdminNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function requestReport()
    {
        $pageTitle       = 'Your Listed Report & Request';
        $arr['app_name']      = systemDetails()['name'];
        $arr['app_url']       = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $url             = "https://license.viserlab.com/issue/get?" . http_build_query($arr);
        $response        = CurlRequest::curlContent($url);
        $response        = json_decode($response);
        if ($response->status == 'error') {
            return to_route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports', compact('reports', 'pageTitle'));
    }

    public function reportSubmit(Request $request)
    {

        $request->validate([
            'type'    => 'required|in:bug,feature',
            'message' => 'required',
        ]);

        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name']      = systemDetails()['name'];
        $arr['app_url']       = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $arr['req_type']      = $request->type;
        $arr['message']       = $request->message;
        $response        = CurlRequest::curlPostContent($url, $arr);
        $response        = json_decode($response);

        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }

        $notify[] = ['success', $response->message];

        return back()->withNotify($notify);
    }

    public function readAll()
    {
        AdminNotification::where('read_status', 0)->update([
            'read_status' => 1
        ]);
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = GeneralSetting::first();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
}
