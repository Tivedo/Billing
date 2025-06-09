<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_item';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public static function getInvoiceDetail($id)
    {
        $data = InvoiceItem::join('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->leftJoin('layanan', 'invoice_item.layanan_id', '=', 'layanan.id')
            ->leftJoin('order', 'invoice.order_id', '=', 'order.id')
            ->leftJoin('kontrak', 'order.id', '=', 'kontrak.order_id')
            ->leftJoin('customer', 'order.customer_id', '=', 'customer.id')
            ->where('invoice.id', $id)
            ->select('invoice_item.*', 'invoice.nomor', 'invoice.tgl_invoice', 'invoice.tgl_jatuh_tempo', 'customer.alamat', 'customer.nama as nama_customer', 'customer.npwp', 'kontrak.no_kontrak as nomor_kontrak', 'layanan.nama as nama_layanan',
            DB::raw("
                    CONCAT(
                        DATE_FORMAT(invoice.tgl_invoice, '%e'),
                        ' - ',
                        DAY(LAST_DAY(invoice.tgl_invoice)),
                        ' ',
                        MONTHNAME(invoice.tgl_invoice),
                        ' ',
                        YEAR(invoice.tgl_invoice)
                    ) as tgl_tagih_formatted
                "),
            )
            ->get()->toArray();
        return $data;
    }
}
