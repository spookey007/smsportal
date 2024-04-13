<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\EmailHistory;
use App\Models\GeneralSetting;
use App\Models\SmsHistory;

class CronController extends Controller
{
    public function sentScheduleEmail()
    {


        $date           = now()->format('Y-m-d h:i') . ':00';
        $scheduleEmails = EmailHistory::where('status', 2)->whereNotNull('schedule')->where('schedule', $date)->orderBy('last_cron')->take(100)->get();



        foreach ($scheduleEmails as $scheduleEmail) {
            $batch = Batch::where('id', $scheduleEmail->batch_id)->first();
            $user  = [
                'username' => $scheduleEmail->email,
                'email'    => $scheduleEmail->email,
                'fullname' => $scheduleEmail->email,
            ];

            notify($user, "DEFAULT", [
                'subject' => $scheduleEmail->subject,
                'message' => $scheduleEmail->message
            ], ['email'], false);

            if (session()->has('mail_error')) {
                $scheduleEmail->status      = 9;
                $scheduleEmail->fail_reason = session()->get('mail_error');
                session()->forget('mail_error');
                $batch->total_fail++;
            } else {
                $scheduleEmail->status = 1;
                $batch->total_success++;
            }
            $scheduleEmail->sent_time = Carbon::now();
            $scheduleEmail->last_cron = time();
            $scheduleEmail->save();
            $batch->status = 1;
            $batch->save();
        }

        $general                  = GeneralSetting::first();
        $general->last_email_cron = Carbon::now();
        $general->save();

        return "EXECUTED";
    }


    public function sentScheduleSms()
    {
        $date        = now()->format('Y-m-d h:i') . ':00';
        $scheduleSms = SmsHistory::where('status', 2)->whereNotNull('schedule')->where('schedule', $date)->orderBy('last_cron')->take(100)->get();

        foreach ($scheduleSms as $scheduleMessage) {

            $batch = Batch::where('id', $scheduleMessage->batch_id)->first();
            $user  = [
                'username' => $scheduleMessage->mobile,
                'mobile'   => $scheduleMessage->mobile,
                'fullname' => $scheduleMessage->mobile,
            ];

            notify($user, "DEFAULT", [
                'subject' => $scheduleMessage->subject,
                'message' => $scheduleMessage->message
            ], ['sms'], false);

            if (session()->has('sms_error')) {
                $scheduleMessage->status      = 9;
                $scheduleMessage->fail_reason = session()->get('sms_error');
                session()->forget('sms_error');
                $batch->total_fail++;
            } else {
                $scheduleMessage->status = 1;
                $batch->total_success++;
            }
            $scheduleMessage->sent_time = Carbon::now();
            $scheduleMessage->last_cron = time();
            $scheduleMessage->save();

            $batch->status = 1;
            $batch->save();
        }

        $general                = GeneralSetting::first();
        $general->last_sms_cron = Carbon::now();
        $general->save();

        return "EXECUTED";
    }
}
