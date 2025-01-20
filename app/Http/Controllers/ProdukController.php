<?php

namespace App\Http\Controllers;

use App\Models\Produk;
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
        dd($produk);
        return view('detail-produk', ['produk' => $produk]);
    }
}
