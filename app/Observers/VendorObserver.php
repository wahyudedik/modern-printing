<?php

namespace App\Observers;

use App\Models\Vendor;
use App\Models\VendorActivity;
use Illuminate\Support\Facades\Auth;

class VendorObserver
{
    public function created(Vendor $vendor)
    {
        VendorActivity::create([
            'vendor_id' => $vendor->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => "Created vendor {$vendor->name}",
            'changes' => $vendor->toArray()
        ]);
    }

    public function updated(Vendor $vendor)
    {
        VendorActivity::create([
            'vendor_id' => $vendor->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'description' => "Updated vendor {$vendor->name}",
            'changes' => $vendor->getDirty()
        ]);
    }

    public function deleted(Vendor $vendor)
    {
        VendorActivity::create([
            'vendor_id' => $vendor->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => "Deleted vendor {$vendor->name}",
            'changes' => $vendor->toArray()
        ]);
    }
}
