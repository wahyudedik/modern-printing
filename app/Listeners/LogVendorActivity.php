<?php

namespace App\Listeners;

use App\Events\VendorActivityEvent;
use App\Models\VendorActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogVendorActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VendorActivityEvent $event): void
    {
        VendorActivity::create([
            'vendor_id' => $event->vendor_id,
            'user_id' => $event->user_id,
            'action' => $event->action,
            'description' => $event->description,
            'changes' => $event->data
        ]);
    }
}
