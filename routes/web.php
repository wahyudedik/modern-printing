<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/account/inactive', function () {
    return view('account.inactive');
})->name('account.inactive');