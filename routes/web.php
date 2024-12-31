<?php

use App\Livewire\Pos;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('home');
});

Route::get('/account/inactive', function () {
    return view('account.inactive');
})
    ->name('account.inactive')
    ->middleware([
        Authenticate::class . ':app',
        ApplyTenantScopes::class
    ]);

// Route::get('/transaksi/print/{record}', function ($record) {
//     return view('transaksi.print', compact('record'));
// })
//     ->name('transaksi.print')
//     ->middleware([
//         Authenticate::class . ':app',
//         ApplyTenantScopes::class
//     ]);


Route::middleware([
    Authenticate::class . ':app',
    ApplyTenantScopes::class
])->group(function () {
    Route::get('/app/{tenant}/pos', [PosController::class, 'index'])
        ->name('pos.index');
    Route::get('/app/{tenant}/pos/category/{slug}', [PosController::class, 'category'])
        ->name('pos.category');
    Route::get('/app/{tenant}/pos/search', [PosController::class, 'search'])
        ->name('pos.search');
    Route::get('/app/{tenant}/pos/cart', [PosController::class, 'cart'])
        ->name('pos.cart');
    Route::post('/app/{tenant}/pos/add-to-cart', [PosController::class, 'addToCart'])
        ->name('pos.addToCart');
    Route::get('/app/{tenant}/pos/cart/remove/{index}', [PosController::class, 'removeItem'])
        ->name('pos.removeItem');
    Route::get('/app/{tenant}/pos/cart/clear', [PosController::class, 'clearCart'])
        ->name('pos.clearCart');
    Route::post('/app/{tenant}/pos/calculate-price', [PosController::class, 'calculatePrice'])
        ->name('pos.calculatePrice');
    Route::get('/app/{tenant}/pos/checkout', [CheckoutController::class, 'show'])
        ->name('pos.checkout');
    Route::post('/app/{tenant}/pos/checkout', [CheckoutController::class, 'process'])
        ->name('pos.checkout.process');
    Route::post('/app/{tenant}/pos/customer/create', [CheckoutController::class, 'createCustomer'])
        ->name('pos.customer.create');
    Route::get('/app/{tenant}/pos/invoice/{transaksi}/download', [InvoiceController::class, 'download'])
        ->name('pos.invoice.download');
    Route::post('/pos/check-price', [PosController::class, 'checkPrice'])->name('pos.checkPrice');
});
