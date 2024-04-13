<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


Route::get('cron/email', "CronController@sentScheduleEmail")->name('cron.email');
Route::get('cron/sms', "CronController@sentScheduleSms")->name('cron.sms');


Route::controller('SiteController')->group(function () {
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
});

Route::get('/', function () {
    return to_route('admin.dashboard');
})->name('home');
