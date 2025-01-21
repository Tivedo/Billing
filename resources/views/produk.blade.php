@extends('layout.main')
@section('title', 'Daftar Akun')
<style>
   
</style>
@section('content')
  <div class="container mx-auto p-4">
   <div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-1/4 p-4">
     <h2 class="text-2xl text-gray-100 font-bold mb-4">
      Kategori
     </h2>
     <ul>
      <li class="mb-2">
       <div class="bg-gray-800 text-white py-2 px-4 rounded w-full text-left">
        SBS
       </div>
      </li>
     </ul>
    </div>
    <!-- Main Content -->
    <div class="w-full md:w-3/4 p-4">
    <h2 class="text-2xl text-gray-100 font-bold">
    Produk
    </h2>
    <hr class="border border-gray-300 w-full my-5"/>
    <div class="flex flex-col md:flex-row items-center">
    @foreach ($produk as $value)
      <div class="w-full md:w-1/3 p-4 cursor-pointer" onclick="window.location='{{ route('produk.detail', $value->id) }}'">
       <img alt="Image of a fixed site device" class="" height="200" src="{{ $value->image }}" width="300"/>
       <h3 class="text-xl text-gray-100 font-bold mt-4">
        {{ $value->nama }}
       </h3>
       <p class="text-gray-100">
        {{ $value->deskripsi }}
       </p>
      </div>
     @endforeach
    </div>
    </div>
   </div>
  </div>
@endsection
