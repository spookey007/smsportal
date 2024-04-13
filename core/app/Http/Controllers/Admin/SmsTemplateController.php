<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\SendSms;
use App\Models\GeneralSetting;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $pageTitle = 'SMS Templates';
        $sms_templates = NotificationTemplate::get();
        return view('admin.sms_template.index', compact('pageTitle', 'sms_templates'));
    }

    public function edit($id)
    {
        $sms_template = NotificationTemplate::findOrFail($id);
        $pageTitle = $sms_template->name;
        return view('admin.sms_template.edit', compact('pageTitle', 'sms_template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sms_body' => 'required',
        ]);

        $sms_template = NotificationTemplate::findOrFail($id);

        $sms_template->sms_body = $request->sms_body;
        $sms_template->sms_status = $request->sms_status ? 1 : 0;
        $sms_template->save();

        $notify[] = ['success','Template updated successfully'];
        return back()->withNotify($notify);
    }


    public function templates()
    {
        $pageTitle = 'SMS API';
        return view('admin.sms_template.sms_template', compact('pageTitle'));
    }

    public function templateUpdate(Request $request)
    {
        $request->validate([
            'sms_api' => 'required',
        ]);
        $general = GeneralSetting::first();
        $general->sms_api = $request->sms_api;
        $general->save();

        $notify[] = ['success', 'SMS template has been updated'];
        return back()->withNotify($notify);
    }

    public function sendTestSMS(Request $request)
    {
        $request->validate(['mobile' => 'required']);
        $general = GeneralSetting::first(['sn', 'sms_config','sms_api','site_name']);
        if ($general->sn == 1) {
            $gateway = $general->sms_config->name;
            $sendSms = new SendSms;
            $message = str_replace("{{name}}", 'Admin', $general->sms_api);
            $message = str_replace("{{message}}", 'This is a test sms', $message);
            $sendSms->$gateway($request->mobile,$general->site_name,$message,$general->sms_config);
        }

        $notify[] = ['success', 'You sould receive a test sms at ' . $request->mobile . ' shortly.'];
        return back()->withNotify($notify);
    }

    public function smsSetting(){
        $pageTitle = 'SMS Setting';
        return view('admin.sms_template.sms_setting',compact('pageTitle'));
    }


    public function smsSettingUpdate(Request $request){
        $request->validate([
            'sms_method' => 'required|in:clickatell,infobip,messageBird,nexmo,smsBroadcast,twilio,textMagic,custom',
            'clickatell_api_key' => 'required_if:sms_method,clickatell',
            'message_bird_api_key' => 'required_if:sms_method,messageBird',
            'nexmo_api_key' => 'required_if:sms_method,nexmo',
            'nexmo_api_secret' => 'required_if:sms_method,nexmo',
            'infobip_username' => 'required_if:sms_method,infobip',
            'infobip_password' => 'required_if:sms_method,infobip',
            'sms_broadcast_username' => 'required_if:sms_method,smsBroadcast',
            'sms_broadcast_password' => 'required_if:sms_method,smsBroadcast',
            'text_magic_username' => 'required_if:sms_method,textMagic',
            'apiv2_key' => 'required_if:sms_method,textMagic',
            'account_sid' => 'required_if:sms_method,twilio',
            'auth_token' => 'required_if:sms_method,twilio',
            'from' => 'required_if:sms_method,twilio',
            'custom_api_method' => 'required_if:sms_method,custom|in:get,post',
            'custom_api_url' => 'required_if:sms_method,custom',
        ]);

        $data = [
            'name'=>$request->sms_method,
            'clickatell'=>[
                'api_key'=>$request->clickatell_api_key,
            ],
            'infobip'=>[
                'username'=>$request->infobip_username,
                'password'=>$request->infobip_password,
            ],
            'message_bird'=>[
                'api_key'=>$request->message_bird_api_key,
            ],
            'nexmo'=>[
                'api_key'=>$request->nexmo_api_key,
                'api_secret'=>$request->nexmo_api_secret,
            ],
            'sms_broadcast'=>[
                'username'=>$request->sms_broadcast_username,
                'password'=>$request->sms_broadcast_password,
            ],
            'twilio'=>[
                'account_sid'=>$request->account_sid,
                'auth_token'=>$request->auth_token,
                'from'=>$request->from,
            ],
            'text_magic'=>[
                'username'=>$request->text_magic_username,
                'apiv2_key'=>$request->apiv2_key,
            ],
            'custom'=>[
                'method'=>$request->custom_api_method,
                'url'=>$request->custom_api_url,
                'headers'=>[
                    'name'=>$request->custom_header_name ?? [],
                    'value'=>$request->custom_header_value ?? [],
                ],
                'body'=>[
                    'name'=>$request->custom_body_name ?? [],
                    'value'=>$request->custom_body_value ?? [],
                ],
            ],
        ];
        $general = GeneralSetting::first();
        $general->sms_config = $data;
        $general->save();
        $notify[] = ['success', 'Sms settings updated successfully'];
        return back()->withNotify($notify);
    }
}
