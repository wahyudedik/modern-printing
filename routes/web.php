<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;

Route::get('/', function () {
    return view('home');
});

Route::get('/account/inactive', function () {
    return view('account.inactive');
})->name('account.inactive');

Route::get('/transaksi/print/{record}', function ($record) {
    return view('transaksi.print', compact('record'));
})
    ->name('transaksi.print')
    ->middleware([
        Authenticate::class . ':app',
        ApplyTenantScopes::class
    ]);

Route::get('/pos', App\Livewire\Pos::class)
    ->name('pos')
    ->middleware([
        Authenticate::class . ':app',
        ApplyTenantScopes::class
    ]);
