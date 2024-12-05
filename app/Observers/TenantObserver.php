<?php

namespace App\Observers;

use App\Models\Vendor;
use App\Notifications\TenantDeactivated;

class TenantObserver
{
    public function updated(Vendor $tenant)
    {
        if ($tenant->isDirty('status') && $tenant->status === 'inactive') {
            // Get all users associated with this tenant who have email addresses
            $users = $tenant->users()->whereNotNull('email')->get();

            // Send notification to each user
            foreach ($users as $user) {
                if ($user->email) {
                    $user->notify(new TenantDeactivated());
                }
            }
        }
    }
}
