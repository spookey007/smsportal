<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function boot()
    {
        $general = Cache::get('GeneralSetting');
        if (!$general) {
            $general = GeneralSetting::first(); /// call from DB
            Cache::put('GeneralSetting', $general);
        }
        $viewShare['general'] = $general;;
        $viewShare['emptyMessage'] = 'Data not found';
        view()->share($viewShare);
        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('read_status', 0)->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount'=>AdminNotification::where('read_status',0)->count(),

            ]);
        });

        Paginator::useBootstrapFour();
    }
}
