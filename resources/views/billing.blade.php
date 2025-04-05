@extends('layout.main')
@section('title', 'Billing')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

<style>
    .container-table{
        margin-left: 3rem !important;
        margin-right: 3rem !important;
    }
    .swal-cancel-custom {
        color: #F53D3D !important;
        background: transparent !important;
        width: 25vw !important;
        height: 30px !important;
        border-radius: 50px !important;
        order: 1;
    }
    .swal-confirm-custom {
        margin-top: 50px !important;
        height: 50px !important;
        background: var(--Button-Orange, linear-gradient(99deg, #FF8210 -46.56%, #FF9C42 88.69%)) !important;
        width: 25vw !important;
        border-radius: 50px !important;
        order: 2;
    }
    .top-notif-wrapper {
            padding-left: 3rem !important;
            padding-right: 3rem !important;
        }
    @media screen and (max-width: 1256px) {
        .top-notif-wrapper {
            display: flex;
            flex-wrap: wrap;
            padding-left: 3rem !important;
            padding-right: 3rem !important;
        }

        #billingTable {
            width: 100% !important;
            scroll-behavior: auto !important;
        }
        .table-deskripsi{
            width: 100% !important;
        }
        .search-input{
            width: 100% !important;
        }
        .title-deposit{
            font-size: 8pt !important;
        }
        .title-harga{
            font-size: 12pt !important;
        }
        .modal-dialog{
            width: 90% !important;
            margin-left: 5% !important;
        }
        .modal{
            justify-content: center !important;
        }
        .text-diperbarui{
            text-align: right !important;
            width: 32% !important;
        }
        .text-sid{
            font-size: 10pt !important;
        }
        
    }
    @media screen and (max-width: 768px) {
        .top-notif-wrapper{
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        .container-table{
            margin-left: 1rem !important;
            margin-right: 1rem !important;
        }
    }
    table.dataTable tbody tr{
        background: transparent !important;
    }
    div.dt-container.dt-empty-footer tbody tr:last-child td {
        border: none;
    }
    table.dataTable thead tr th {
        border-bottom: none;
    }
</style>
<div class="top-notif-wrapper flex w-full gap-5" style="padding-top: 15vh !important;">
    {{-- <div class="d-flex p-3 w-100" style="background-color: #242134;border-radius: 10px">
        <div class="d-flex gap-5 w-100 justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <span class="text-white">Tagihan Terdekat</span>
                <span style="font-size: 20pt" class="text-white mt-1"><strong>IDR17,000,000</strong></span>
            </div>
            <button class="btn btn-primary m-0" style="background-color: #3399FE;border-radius: 10px;height: 80%;">Bayar
                Sekarang</button>
        </div>
    </div> --}}
    <div class="flex p-3 w-full" style="background-color: #242134;border-radius: 10px">
        <div class="flex gap-5 w-full justify-between items-center">
            <div class="flex flex-col">
                <span class="text-white title-deposit">Total Deposit Aktif</span>
                <span style="font-size: 20pt" class="text-white title-harga"><strong>IDR{{ number_format(($jumlahDepositAktif ?? 0), 0, ',', '.') }}</strong></span>
            </div>
            <button id="btnDepoAktif" class="bg-transparent border border-[#8158F4] px-4 py-2 rounded-lg title-deposit"
                style="border: 1px solid#8158F4;border-radius: 10px;color: #8158F4;height: 80%;">Lihat Rincian
                <i class="bi bi-eye-fill ps-1"></i>
            </button>
        </div>
    </div>
    <div class="flex p-3 w-full" style="background-color: #242134; border-radius: 10px">
        <div class="flex gap-5 w-full justify-between items-center">
            <div class="flex flex-col">
                <span class="text-white title-deposit">Total Deposit Terpakai</span>
                <span style="font-size: 20pt" class="text-white title-harga"><strong>IDR{{ number_format(($jumlahDepositTerpakai ?? 0), 0, ',', '.') }}</strong></span>
            </div>
            <button class="bg-transparent border border-[#8158F4] px-4 py-2 rounded-lg title-deposit"
                style="border: 1px solid#8158F4;border-radius: 10px;color: #8158F4;height: 80%"
                id="btnDepoTerpakai">Lihat Rincian
                <i class="bi bi-eye-fill ps-1"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-table p-3 my-3 mb-5" style="background-color: #242134;border-radius: 10px;margin-top: 20px">
    <span class="text-white"><strong>Tabel Billing</strong></span>
    <div class="flex gap-3 justify-between">
        <input type="text" id="customSearchBox" 
               class="w-full md:w-1/4 bg-[#242134] border border-[#939393] text-white rounded-lg px-4 py-2 mt-3" 
               placeholder="Cari">
        <div class="flex gap-2">
            <a href="/export-billing" class="mt-3 border border-white rounded-lg p-2 flex items-center justify-center h-10 w-10">
                <img src="{{ URL::asset('/assets/image/excel-icon.png') }}" class="w-6 h-6" alt="">
            </a>
            <button class="btn bg-transparent mt-3" id="btnFilter"
                style="border-radius: 10px;border:1px solid white;width: 42px;height: 42px;">
                <i class="bi bi-funnel-fill text-white"></i>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="billingTable" class="text-white w-100 mt-3 ">
            <thead style="color: #939393">
                <tr>
                    <th style="font-weight: 500">ID Order</th>
                    <th class="text-left" style="font-weight: 500">SID</th>
                    <th style="font-weight: 500">Jatuh Tempo</th>
                    <th style="font-weight: 500">Nominal</th>
                    <th style="font-weight: 500">Status Pembayaran</th>
                    <th style="font-weight: 500">Status upload Bupot</th>
                    <th style="font-weight: 500">Jenis</th>
                    <th style="font-weight: 500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr style="border-top: 1px solid #939393 !important;">
                    <td colspan="1">{{ $d['nomor'] }} <span class="hidden">{{ $d['nama'] }}</span></td>
                    <td>{{ $d['nama'] }}</td>
                    <td>{{ $d['tgl_jatuh_tempo'] }}</td>
                    <td>Rp{{ number_format($d['jumlah'], 0, ',', '.') }}</td>
                    <td>
                        @if($d['status'] == "paid")
                        <span style="color: #8CC243">Dibayar</span>
                        @else
                        <span style="color: #FD6464">Belum Dibayar</span>
                        @endif
                    </td>
                    <td>
                        @if($d['status_perusahaan'] == '2')
                        <span style="color: #8CC243">Sudah</span>
                        @elseif($d['url_bukti_potong_pph'] == null)
                        <span style="color: #FD6464">Belum</span>
                        @else
                        <span style="color: #8CC243">Sudah</span>
                        @endif
                    </td>
                    <td>{{ $d['type'] }}</td>
                    <td class="flex justify-end w-full">
                        @if($d['status'] == "PAID")
                        <button class="btn btn-primary m-3 ms-0 border-0"
                            style="background-color: #263D51;border-radius: 10px" disabled>Bayar Sekarang</button>
                        @elseif($d['status'] != "PAID")
                        <a class="bg-[#3399FE] text-white font-medium py-2 px-4 rounded-lg m-3 ms-0 shadow-md hover:bg-blue-600 transition"
                            href="{{ route('pilih.metode.pembayaran.recurring', ['id' => $d['id']]) }}">
                            Bayar Sekarang
                        </a>
                        @else
                        <a class="bg-[#3399FE] text-white font-medium py-2 px-4 rounded-lg m-3 ms-0 shadow-md hover:bg-blue-600 transition"
                            href="{{ route('pilih.metode.pembayaran.recurring', ['id' => $d['id']]) }}">
                            Bayar Sekarang
                        </a>
                        @endif
                        <button class="border-0 bg-transparent toggle-details">
                            <i class="bi bi-chevron-down text-white" style="font-weight: 800;font-size: x-large"></i>
                        </button>
                    </td>
                </tr>
                <tr class="detail-row" style="display: none">
                    <td colspan="8">
                        <div class="p-3 mb-2" style="background: rgba(255, 255, 255, 0.10);border-radius: 10px">
                            <table class="table-deskripsi" style="width: 100%;border:none; padding-right: 0px; margin-right: 0px;">
                                <thead>
                                    <tr>
                                        <th class="text-white" style="width: 20%; font-weight: 600;">Tanggal Invoice</th>
                                        <th class="text-white" style="width: 20%; font-weight: 600;">Node</th>
                                        <th class="text-white" style="width: 20%; font-weight: 600;">Faktur Pajak</th>
                                        @if ($d['status_perusahaan'] == 1)
                                            <th class="text-white" style="width: 20%;  font-weight: 600;">Bukti Potong PPh 23</th>
                                        @elseif($d['status_perusahaan'] == 2)
                                        @else
                                            <th class="text-white" style="width: 20%;  font-weight: 600;">Bukti Potong PPh 23</th>
                                        @endif
                                        {{-- @if($d['status'] != "PAID" && $d['auto_debit'] == 1)
                                            <th style="width: 20%;  font-weight: 600; color: #FD6464; font-size: 12px;"><div class="d-flex align-items-center" style="color: #FD6464; font-size: 12px;">
                                                <i class="bi bi-exclamation-circle" style="font-size: 18px; margin-right: 10px;"></i>
                                                <span>Pembayaran autodebit melalui kartu kredit Anda tidak berhasil</span>
                                            </div>
                                            </th>
                                        @endif --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-white">{{ $d['tgl_invoice'] }}</td>
                                        <td class="text-white">{{ $d['nama'] }}</td>
                                        <td>
                                            tes
                                        </td>
                                        <td>
                                            @if ($d['status_perusahaan'] == '1')
                                                {{-- @if($d['type'] == 'OTC')   --}}
                                                @if ($d['url_bukti_potong_pph'] == null)
                                                <button class="bg-[#3399FE] text-white font-medium py-2 px-5 rounded-lg shadow-md hover:bg-blue-600 transition flex items-center btn-pph"
                                                    data-id="{{ $d['id'] }}" 
                                                    id="btnPph">
                                                    <i class="bi bi-upload me-2"></i>
                                                    Upload File
                                                </button>
                                                @else
                                                <span class="text-green-500">Sudah di upload</span>
                                                @endif
                                            @elseif($d['status_perusahaan'] == '2')
                                            @else
                                                {{-- @if($d['type'] == 'OTC')   --}}
                                                @if ($d['url_bukti_potong_pph'] == null)
                                                <button class="bg-[#3399FE] text-white font-medium py-2 px-5 rounded-lg shadow-md hover:bg-blue-600 transition flex items-center gap-2 btn-pph"
                                                    data-id="{{ $d['id'] }}" 
                                                    id="btnPph">
                                                    <i class="bi bi-upload"></i>
                                                    Upload File
                                                </button>
                                                @else
                                                <span class="text-green-500">Sudah di upload</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if($d['status'] != "PAID" && $d['auto_debit'] == 1)
                                                <a href="{{ route('bayar-recurring', ['id' => $d['id_invoice']]) }}" style="font-weight: 600; color: #3399FE; font-size: 16px; margin-left: 30px; text-decoration: underline; ">
                                                    Perbarui Kartu Kredit
                                                </a>
                                            @endif --}}
                                        </td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table-deskripsi" style="width: 100%; padding-right: 0px; margin-right: 0px;">
                                <thead>
                                    <tr>
                                        <th class="text-white" style="width: 20%;  font-weight: 600;">Nomor Invoice</th>
                                        <th class="text-white" style="width: 20%; font-weight: 600;">Alamat Layanan</th>
                                        <th class="text-white" style="width: 20%; font-weight: 600;">Invoice</th>
                                        @if($d['status'] != "PAID")
                                            <th class="text-white" style="width: 20%;  font-weight: 600; color: #FD6464; font-size: 12px;"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-white">{{ $d['nomor'] }}</td>
                                        <td class="text-white">{{ $d['alamat'] }}</td>
                                        <td colspan="2" class="text-white">
                                            @if ($d['url_invoice'] == null && $d['url_tanda_terima'] == null)
                                            Invoice belum tersedia
                                            @elseif ($d['url_tanda_terima'] != null)
                                            <a href="{{ route('download.tanda.terima', ['filename' => $d['url_tanda_terima']]) }}"
                                                class="btn btn-primary ps-5 pe-5"
                                                style="border: none;border-radius: 10px;background-color: #8158F4">
                                                <i class="bi bi-download me-2 "></i>
                                                Unduh File
                                            </a>
                                            @else
                                            <a href="{{ route('download.invoice', ['filename' => $d['url_invoice']]) }}"
                                                class="btn btn-primary ps-5 pe-5"
                                                style="border: none;border-radius: 10px;background-color: #8158F4">
                                                <i class="bi bi-download me-2 "></i>
                                                Unduh File
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if($d['status'] != "PAID" && $d['auto_debit'] == 1)
                                                <span style="font-weight: 600; color: #FD6464; font-size: 12px;"></span>
                                            @endif --}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td class="hidden">
                        
                    </td>
                    <td class="hidden">{{ $d['nomor'] }}</td>
                    <td class="hidden"></td>
                    <td class="hidden">
                        @if($d['status'] == "paid")
                        <span style="color: #8CC243">Dibayar</span>
                        @else
                        <span style="color: #FD6464">Belum Dibayar</span>
                        @endif
                    </td>
                    <td class="hidden">
                        @if($d['status_perusahaan'] == '2')
                        <span style="color: #8CC243">Lunas</span>
                        @elseif($d['url_bukti_potong_pph'] == null && $d['status_perusahaan'] == '3')
                        <span style="color: #FD6464">Belum Lunas</span>
                        @elseif($d['url_bukti_potong_pph'] == null && $d['status_perusahaan'] == '1' && $d['url_bukti_potong_ppn'] == null)
                        <span style="color: #FD6464">Belum Lunas</span>
                        @else
                        <span style="color: #8CC243">Lunas</span>
                        @endif
                    </td>
                    <td class="hidden">{{ $d['tgl_jatuh_tempo'] }}</td>
                    <td class="hidden">{{ $d['type'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $snap_token }}

    </div>
</div>
<div class="fixed inset-0 flex items-start justify-center mt-5 z-50 hidden" id="depoAktif" tabindex="-1" role="dialog">
    <div class="w-[35vw] max-w-none bg-[#242134] rounded-lg shadow-lg p-4">
        <h4 class="text-center text-white my-3 text-lg font-medium">
            Rincian Deposit Aktif
        </h4>
        <div class="flex flex-col p-2">
            @foreach ($depositAktif as $da)
            <div class="flex justify-between p-2 mb-3 border border-white rounded-lg">
                <div class="flex flex-col">
                    <span class="text-gray-400 font-bold">Nomor {{ $da['nomor'] }}</span>
                    <span class="text-white mt-1 font-bold">IDR{{ number_format($da['jumlah'], 0, ',', '.') }}</span>
                </div>
                <span class="text-white text-xs self-center">
                    <small>Diperbaharui pada {{ \Carbon\Carbon::parse($da['tgl'])->translatedFormat('j F Y') }}</small>
                </span>
            </div>
            @endforeach
            <a class="text-center text-red-400 my-2 cursor-pointer hover:underline" id="closeDepoAktif">
                Tutup
            </a>
        </div>
    </div>
</div>

<div class="fixed inset-0 flex items-start justify-center mt-5 z-50 hidden" id="depoTerpakai" tabindex="-1" role="dialog">
    <div class="w-[35vw] max-w-none bg-[#242134] rounded-lg shadow-lg">
        <div class="p-4">
            <h4 class="text-center text-white my-3 text-lg font-medium">
                Rincian Deposit Terpakai
            </h4>
            <div class="flex flex-col p-2">
                @foreach ($depositTerpakai as $dt)
                <div class="flex justify-between p-2 mt-2 border border-white rounded-lg">
                    <div class="flex flex-col">
                        <span class="text-gray-400 font-bold">SID {{ $dt['sid_tsat'] }}</span>
                        <span class="text-white mt-1 font-bold">IDR{{ number_format($dt['jumlah'], 0, ',', '.') }}</span>
                    </div>
                    <span class="text-white text-xs self-center">
                        <small>Diperbaharui pada {{ \Carbon\Carbon::parse($dt['tgl_deposit'])->translatedFormat('j F Y') }}</small>
                    </span>
                </div>
                @endforeach
                <a class="text-center text-red-400 my-2 cursor-pointer hover:underline" id="closeDepoTerpakai">
                    Tutup
                </a>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal mt-5" id="filterModal" style="border-radius: 10px" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width: 32vw;max-width: unset;" role="document">
        <div class="modal-content p-2" style="border-radius: 10px;background-color: #242134">
            <div class="modal-body">
                <h4 class="text-white mt-2 mb-2" style="font-weight: 700">Filter</h4>
                <div class="filter-options">
                    <span class="text-white" style="font-weight: 600">Status Pembayaran</span>
                    <div class="radio-group mt-1 mb-3">
                        <label>
                            <input type="radio" id="unpaid" name="status" value="Belum Dibayar">
                            <span style="font-weight: 300">Belum Dibayar</span>
                        </label>
                        <label>
                            <input type="radio" id="unpaid" name="status" value="Dibayar">
                            <span style="font-weight: 300">Dibayar</span>
                        </label>
                    </div>
                    <span class="text-white" style="font-weight: 600">Status Pelunasan</span>
                    <div class="radio-group mt-1 mb-3">
                        <label>
                            <input type="radio" id="unpaid" name="status_pelunasan" value="Belum Lunas">
                            <span style="font-weight: 300">Belum Lunas</span>
                        </label>
                        <label>
                            <input type="radio" id="unpaid" name="status_pelunasan" value="Lunas">
                            <span style="font-weight: 300">Lunas</span>
                        </label>
                    </div>
                    <span class="text-white" style="font-weight: 600">Node</span>
                    <div class="radio-group mt-1">
                        @foreach ($node as $d)
                        <label for="">
                            <input type="checkbox" id="node1" name="node" value="{{ $d['nama'] }}">
                            <span style="font-weight: 300" for="node1">{{ $d['nama'] }}</span>
                        </label>
                        @endforeach
                    </div>
                    <button class="btn btn-secondary text-white w-100 mt-4" style="border:none; border-radius: 10px;" id="resetFilter">Reset Filter</button>
                    <button class="btn btn-info text-white w-100 mt-2"
                        style="background-color: #8158F4;border:none; border-radius: 10px;" id="applyFilter">Terapkan
                        Filter</button>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="uploadPphModal" tabindex="-1" role="dialog">
    <div class="w-[32vw] max-w-none bg-[#242134] rounded-2xl shadow-lg p-6">
        <div class="w-full flex justify-end">
            <button id="closeUploadPphModal" class="text-white text-xl hover:text-gray-400">
                &times;
            </button>
        </div>
        <h5 class="text-white text-lg font-semibold text-center mt-2 mb-4">
            Bukti Potong PPH 23
        </h5>
        <form action="{{ route('upload.bukti.pph') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mt-3 relative">
                <input type="file" class="absolute inset-0 w-full h-[8vh] opacity-0 cursor-pointer" 
                    id="uploadPphFile" name="pph" accept=".jpg,.jpeg,.png,.pdf" required>
                <button type="button" class="border border-dashed border-gray-500 text-gray-500 bg-transparent py-3 w-full flex items-center justify-center gap-2"
                    id="uploadPphButton">
                    <i class="bi bi-file-earmark-arrow-up-fill"></i>
                    Upload Bukti Potong PPH 23
                </button>
            </div>
            <label class="text-white block mt-2" id="countingpph"></label>
            <input type="hidden" name="id" id="idOrderPph" value="">
            <button type="submit" class="bg-[#8158F4] text-white w-full py-2 mt-4 rounded-lg">
                Submit
            </button>
        </form>
    </div>
</div>

<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="uploadPpnModal" tabindex="-1" role="dialog">
    <div class="w-[32vw] max-w-none bg-[#242134] rounded-2xl shadow-lg p-6">
        <div class="w-full flex justify-end">
            <button id="closeUploadPpnModal" class="text-white text-xl hover:text-gray-400">
                &times;
            </button>
        </div>
        <h5 class="text-white text-lg font-semibold text-center mt-2 mb-4">
            Bukti SSP Wapu PPN
        </h5>
        <form action="{{ route('upload.bukti.ppn') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mt-3 relative">
                <input type="file" class="absolute inset-0 w-full h-[8vh] opacity-0 cursor-pointer" 
                    id="uploadPpnFile" name="ppn" accept=".jpg,.jpeg,.png,.pdf" required>
                <button type="button" class="border border-dashed border-gray-500 text-gray-500 bg-transparent py-3 w-full flex items-center justify-center gap-2"
                    id="uploadPpnButton">
                    <i class="bi bi-file-earmark-arrow-up-fill"></i>
                    Upload Bukti Potong SSP WAPU PPN
                </button>
            </div>
            <label class="text-white block mt-2" id="countingppn"></label>
            <input type="hidden" name="id" id="idOrder" value="">
            <button type="submit" class="bg-[#8158F4] text-white w-full py-2 mt-4 rounded-lg">
                Submit
                
            </button>
        </form>
    </div>
</div>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-scNhgP-5noD4Cx7M"></script>
@if ($snap_token != null)
<script>
    window.snap.pay('{{ $snap_token }}', 
        {
            onSuccess: function(result) {
                /* You may add your own implementation here */
                console.log(result);
                alert("payment success!");
            },
            onPending: function(result) {
                /* You may add your own implementation here */
                console.log(result);
                alert("wating your payment!");
            },
            onError: function(result) {
                /* You may add your own implementation here */
                console.log(result);
                alert("payment failed.");
            },
            onClose: function() {
                /* You may add your own implementation here */
                alert('you closed the popup without finishing the payment');
            }
        }
    );
</script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', () => {
            const jwt_token = '{{ Session::get('jwt_token') }}';

        });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('uploadPpnFile').addEventListener('change', function(event) {
            var fileName = event.target.files[0].name;
            var uploadButton = document.getElementById('uploadPpnButton');
            uploadButton.innerHTML = '<i class="bi bi-file-earmark-arrow-up-fill"></i> ' + fileName;
        });

        document.getElementById('uploadPpnButton').addEventListener('click', function() {
            document.getElementById('uploadPpnFile').click();
        });
        document.getElementById('uploadPphFile').addEventListener('change', function(event) {
            var fileName = event.target.files[0].name;
            var uploadButton = document.getElementById('uploadPphButton');
            uploadButton.innerHTML = '<i class="bi bi-file-earmark-arrow-up-fill"></i> ' + fileName;
        });

        document.getElementById('uploadPphButton').addEventListener('click', function() {
            document.getElementById('uploadPphFile').click();
        });

        document.addEventListener("DOMContentLoaded", function () {
        @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
        var token = '{{ Session::get('jwt_token') }}';
            const footerDisplay = document.getElementById('footer');
            if (token) {
                footerDisplay.classList.add('hidden');
            } else {
                footerDisplay.classList.add('hidden');
            }
        });
</script>
<script>
    $(document).ready(function() {
        $('#resetFilter').on('click', function() {
            // Reset radio buttons
            $('input[type="radio"]').prop('checked', false);
            $('input[type="checkbox"]').prop('checked', false);
        });
        // Modal toggle handlers
        $('#btnDepoAktif').click(function() {
            document.getElementById("depoAktif").classList.remove('hidden');
        });
        $('#closeDepoAktif').click(function() {
            document.getElementById("depoAktif").classList.add('hidden');
        });
        $('#btnDepoTerpakai').click(function() {
            document.getElementById("depoTerpakai").classList.remove('hidden');
        });
        $('#closeDepoTerpakai').click(function() {
            document.getElementById("depoTerpakai").classList.add('hidden');
        });
        $('#btnFilter').click(function() {
            $('#filterModal').modal('show');
        });
        $('#closeUploadPpnModal').click(function() {
            document.getElementById("uploadPpnModal").classList.add('hidden');
        });
        $('#closeUploadPphModal').click(function() {
            document.getElementById("uploadPphModal").classList.add('hidden');
        });

        // Handle PPn and PPh modals
        $(document).on('click', '.btn-ppn', function() {
            var id = $(this).data('id');
            // var countingppn = $(this).data('countingppn');
            // if(countingppn == null){
            //     countingppn = 0;
            // }
            // countingppn = 3 - countingppn;
            $("#idOrder").val(id);
            // $("#countingppn").text("Anda mempunyai batas untuk mengupload bukti potong PPN sebanyak " +countingppn + " kali");
            document.getElementById("uploadPpnModal").classList.remove('hidden');
        });
        $(document).on('click', '.edit-btn-ppn', function() {
            var id = $(this).data('id');
            // var countingppn = $(this).data('countingppn');
            // if(countingppn == null){
            //     countingppn = 0;
            // }
            // countingppn = 3 - countingppn;
            $("#idOrder").val(id);
            // $("#countingppn").text("Anda mempunyai batas untuk mengupload bukti potong PPN sebanyak " +countingppn + " kali");
            document.getElementById("uploadPpnModal").classList.remove('hidden');

        });
        $(document).on('click', '.btn-pph', function() {
            var id = $(this).data('id');
            var countingpph = $(this).data('countingpph');
            if(countingpph == ''){
                countingpph = 0;
            }
            countingpph = 3 - countingpph;
            $("#idOrderPph").val(id);
            $("#countingpph").text("Anda mempunyai batas untuk mengupload bukti potong PPH 23 sebanyak "+countingpph+" kali");
            document.getElementById("uploadPphModal").classList.remove('hidden');

        });
        $(document).on('click', '.edit-btn-pph', function() {
            var id = $(this).data('id');
            var countingpph = $(this).data('countingpph');
            if(countingpph == null){
                countingpph = 0;
            }
            countingpph = 3 - countingpph;
            $("#idOrderPph").val(id);
            $("#countingpph").text("Anda mempunyai batas untuk mengupload bukti potong PPH 23 sebanyak "+countingpph+" kali");
            document.getElementById("uploadPphModal").classList.remove('hidden');
        });

        // Toggle details
        $(document).on('click', '.toggle-details', function() {
            const button = $(this);
            const detailRow = button.closest('tr').next('.detail-row');


            if (detailRow.is(':visible')) {
                detailRow.hide();
                button.find('i').toggleClass('bi-chevron-up bi-chevron-down');
            } else {
                detailRow.show();
                button.find('i').toggleClass('bi-chevron-down bi-chevron-up');
            }
        });

        // DataTables setup
        var table = $('#billingTable').DataTable({
            "lengthMenu": [[10], [10]], 
            "pageLength": 8,
            "dom": 'rtip',
            "columns": [
                { "width": "15%" },  // Column 1 width
                { "width": "10%" },  // Column 2 width
                { "width": "10%" },  // Column 3 width
                { "width": "15%" },  // Column 4 width
                { "width": "15%" },  // Column 5 width
                { "width": "15%" },  // Column 6 width
                { "width": "10%" },  // Column 7 width
                { "width": "10%" }   // Column 8 width
            ],
            "autoWidth": false,
            "order": [],
        });

        // Custom search box
        $('#customSearchBox').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Custom filter
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var status = $('input[name="status"]:checked').val();
                var statusPelunasan = $('input[name="status_pelunasan"]:checked').val();
                var nodes = $('input[name="node"]:checked').map(function() {
                    return $(this).val();
                }).get();
                var paymentStatus = data[4]; // Column 5 (status pembayaran)
                var nodeName = data[0]; // Column 1 (node name)
                var paymentStatusPelunasan = data[5]; // Column 6 (status pelunasan)

                if ((status === undefined || paymentStatus === status) &&
                    (nodes.length === 0 || nodeName.includes(nodes)) && (statusPelunasan === undefined || paymentStatusPelunasan === statusPelunasan)) {
                    return true;
                }
                return false;
            }
        );

        // Apply filter button
        $('#applyFilter').on('click', function() {
            table.draw();
            $('#filterModal').modal('hide');
        });
        
    });

</script>
@endsection
