<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [CustomerController::class, 'loginPost']);
Route::post('/register', [CustomerController::class, 'register']);
Route::post('/payment-notification', [PaymentController::class, 'midtransNotification']);
