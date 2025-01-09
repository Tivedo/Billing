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
      <div class="w-full md:w-1/3 p-4 cursor-pointer">
       <img alt="Image of a fixed site device" class="" height="200" src="https://firebasestorage.googleapis.com/v0/b/image-211fd.appspot.com/o/get-image.png?alt=media&token=001cd25e-45c7-4098-b525-1e08b872b407" width="300"/>
       <h3 class="text-xl text-gray-100 font-bold mt-4">
        Fixed Site
       </h3>
       <p class="text-gray-100">
        Unlimited Standard Data, Network Priority, Priority Support
       </p>
      </div>
      <div class="w-full md:w-1/3 p-4 cursor-pointer">
       <img alt="Image of a mobility device" class="" height="200" src="https://firebasestorage.googleapis.com/v0/b/image-211fd.appspot.com/o/get-image%20(1).png?alt=media&token=4df00ff1-bf15-4432-b275-9e061ae30452" width="300"/>
       <h3 class="text-xl text-gray-100 font-bold mt-4">
        Mobility
       </h3>
       <p class="text-gray-100">
        Unlimited Inland Data, In - Motion + Ocean Use, Network Priority, Priority Support
       </p>
      </div>
     </div>
    </div>
   </div>
  </div>
@endsection
