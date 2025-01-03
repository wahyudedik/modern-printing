<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Events\VendorActivityEvent;
use App\Listeners\LogVendorActivity;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VendorActivityEvent::class => [
            LogVendorActivity::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            if ($event->user->vendor_id > 0) {
                event(new VendorActivityEvent(
                    'login',
                    ['login_time' => now()->format('Y-m-d H:i:s')],
                    (int) $event->user->vendor_id,
                    $event->user->id,
                    "Login: {$event->user->name}"
                ));
            }
        });
    }
}
