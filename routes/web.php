<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'login'])->name('login');
Route::get('/register', [CustomerController::class, 'register'])->name('register');
Route::post('/register', [CustomerController::class, 'store'])->name('register.submit');
Route::post('/login', [CustomerController::class, 'loginPost'])->name('login.submit');
Route::middleware(['jwt'])->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/produk/{$id}', [ProdukController::class, 'detail'])->name('produk.detail');
});
// Route::get('/produk/detail', function () {
//     return view('detail-produk');
// });

