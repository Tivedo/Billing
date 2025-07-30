<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Mail\RemindBupot;
use App\Mail\RemindPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RemindingPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reminding-bupot';

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
        // if (in_array($today, [10, 20])) {
            // Cari invoice yang statusnya pending (atau yang mau diingatkan)
            $invoices = Invoice::join('order', 'order.id', '=', 'invoice.order_id')
                ->join('customer', 'customer.id', '=', 'order.customer_id')
                ->select('invoice.*', 'customer.email', 'customer.nama')
                ->where('invoice.url_bukti_potong_pph', null) // Ganti dengan kondisi yang sesuai
                ->get();

            foreach ($invoices as $invoice) {
                // Di sini kamu bisa kirim email, notifikasi, atau lainnya
                // Misal kirim email reminder
                Mail::to('abiyyu.umar16@gmail.com')->send(new RemindBupot($invoice));

                // Bisa juga update field misalnya reminder_sent_at, kalau mau
            }

            $this->info('Reminder pembayaran berhasil dikirim.');
        // } else {
        //     $this->info('Hari ini bukan jadwal reminder.');
        // }
    }
}
