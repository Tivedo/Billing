<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Kontrak;
use App\Models\Customer;
use App\Mail\InvoiceMail;
use App\Models\InvoiceItem;
use App\Models\KontrakLayanan;
use App\Services\PajakService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GenerateInvoice extends Command
{
    protected $signature = 'invoice:generate';
    protected $description = 'Generate invoice otomatis untuk customer aktif setiap tanggal 1';

    public function handle()
    {
        // $pajakService = new PajakService();
        // $tokenPajak = $pajakService->login();

        $start = Carbon::now()->startOfMonth()->toDateString();
        $end   = Carbon::now()->endOfMonth()->toDateString();

        $kontrak = Kontrak::join('order', 'order.id', '=', 'kontrak.order_id')
            ->leftJoin('invoice', function($join) use ($start, $end) {
                $join->on('invoice.order_id', '=', 'order.id')
                    ->whereBetween('invoice.tgl_invoice', [$start, $end]);
            })
            ->select('kontrak.id', 'kontrak.order_id')
            ->where('kontrak.status', 'active')
            ->whereNull('invoice.id')
            ->get();

        Log::info('Jumlah kontrak: ' . $kontrak->count());
        $today = Carbon::now();

        foreach ($kontrak as $k) {
            $invoice = Invoice::create([
                'nomor' => 'INV-' . strtoupper(uniqid()),
                'tgl_invoice' => $today->format('Y-m-d'),
                'tgl_jatuh_tempo' => $today->copy()->addDays(20)->format('Y-m-d'),
                'type' => 'recurring',
                'order_id' => $k->order_id,
            ]);

            $items = KontrakLayanan::leftJoin('layanan', 'layanan.id', '=', 'kontrak_layanan.layanan_id')
                ->leftJoin('kontrak', 'kontrak.id', '=', 'kontrak_layanan.kontrak_id')
                ->leftJoin('customer', 'customer.id', '=', 'kontrak.customer_id')
                ->select('kontrak_layanan.*', 'layanan.harga', 'customer.status_perusahaan')
                ->where('kontrak.id', $k->id)->get();

            foreach ($items as $i) {
                $pph = in_array($i->status_perusahaan, [1, 2]) ? $i->harga * 0.02 : 0;
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'layanan_id' => $i->layanan_id,
                    'nilai_pokok' => $i->harga,
                    'ppn' => round($i->harga * 0.11),
                    'pph' => $pph,
                    'dpp_lain' => round($i->harga * 11 / 12),
                    'nilai_bayar' => round($i->harga * 1.11 - $pph),
                ]);
            }

            $data = InvoiceItem::getInvoiceDetail($invoice->id);
            // $npwpData = $pajakService->validateNpwp($tokenPajak, $data[0]['npwp']);
            $total = array_sum(array_column($data, 'nilai_bayar'));

            $pdf = Pdf::loadView('pdf/invoice', [
                'dataInvoice' => $data,
                'terbilang' => $this->convert($total) . ' Rupiah',
                'total' => $total,
                'total_dpp_lain' => array_sum(array_column($data, 'dpp_lain')),
                'total_ppn' => array_sum(array_column($data, 'ppn')),
                'total_tagihan' => array_sum(array_column($data, 'nilai_pokok')),
                'alamat_customer' => $npwpData['alamat']?? $data[0]['alamat'],
                'nama_customer' => $npwpData['nama'] ?? $data[0]['nama_customer'],
            ])->setPaper('A4', 'portrait');

            $filename = 'invoice_' . $invoice->nomor . '.pdf';
            $pdf->save(public_path("invoice/$filename"));
            $url = asset("invoice/$filename");
            Invoice::where('id', $invoice->id)->update(['url_invoice' => $url]);
            Log::info('Invoice generated: ' . $filename);
            Mail::to($data[0]['email'])->send(new InvoiceMail($invoice->nomor, $data[0]->nama_customer, $invoice->tgl_invoice, $url));
        }
    }
    public function convert($number){
        {
            $words = array(
                '0' => '', '1' => 'Satu', '2' => 'Dua', '3' => 'Tiga', '4' => 'Empat',
                '5' => 'Lima', '6' => 'Enam', '7' => 'Tujuh', '8' => 'Delapan', '9' => 'Sembilan',
                '10' => 'Sepuluh', '11' => 'Sebelas', '12' => 'Dua Belas', '13' => 'Tiga Belas', '14' => 'Empat Belas',
                '15' => 'Lima Belas', '16' => 'Enam Belas', '17' => 'Tujuh Belas', '18' => 'Delapan Belas', '19' => 'Sembilan Belas', 
                '20' => 'Dua Puluh', '30' => 'Tiga Puluh', '40' => 'Empat Puluh', '50' => 'Lima Puluh',
                '60' => 'Enam Puluh', '70' => 'Tujuh Puluh', '80' => 'Delapan Puluh', '90' => 'Sembilan Puluh',
                '100' => 'Seratus', '1000' => 'Seribu'
            );

                if ($number < 21) {
                    return $words[$number];
                } elseif ($number < 100) {
                    return $words[10 * floor($number / 10)] . ' ' . self::convert($number % 10);
                } elseif ($number < 200) {
                    return 'Seratus ' . self::convert($number - 100);
                } elseif ($number < 1000) {
                    return $words[floor($number / 100)] . ' Ratus ' . self::convert($number % 100);
                } elseif ($number < 2000) {
                    return 'Seribu ' . self::convert($number - 1000);
                } elseif ($number < 1000000) {
                    return self::convert(floor($number / 1000)) . ' Ribu ' . self::convert($number % 1000);
                } elseif ($number < 1000000000) {
                    return self::convert(floor($number / 1000000)) . ' Juta ' . self::convert($number % 1000000);
                } elseif ($number < 1000000000000) {
                    return self::convert(floor($number / 1000000000)) . ' Miliar ' . self::convert(fmod($number, 1000000000));
                } else {
                    return self::convert(floor($number / 1000000000000)) . ' Triliun ' . self::convert(fmod($number, 1000000000000));
                }
            }
    }
}

