<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Api\BillingController as ApiBillingController;

class BillingController extends Controller
{
    public function index()
    {
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
            'snap_token' => false
        ]);
    }
    public function uploadBuktiPpn(Request $request)
    {
        $request->validate([
            'ppn' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Simpan file ke dalam storage
        if ($request->file('ppn')) {
            $filePath = $request->file('ppn')->store('uploads', 'public');
        }
        // simpan url file ke dalam database
        $invoice = Invoice::find($request->id);
        $invoice->url_bukti_potong_ppn = $filePath;
        $invoice->save();

        return back()->with('success', 'File berhasil diupload!')->with('file', $filePath);
    }
}
