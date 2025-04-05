<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\BillingController as ApiBillingController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;


class PaymentController extends Controller
{

    public function create($id) {

        $paymentParam = $this->createPaymentParam($id);
        Log::info('Payment Param: ', $paymentParam);
        $tokenSnap = (new MidtransService())->createSnapToken($paymentParam);
                
        $token = Session::get('jwt_token');
        $payload = JWTAuth::setToken($token)->getPayload();
        $customerId = $payload->get('sub');
        $response = (new ApiBillingController)->getInvoice($customerId);
        $responseData = json_decode($response->getContent(), true);
        $responseDepositAktif = (new ApiBillingController)->getDepositAktif($customerId);
        $depositAktif = json_decode($responseDepositAktif->getContent(), true);
        $jumlahDepositAktif = 0;
        foreach ($depositAktif as $item) {
            $jumlahDepositAktif += $item['jumlah'];
        }
        $resposeDepositTerpakai = (new ApiBillingController)->getDepositTerpakai($customerId);
        $depositTerpakai = json_decode($resposeDepositTerpakai->getContent(), true);
        return view('billing', [
            'data' => $responseData,
            'depositAktif' => $depositAktif,
            'depositTerpakai' => $depositTerpakai,
            'jumlahDepositAktif' => $jumlahDepositAktif,
            'snap_token' => $tokenSnap
        ]);
    }

    private function createPaymentParam($id) {
        
        $invoice = Invoice::join('invoice_item', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->join('layanan', 'invoice_item.layanan_id', '=', 'layanan.id')
            ->join('order', 'invoice.order_id', '=', 'order.id')
            ->join('customer', 'order.customer_id', '=', 'customer.id')
            ->select('invoice.id', 'layanan.nama as namaproduk', 'invoice_item.nilai_bayar as harga', 'customer.nama as namacustomer', 'customer.email', 'customer.telp', 'customer.alamat as address')
            ->where('invoice.id', $id)
            ->first();
        $item = [
            [
                'id' => $invoice->id,
                'price' => $invoice->harga,
                'quantity' => 1,
                'name' => $invoice->namaproduk,
            ]
        ];    
        

        $currentTimeMilis = floor(microtime(true) * 1000);
        $first_name = explode(" ",$invoice->namacustomer)[0] ?? "";
        $last_name = explode(" ",$invoice->namacustomer)[1] ?? "";
        $transaction_detail = [
            'order_id' => "RTC-$currentTimeMilis-$invoice->id",
            'gross_amount' => $invoice->harga
        ];
        $address = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $invoice->email,
            'phone' => strval($invoice->telp),
            "address" => $invoice->address,
            "country_code" => "IDN"
        ];
        $customer_detail = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $invoice->email,
            'phone' => strval($invoice->telp),
            'billing_address' => $address,
            'shipping_address' => $address
        ];

        return [
            'transaction_details' => $transaction_detail,
            'item_details' => $item,
            'customer_details' => $customer_detail,
            'callback' => [
                'finish' => route('payment.success', ['invoice_id' => $invoice->id]),
                // 'unfinish' => route('payment.unfinish'),
                // 'error' => route('payment.error')
            ],
        ];
    }

    

    public function success(Request $request) {
        $invoiceId = $request->input('invoice_id');
        Invoice::where('id', $invoiceId)->update(['status' => 'paid']);
        return redirect()->route('billing')->with('success', 'Pembayaran berhasil');
    }
}
