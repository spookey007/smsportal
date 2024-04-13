<?php

namespace App\Http\Controllers\Admin;

use App\Notify\Sms;
use App\Models\Smtp;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{


    public function emailSetting()
    {
        $pageTitle                       = "Email Setting";
        $allSmtp                         = Smtp::active()->orderBy('id', 'DESC')->get();
        return view('admin.email.setting', compact('pageTitle', 'allSmtp'));
    }


    public function globalTemplate()
    {
        $pageTitle                       = 'Global Template For Email';
        return view('admin.email.global_template', compact('pageTitle'));
    }



    public function globalTemplateUpdate(Request $request)
    {

        $request->validate([
            'email_template'               => 'required',
        ]);

        $general                           = GeneralSetting::first();
        $general->email_template           = $request->email_template;
        $general->save();
        $notify[]                          = ['success', 'Global email template updated successfully'];
        return back()->withNotify($notify);
    }

    public function emailSettingUpdate(Request $request)
    {
        $request->validate([
            'email_method' => 'required|in:php,smtp,sendgrid,mailjet',
            'smtp'         => 'required_if:email_method,smtp',
            'appkey'       => 'required_if:email_method,sendgrid',
            'public_key'   => 'required_if:email_method,mailjet',
            'secret_key'   => 'required_if:email_method,mailjet',
            'email_from'   => 'required|email|string|max:40',

        ], [
            'smtp.required_if'       => ':attribute is required for SMTP configuration',
            'appkey.required_if'     => ':attribute is required for SendGrid configuration',
            'public_key.required_if' => ':attribute is required for Mailjet configuration',
            'secret_key.required_if' => ':attribute is required for Mailjet configuration',
        ]);

        $general                            = GeneralSetting::first();

        if ($request->email_method == 'php') {
            $data['name'] = 'php';
        } else if ($request->email_method == 'smtp') {
            $smtp             = Smtp::active()->where('id', $request->smtp)->firstOrFail();
            $data['name']     = 'smtp';
            $data['host']     = $smtp->host;
            $data['port']     = $smtp->port;
            $data['username'] = $smtp->username;
            $data['password'] = $smtp->password;
            $data['enc']      = $smtp->encryption;
            $data['driver']   = 'smtp';
            $general->smtp_id = $smtp->id;
        } else if ($request->email_method == 'sendgrid') {
            $request->merge(['name' => 'sendgrid']);
            $data                           = $request->only('name', 'appkey');
        } else if ($request->email_method == 'mailjet') {
            $request->merge(['name' => 'mailjet']);
            $data                           = $request->only('name', 'public_key', 'secret_key');
        }

        $general->mail_config               = $data;
        $general->email_from                = $request->email_from;
        $general->save();
        $notify[]                           = ['success', 'Email settings updated successfully'];

        cache()->forget('general');
        return back()->withNotify($notify);
    }

    public function  smsSettingUpdate(Request $request)
    {

        $request->validate([
            'sms_method'                        => 'required|in:clickatell,infobip,messageBird,nexmo,smsBroadcast,twilio,textMagic,custom',
            'clickatell_api_key'                => 'required_if:sms_method,clickatell',
            'message_bird_api_key'              => 'required_if:sms_method,messageBird',
            'nexmo_api_key'                     => 'required_if:sms_method,nexmo',
            'nexmo_api_secret'                  => 'required_if:sms_method,nexmo',
            'infobip_username'                  => 'required_if:sms_method,infobip',
            'infobip_password'                  => 'required_if:sms_method,infobip',
            'sms_broadcast_username'            => 'required_if:sms_method,smsBroadcast',
            'sms_broadcast_password'            => 'required_if:sms_method,smsBroadcast',
            'text_magic_username'               => 'required_if:sms_method,textMagic',
            'apiv2_key'                         => 'required_if:sms_method,textMagic',
            'account_sid'                       => 'required_if:sms_method,twilio',
            'auth_token'                        => 'required_if:sms_method,twilio',
            'from'                              => 'required_if:sms_method,twilio',
            'custom_api_method'                 => 'required_if:sms_method,custom|in:get,post',
            'custom_api_url'                    => 'required_if:sms_method,custom',
        ]);

        $data = [
            'name'                              => $request->sms_method,
            'clickatell'                        => [
                'api_key'                       => $request->clickatell_api_key,
            ],
            'infobip'                           => [
                'username'                      => $request->infobip_username,
                'password'                      => $request->infobip_password,
            ],
            'message_bird'                      => [
                'api_key'                       => $request->message_bird_api_key,
            ],
            'nexmo'                             => [
                'api_key'                       => $request->nexmo_api_key,
                'api_secret'                    => $request->nexmo_api_secret,
            ],
            'sms_broadcast'                     => [
                'username'                      => $request->sms_broadcast_username,
                'password'                      => $request->sms_broadcast_password,
            ],
            'twilio'                            => [
                'account_sid'                   => $request->account_sid,
                'auth_token'                    => $request->auth_token,
                'from'                          => $request->from,
            ],
            'text_magic'                        => [
                'username'                      => $request->text_magic_username,
                'apiv2_key'                     => $request->apiv2_key,
            ],
            'custom'                            => [
                'method'                        => $request->custom_api_method,
                'url'                           => $request->custom_api_url,
                'headers'                       => [
                    'name'                      => $request->custom_header_name ?? [],
                    'value'                     => $request->custom_header_value ?? [],
                ],
                'body'                          => [
                    'name'                      => $request->custom_body_name ?? [],
                    'value'                     => $request->custom_body_value ?? [],
                ],
            ],
        ];
        $general                                = GeneralSetting::first();
        $general->sms_config                    = $data;
        $general->save();
        $notify[]                               = ['success', 'Sms settings updated successfully'];
        cache()->forget('general');
        return back()->withNotify($notify);
    }


    public function emailTest(Request $request)
    {

        $request->validate([
            'email'                     => 'required|email'
        ]);

        $general                        = GeneralSetting::first();
        $config                         = $general->mail_config;
        $receiverName                   = explode('@', $request->email)[0];
        $subject                        = strtoupper($config->name) . ' Configuration Success';
        $message                        = 'Your email notification setting is configured successfully for ' . $general->site_name;

        if ($general->en) {
            $user = [
                'username'              => $request->email,
                'email'                 => $request->email,
                'fullname'              => $receiverName,
            ];
            notify($user, 'DEFAULT', [
                'subject'               => $subject,
                'message'               => $message,
            ], ['email']);
        } else {
            $notify[]                   = ['info', 'Please enable from general settings'];
            $notify[]                   = ['error', 'Your email notification is disabled'];
            return back()->withNotify($notify);
        }

        if (session('mail_error')) {
            $notify[]                   = ['error', session('mail_error')];
        } else {
            $notify[]                   = ['success', 'Email sent to ' . $request->email . ' successfully'];
        }

        return back()->withNotify($notify);
    }

    public function smsTest(Request $request)
    {
        $request->validate([
            'mobile'                    => 'required'
        ]);
        $general                        = GeneralSetting::first();

        if ($general->sn == 1) {
            $sendSms                    = new Sms;
            $sendSms->mobile            = $request->mobile;
            $sendSms->receiverName      = ' ';
            $sendSms->message           = 'Your sms notification setting is configured successfully for ' . $general->site_name;
            $sendSms->subject           = ' ';
            $sendSms->send();
        } else {
            $notify[]                   = ['info', 'Please enable from general settings'];
            $notify[]                   = ['error', 'Your sms notification is disabled'];
            return back()->withNotify($notify);
        }

        if (session('sms_error')) {
            $notify[]                   = ['error', session('sms_error')];
        } else {
            $notify[]                   = ['success', 'SMS sent to ' . $request->mobile . 'successfully'];
        }

        return back()->withNotify($notify);
    }



    public function globalTemplateSms()
    {
        $pageTitle                       = 'Global Template For SMS';
        return view('admin.sms.global_template', compact('pageTitle'));
    }

    public function globalTSmsTemplateUpdate(Request $request)
    {

        $request->validate([
            'sms_from'                         => 'required|string|max:40',
            'sms_body'                         => 'required',
        ]);

        $general                               = GeneralSetting::first();
        $general->sms_from                     = $request->sms_from;
        $general->sms_body                     = $request->sms_body;
        $general->save();
        $notify[]                          = ['success', 'Global SMS template updated successfully'];
        return back()->withNotify($notify);
    }

    public function smsSetting()
    {
        $pageTitle                       = "SMS Gateway Setting";
        return view('admin.sms.setting', compact('pageTitle'));
    }
}
