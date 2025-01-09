<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::get('/produk', function () {
    return view('produk');
});
Route::get('/produk/detail', function () {
    return view('detail-produk');
});

