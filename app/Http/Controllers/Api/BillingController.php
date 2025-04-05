<?php
namespace App\Http\Controllers\Api;
use App\Models\Deposit;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class BillingController extends Controller
{
    public function getInvoice($customerId)
    {
        $invoice = Invoice::join('order', 'invoice.order_id', '=', 'order.id')
        ->leftjoin('customer', 'order.customer_id', '=', 'customer.id')
        ->leftjoin('invoice_item', 'invoice.id', '=', 'invoice_item.invoice_id')
        ->leftjoin('layanan', 'invoice_item.layanan_id', '=', 'layanan.id')
        ->leftjoin('produk', 'invoice_item.produk_id', '=', 'produk.id')
        ->select('invoice.id',
        'invoice.nomor',
        'invoice.status',
        'invoice.tgl_invoice',
        'invoice.tgl_jatuh_tempo',
        'invoice.url_bukti_potong_pph',
        'invoice.url_bukti_potong_ppn',
        'invoice.url_invoice',
        'invoice.url_tanda_terima',
        'invoice.type',
        'invoice_item.nilai_pokok',
        'invoice_item.ppn',
        'invoice_item.nilai_bayar as jumlah',
        DB::raw('IFNULL(layanan.nama, produk.nama) as nama'),
        'customer.status_perusahaan',
        'customer.alamat'
        )
        ->where('customer.id', $customerId)
        ->get();
        return response()->json($invoice);
    }
    public function getDepositAktif($customerId){
        $data = Deposit::join('order', 'deposit.order_id', '=', 'order.id')
        ->select('deposit.id', 'deposit.tgl', 'deposit.jumlah', 'deposit.type', 'order.nomor')
        ->where('customer_id', $customerId)->where('type', 'aktif')->get();
        return response()->json($data);
    }
    public function getDepositTerpakai($customerId){
        $data = Deposit::join('order', 'deposit.order_id', '=', 'order.id')
        ->select('deposit.id', 'deposit.tgl', 'deposit.jumlah', 'deposit.type', 'order.nomor')
        ->where('customer_id', $customerId)->where('type', 'terpakai')->get();
        return response()->json($data);
    }
    
}