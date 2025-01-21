<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Layanan;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('produk', ['produk' => $produk]);
    }
    public function detail($id)
    {
        $produk = Produk::find($id);
        $layanan = Layanan::where('produk_id', $id)->get();
        return view('detail-produk', ['produk' => $produk, 'layanan' => $layanan]);
    }
}
