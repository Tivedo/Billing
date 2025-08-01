<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;

Route::get('/', [CustomerController::class, 'login'])->name('login');
Route::get('/register', [CustomerController::class, 'register'])->name('register');
Route::post('/register', [CustomerController::class, 'store'])->name('register.submit');
Route::post('/login', [CustomerController::class, 'loginPost'])->name('login.submit');
Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
Route::get('/produk/{id}', [ProdukController::class, 'detail'])->name('produk.detail');
Route::get('/billing', [BillingController::class, 'index'])->name('billing');
Route::get('file/dowload-invoice', [BillingController::class, 'downloadInvoice'])->name('download.invoice');
Route::get('/file/download-tanda-terima', [BillingController::class, 'downloadTandaTerima'])->name('download.tanda.terima');
Route::post('/upload-bukti-ppn', [BillingController::class, 'uploadBuktiPpn'])->name('upload.bukti.ppn');
Route::post('/upload-bukti-pph', [BillingController::class, 'uploadBuktiPph'])->name('upload.bukti.pph');
Route::post('/bayar', [PaymentController::class, 'create'])->name('pilih.metode.pembayaran.recurring');
Route::post('/payment-callback', [PaymentController::class, 'getTrxStatus']);
Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment-notification', [PaymentController::class, 'midtransNotification'])->name('payment.notification');
Route::get('/uploads/{filename}', function ($filename) {
    $path = storage_path('app/uploads/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path);
});
Route::get('/invoice/{filename}', function ($filename) {
    $path = public_path('invoice/' . $filename);

    if (!File::exists($path)) {
        abort(404, 'Invoice not found.');
    }

    $mimeType = File::mimeType($path); // ex: application/pdf
    return Response::file($path, [
        'Content-Type' => $mimeType
    ]);
});
Route::get('/bupot/{filename}', function ($filename) {
    $path = public_path('bupot/' . $filename);

    if (!File::exists($path)) {
        abort(404, 'Bukti potong not found.');
    }

    $mimeType = File::mimeType($path); // ex: application/pdf
    return Response::file($path, [
        'Content-Type' => $mimeType
    ]);
});
Route::get('/faktur/{filename}', function ($filename) {
    $path = public_path('faktur/' . $filename);

    if (!File::exists($path)) {
        abort(404, 'Faktur not found.');
    }

    $mimeType = File::mimeType($path); // ex: application/pdf
    return Response::file($path, [
        'Content-Type' => $mimeType
    ]);
});
// Route::get('/produk/detail', function () {
//     return view('detail-produk');
// });

