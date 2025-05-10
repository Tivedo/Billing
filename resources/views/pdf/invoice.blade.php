<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
            
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .invoice-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            padding-top: 10px !important;
            page-break-inside: avoid;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details, .payment-details {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td, .payment-details th, .payment-details td {
            border: 2px solid #000;
            text-align: center;
            border-collapse: collapse;
        }
        .invoice-details th, .payment-details th {
            background-color: #C0C0C0;
        }
        .invoice-details-2, .payment-details {
            width: 100%;
            margin-bottom: 20px;
            border: 2px solid #000;
            border-collapse: collapse;
        }
        .payment-details td {
            border-bottom: none;
        }
        .invoice-details-2 th, .invoice-details-2 td, .payment-details-2 th, .payment-details-2 td {
            padding: 8px;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-details-2 th {
            background-color: #C0C0C0;
            border-right: 2px solid #000;
        }
        .total-amount {
            text-align: right !important;
            font-weight: bold;
            border: none !important;
        }
        .signature {
            margin-top: 20px;
            display: flex;
            justify-content: end;
            text-align: right; /* Tambahkan ini untuk memastikan teks berada di kanan */
        }
        .total-container {
            display: flex;
            justify-content: space-between;
            background-color: #C0C0C0;
            width: max-content;
            padding: 5px;
        }
        .details, .amount {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;

        }
        .details td, .amount td {
            padding: 5px 0;
        }
        .details td:first-child, .amount td:first-child {
            width: 200px;
            padding: 0px
        }
        .amount td:last-child {
            text-align: right;
        }
        .amount td:first-child {
            text-align: right;
        }
        .total {
            font-weight: bold;
        }
        .footer {
            text-align: left;
        }
    
        .footer-container {
            bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        /* .table-service-fee, .table-service-fee th, .table-service-fee td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .table-service-fee th{
            background-color: #ABD7EB
        } */
    .table-service-fee {
      border-collapse: collapse;
      margin-top: 10px;
    }
    .table-service-fee th, .table-service-fee td {
      border: 1px solid black;
      padding: 5px;
      text-align: center;
    }
    .table-service-fee th {
      background-color: #d3d3d3;
    }
    .spacer {
      border: none !important;
      background: transparent !important;
    }

    </style>
</head>
<body>
    @php
        use Carbon\Carbon;
    @endphp
    <div class="header">
        <h1>KWITANSI</h1>
    </div>
    <div class="invoice-container">
        <table class="details">
            <tr>
                <td style="border-bottom: 2px solid #000"><strong>No. Kwitansi</strong></td>
                <td style="border-bottom: 2px solid #000"><strong>: {{ $dataSbs[0]['nomor'] }}</strong></td>
                <td style="border-bottom: 2px solid #000"><strong>Tanggal Jatuh Tempo</strong></td>
                <td style="border-bottom: 2px solid #000;text-align: right !important"><strong>: {{ Carbon::parse($dataSbs[0]['tgl_invoice'])->addDays(20)->format('d-m-Y') }}</strong></td>
            </tr>
        </table>
        <table class="details">
            
            <tr style="height: 50px;">
                <td style="vertical-align: top"><strong>Terima dari</strong></td>
                <td>: {{ $dataSbs[0]['nama_customer'] }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 5px">{{ $dataSbs[0]['alamat']  }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>NPWP</strong></td>
                <td>: {{ $dataSbs[0]['npwp'] }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Untuk pembayaran</strong></td>
                <td>: Penyediaan Layanan internet {{ $dataSbs[0]['nama_customer'] }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table class="amount">
            <tr>
                <td></td>
                <td style="width: 100px">Nomor invoice</td>
                <td><strong>: {{ $dataSbs[0]['nomor'] }}</strong></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><strong>Harga Jual</strong></td>
                <td colspan="3"><strong>Rp {{number_format($total_tagihan, 0, ',', '.')  }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><strong>DPP Nilai Lain (11/12 x Harga Jual)</strong></td>
                <td colspan="3"><strong>Rp {{number_format($total_dpp_lain, 0, ',', '.')  }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="border-bottom: 2px solid #000"><strong>PPN 12% (12% x DPP Nilai Lain)</strong></td>
                <td colspan="3" class="total-amount"><strong><span>Rp {{number_format($total_ppn, 0, ',', '.')  }}</span></strong></td>
            </tr>
            <tr class="total">
                <td></td>
                <td></td>
                <td>Total</td>
                <td colspan="3" class="total-amount"><strong><span>Rp {{number_format($total, 0, ',', '.')  }}</span></strong></td>
            </tr>
        </table>
        <table class="amount">
            <td style="text-align: left">Terbilang</td>
            <td style="text-align: left;background-color: #C0C0C0"><strong>: {{ $terbilang }}</strong></td>
        </table>
        <div class="signature">
            <table style="width: 100%">
                <tr>
                    <th style="width: 65%"></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><p>Bogor, {{ Carbon::parse($dataSbs[0]['tgl_invoice'])->format('d-m-Y') }}</p></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><p style="margin-top: -10px"><strong><u>Jhon Doe</u></strong><br>
                        Manager Billing</p></td>
                </tr>
            </table>
        </div>
        <p><strong>TOTAL</strong></p>
        <div class="total-container" style="width: 20%">
            <span><strong>Rp </strong></span>
            <span style="text-align: right"><strong>{{number_format($total, 0, ',', '.')  }}</strong></span>
        </div>
            </p>
        {{-- <p>Pembayaran dapat dilakukan melalui Sistem Billing  menggunakan metode Virtual Account (VA) <br> dari pilihan bank sebagai berikut:</p>
        <table class="table-service-fee">
            <tr>
              <th>Bank</th>
              <th>Service Fee</th>
              <th class="spacer"></th> <!-- Spacer -->
              <th>Bank</th>
              <th>Service Fee</th>
              <th class="spacer"></th> <!-- Spacer -->
              <th>Bank</th>
              <th>Service Fee</th>
            </tr>
            <tr>
              <td>Mandiri</td><td>Rp 1.750</td><td class="spacer"></td>
              <td>BCA</td><td>Rp 2.000</td><td class="spacer"></td>
              <td>Permata</td><td>Rp 2.000</td>
            </tr>
            <tr>
              <td>BNI</td><td>Rp 1.750</td><td class="spacer"></td>
              <td>BTN</td><td>Rp 2.000</td><td class="spacer"></td>
              <td>Mega</td><td>Rp 1.500</td>
            </tr>
            <tr>
              <td>BRI</td><td>Rp 1.750</td><td class="spacer"></td>
              <td>BSI</td><td>Rp 2.500</td><td class="spacer"></td>
              <td style="border: none !important"></td>
              <td style="border: none !important"></td>
            </tr>
          </table>   --}}
        {{-- <p>Dokumen ini ditandatangani secara elektronik dan diakui secara sah oleh PT. XYZ</p> --}}
    </div>
    <div class="invoice-container">
        <div class="header">
            <h1>INVOICE</h1>
            <p>BIAYA BERLANGGANAN</p>
        </div>

        <table class="invoice-details">
            <tr>
                <th>NOMOR INVOICE</th>
                <th>TGL. INVOICE</th>
                <th>JATUH TEMPO</th>
                <th>NO KONTRAK</th>
            </tr>
                <td>{{ $dataSbs[0]['nomor'] }}</td>
                <td>{{ Carbon::parse($dataSbs[0]['tgl_invoice'])->format('d-m-Y') }}</td>
                <td>{{ Carbon::parse($dataSbs[0]['tgl_invoice'])->addDays(20)->format('d-m-Y') }}</td>
                <td>{{ $dataSbs[0]['nomor_kontrak'] }}</td>
        </table>
        <table class="invoice-details-2">
            
            <tr>
                <th>Nama Badan</th>
                <td colspan="3"><strong> {{ $dataSbs[0]['nama_customer'] }} </strong></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td colspan="3">{{ $dataSbs[0]['alamat'] }}</td>
            </tr>
            <tr>
                <th>NPWP</th>
                <td colspan="3">{{ $dataSbs[0]['npwp'] }}</td>
            </tr>
            <tr>
                <th>Total Tagihan</th>
                <td colspan="3"><strong>Rp{{number_format($total, 0, ',', '.')  }}</strong></td>
            </tr>
            <tr>
                <th>Terbilang</th>
                <td colspan="3="><strong>{{ $terbilang }}</strong></td>
            </tr>
        </table>

        <table class="payment-details" >
            <tr>
                <th>No</th>
                <th>Layanan</th>
                <th>Periode</th>
                <th>Biaya</th>
                <th>Prorate</th>
                <th>Jumlah Biaya (Rp)</th>
            </tr>
            @foreach ($dataSbs as $key => $data)
            <tr height="100px" style="vertical-align: top">
                <td>{{ $key + 1 }}</td>
                <td style="text-align: left">
                    {{ $data['nama_layanan'] }} 1 bulan
                </td>                
                <td>{{ $data['tgl_tagih_formatted'] }}</td>
                <td style="text-align: right">{{number_format(($data['nilai_pokok']), 0, ',', '.')}}</td>
                <td>FULL</td>
                <td style="text-align: right">{{number_format($data['nilai_bayar'], 0, ',', '.')}}</td>
            </tr>
            @endforeach
            <tr style="background-color: #C0C0C0;">
                <td colspan="5" class="total-amount">Harga Jual</td>
                <td style="border:none;border-left: 2px solid #000;text-align: right"><strong><span>{{number_format($total_tagihan, 0, ',', '.')  }}</span></strong></td>
            </tr>
            <tr style="background-color: #C0C0C0;">
                <td colspan="5" class="total-amount">DPP Nilai Lain (11/12 x Harga Jual)</td>
                <td style="border:none;border-left: 2px solid #000;text-align: right"><strong><span>{{number_format($total_dpp_lain, 0, ',', '.')  }}</span></strong></td>
            </tr>
            @if($dataSbs[0]['ppn'] != 0)
            <tr style="background-color: #C0C0C0;">
                <td colspan="5" class="total-amount">PPN 12% (12% x DPP Nilai Lain)</td>
                <td style="border:none;border-left: 2px solid #000;text-align: right"><strong><span>{{number_format($total_ppn, 0, ',', '.')  }}</span></strong></td>
            </tr>
            @endif
            <tr style="background-color: #C0C0C0;">
                <td colspan="5" class="total-amount">Grand Total</td>
                <td style="border:none;border-left: 2px solid #000;text-align: right"><strong><span>{{number_format(($total), 0, ',', '.')  }}</span></strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
