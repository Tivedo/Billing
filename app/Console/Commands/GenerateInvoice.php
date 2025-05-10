<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Kontrak;
use App\Models\Customer;
use App\Mail\InvoiceMail;
use App\Models\InvoiceItem;
use App\Models\KontrakLayanan;
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
        $today = Carbon::now();

        if ($today->day !== 1) {
            Log::info('Hari ini bukan tanggal 1, tidak ada invoice yang di-generate.');
            return;
        }

        $kontrak = Kontrak::join('order', 'order.id', '=', 'kontrak.order_id')
        ->leftJoin('invoice', 'invoice.order_id', '=', 'order.id')
        ->where('kontrak.status', 'active')
        ->where('invoice.id', null)
        ->get();
        Log::info('Jumlah kontrak yang ditemukan: ' . $kontrak->count());

        foreach ($kontrak as $k) {
            $invoice = Invoice::create([
                'nomor' => 'INV-' . strtoupper(uniqid()),
                'tgl_invoice' => $today->format('Y-m-d'),
                'tgl_jatuh_tempo' => $today->addDays(20)->format('Y-m-d'),
                'type' => 'recurring',
                'order_id' => $k->order_id,
            ]);
            $kontrakLayanan = KontrakLayanan::join('layanan', 'layanan.id', '=', 'kontrak_layanan.layanan_id')
            ->select('kontrak_layanan.*', 'layanan.harga')
            ->where('kontrak_id', $k->id)->get();
            foreach ($kontrakLayanan as $kl) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'layanan_id' => $kl->layanan_id,
                    'harga' => $kl->harga
               ]);
            }
            $dataInvoice = InvoiceItem::join('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->where('invoice.id', $invoice->id)
            ->select('invoice_item.*', 'invoice.nomor', 'invoice.tgl_invoice', 'invoice.tgl_jatuh_tempo')
            ->get()->toArray();
            $totalTagihan = array_sum(array_column($dataInvoice, 'nilai_bayar'));
            $terbilang = $this->convert($totalTagihan) . ' Rupiah';
            $pdf = Pdf::loadView('pdf/invoice-layanan', [
                'dataSbs' => $dataInvoice,
                'terbilang' => $terbilang,
                'total' => $totalTagihan,
                'total_tagihan' => array_sum(array_column($invoice, 'total_tagihan')),
                'total_ppn' => array_sum(array_column($invoice, 'ppn')),
            ]);
            $pdf->setPaper('A4', 'portrait');
            $pdfPath = storage_path('app/public/invoice' . $invoice[0]->nomor . '.pdf');
            $pdf->save($pdfPath);
            // Mail::to($customer->email)->send(new InvoiceMail($invoice));

        }

        Log::info('Invoice berhasil di-generate untuk ' . $kontrak->count() . ' kontrak.');
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
