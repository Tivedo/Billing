<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
    public function uploadBuktiPph(Request $request)
    {
        $request->validate([
            'pph' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Simpan file ke dalam storage
        if ($request->file('pph')) {
            // 1. Ambil objek file dari request
            $file = $request->file('pph');

            // 2. Buat nama file yang unik untuk menghindari nama yang sama
            // Contoh: invoice_62f1b4e7a8e1b.pdf
            $filename = 'buktiPotong_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // 3. Pindahkan file ke folder public/bupot
            $file->move(public_path('bupot'), $filename);

            // 4. Buat URL lengkap menggunakan helper asset()
            $fileUrl = asset('invoice/' . $filename);

            // 5. Simpan URL lengkap ke database
            $invoice = Invoice::find($request->id);
            $invoice->url_bukti_potong_pph = $fileUrl;
            $invoice->save();
        }

        return back()->with('success', 'File berhasil diupload!')->with('file', $fileUrl);
    }
}
