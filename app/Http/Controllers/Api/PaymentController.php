<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function midtransNotification(Request $request)
    {
        $notif = $request->all();

        $orderId = $notif['order_id'];
        $invoiceId = explode('-', $orderId)[2];
        Log::info('Invoice ID: ' . $invoiceId);

        $transactionStatus = $notif['transaction_status'];
        $paymentType = $notif['payment_type'];
        $fraudStatus = $notif['fraud_status'];

        // Contoh simple update ke database
        $invoice = Invoice::where('id', $invoiceId)->first();
        if ($transactionStatus == 'settlement') {
            $invoice->status = 'paid';
        } elseif ($transactionStatus == 'pending') {
            $invoice->status = 'pending';
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $invoice->status = 'failed';
        }
        $invoice->save();

        return response()->json(['message' => 'Notification handled']);
    }
}