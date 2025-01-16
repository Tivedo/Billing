<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'login'])->name('login');
Route::get('/register', [CustomerController::class, 'register'])->name('register');
Route::post('/register', [CustomerController::class, 'store'])->name('register.submit');
Route::post('/login', [CustomerController::class, 'loginPost'])->name('login.submit');
Route::get('/produk', function () {
    return view('produk');
});
Route::get('/produk/detail', function () {
    return view('detail-produk');
});

