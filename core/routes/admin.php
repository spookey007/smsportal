<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->controller('LoginController')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout');

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('admin')->group(function () {

    Route::controller('AdminController')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');
        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });


    Route::controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('general-setting', 'index')->name('setting.index');
        Route::post('general-setting', 'update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');
    });


    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate')->name('email.update');
        Route::post('email/test', 'emailTest')->name('email.test');
        Route::get('global/template', 'globalTemplate')->name('global.template.email');
        Route::post('global/template', 'globalTemplateUpdate')->name('global.template.email');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');
        Route::post('sms/update', 'smsSettingUpdate')->name('sms.update');

        Route::get('global/template/sms', 'globalTemplateSms')->name('sms.global.template');
        Route::post('global/template/sms', 'globalTSmsTemplateUpdate')->name('global.template.update');
    });

    // Plugin
    Route::controller('ExtensionController')->group(function () {
        Route::get('extensions', 'index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'update')->name('extensions.update');
        Route::post('extensions/status/{id}', 'status')->name('extensions.status');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
    });

    //===============start the contact route ===========
    Route::prefix('contact')->controller("ContactController")->group(function () {
        Route::get('all', 'all')->name('contact.all');
        Route::post('store', 'save')->name('contact.store');
        Route::post('update/{id}', 'save')->name('contact.update');
        Route::post('import', 'importContact')->name('contact.import');
        Route::post('export', 'exportContact')->name('contact.export');
        Route::get('email', 'emailContact')->name('contact.email');
        Route::get('sms', 'smsContact')->name('contact.sms');
        Route::get('contact/search/{contact_type}/', "contactSearch")->name('contact.search');
    });

    //=============== Start the group route ===========
    Route::prefix('group')->controller("GroupController")->group(function () {

        Route::name('group.')->group(function () {
            Route::post('store', 'saveGroup')->name('store');
            Route::get('banned', 'banned')->name('banned');
            Route::post('update/{id}', 'saveGroup')->name('update');
            Route::post('delete/contact/{id}', 'deleteContactFromGroup')->name('delete.contact');
            Route::get('contact/view/{id}/{groupType}', 'viewGroupContact')->name('contact.view');
            Route::post('save/contact/{groupId}/{groupType}', 'contactSaveToGroup')->name('to.contact.save');
            Route::post('import/contact/{groupId}/{groupType}', 'importContactToGroup')->name('import.contact');
        });

        Route::name('email.group.')->prefix('email-group')->group(function () {
            Route::get('index', 'emailGroup')->name('index');
        });
        Route::name('sms.group.')->prefix('sms-group')->group(function () {
            Route::get('index', 'smsGroup')->name('index');
        });
    });

    //================== Start the smtp route=========
    Route::controller('SmtpController')->name('smtp.')->prefix('smtp')->group(function () {
        Route::get('/index', "index")->name('index');
        Route::post('/store', "save")->name('store');
        Route::post('/update/{id}', "save")->name('update');
    });

    Route::controller("EmailController")->group(function () {
        Route::name('email.')->prefix('email')->group(function () {
            Route::get("/history", "history")->name('history');
            Route::post("/merge", "merge")->name('merge');
            Route::get("/send", "sendEmail")->name('send');
            Route::post("/send", "send")->name('send');
            Route::get("/view/{id}", "view")->name('view');
        });
    });

    Route::controller("SmsController")->group(function () {
        Route::name('sms.')->prefix('sms')->group(function () {
            Route::get("/history", "history")->name('history');
            Route::get("/send", "sendSms")->name('send');
            Route::post("/send", "send")->name('send');
            Route::get("/view/{id}", "view")->name('view');
        });
        Route::post("/mobile/number/merge", "mobileNumberMerge")->name('mobile.number.merge');
    });

    Route::controller('BatchController')->name('batch.')->group(function () {
        Route::get('email', 'emailBatch')->name('email');
        Route::get('sms', 'smsBatch')->name('sms');
    });
});
