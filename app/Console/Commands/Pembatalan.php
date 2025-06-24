<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Mail\PembatalanMail;
use App\Mail\RemindPayment;
use App\Models\Kontrak;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Pembatalan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reminding-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->day; // ambil tanggal hari ini (1-31)

        // Cek apakah hari ini tanggal 10, 20, atau 29
        if (in_array($today, [29])) {
            // Cari invoice yang statusnya pending (atau yang mau diingatkan)
            $invoices = Invoice::join('order', 'order.id', '=', 'invoice.order_id')
                ->join('customer', 'customer.id', '=', 'order.customer_id')
                ->select('invoice.*', 'customer.email', 'customer.nama')
                ->where('invoice.status', '!=', 'paid') // Ganti dengan kondisi yang sesuai
                ->get();

            foreach ($invoices as $invoice) {
                // Di sini kamu bisa kirim email, notifikasi, atau lainnya
                // Misal kirim email reminder
                Mail::to('abiyyu.umar18@gmail.com')->send(new PembatalanMail($invoice));

                Invoice::where('id', $invoice->id)->update(['is_batal' => 1]);
                Invoice::join('order', 'order.id', '=', 'invoice.order_id')
                    ->join('kontrak', 'kontrak.id', '=', 'order.kontrak_id')
                    ->where('invoice.id', $invoice->id)
                    ->update(['kontrak.status' => 'inactive']);
            }

            $this->info('Reminder pembayaran berhasil dikirim.');
        } else {
            $this->info('Hari ini bukan jadwal reminder.');
        }
    }
}
