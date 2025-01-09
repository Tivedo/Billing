@extends('layout.main')
@section('title', 'Daftar Akun')
<style>
   
</style>
@section('content')
     <div class="container mx-auto p-4 flex flex-col lg:flex-row items-start lg:items-center justify-between">
      <div class="flex flex-col lg:flex-row items-start lg:items-center">
       <img alt="Satellite dish on a stand" class="w-full lg:w-1/3" height="300" src="https://firebasestorage.googleapis.com/v0/b/image-211fd.appspot.com/o/get-image.png?alt=media&token=001cd25e-45c7-4098-b525-1e08b872b407" width="400"/>
       <div class="mt-4 lg:mt-0 lg:ml-8">
        <h1 class="text-3xl text-gray-100 font-bold">
         Fixed Site
        </h1>
        <div class="mt-4">
         <h2 class="text-xl text-gray-100 font-semibold">
          Pilih Perangkat
         </h2>
         <div class="flex flex-wrap gap-4 mt-2">
          <div class="bg-gray-800 p-4 rounded-lg">
           <p class="text-gray-100">
            User Terminal Flat High Performance
           </p>
           <p class="text-gray-100">
            IDR 39.388.820
           </p>
          </div>
          <div class="bg-gray-800 p-4 rounded-lg">
           <p class="text-gray-100">
            User Terminal Standard Actuated
           </p>
           <p class="text-gray-100">
            IDR 5.315.315
           </p>
          </div>
         </div>
        </div>
        <div class="mt-4">
         <h2 class="text-xl text-gray-100 font-semibold">
          Pilih Layanan
         </h2>
         <div class="flex flex-wrap gap-4 mt-2">
          <div class="bg-gray-800 p-4 rounded-lg">
           <p class="text-gray-100">
            Data Plan 40GB
           </p>
           <p class="text-gray-100">
            IDR 990.991/bln
           </p>
          </div>
          <div class="bg-gray-800 p-4 rounded-lg">
           <p class="text-gray-100">
            Data Plan 1TB
           </p>
           <p class="text-gray-100">
            IDR 2.725.225/bln
           </p>
          </div>
          <div class="bg-gray-800 p-4 rounded-lg">
           <p class="text-gray-100">
            Data Plan 2TB
           </p>
           <p class="text-gray-100">
            IDR 5.509.910/bln
           </p>
          </div>
          <div class="bg-gray-800 p-4 rounded-lg">
           <p class="text-gray-100">
            Data Plan 6TB
           </p>
           <p class="text-gray-100">
            IDR 11.099.099/bln
           </p>
          </div>
         </div>
         <p class="text-sm text-gray-100 mt-2">
          *periode berlangganan 12 bulan dan akan ditagihkan setiap bulan
         </p>
        </div>
       </div>
      </div>
      <div class="bg-gray-900 p-6 rounded-lg mt-8 lg:mt-0 lg:ml-8 w-full lg:w-1/3">
        <h2 class="text-xl text-gray-100 font-semibold">
         Perangkat
        </h2>
       <div class="mt-4">
        <h3 class="text-lg text-gray-100 font-semibold">
         Kuantitas
        </h3>
        <div class="flex items-center mt-2">
         <button class="bg-gray-700 text-white px-3 py-1 rounded-l">
          -
         </button>
         <input class="bg-gray-800 text-center w-12 text-white py-1" type="text" value="1"/>
         <button class="bg-gray-700 text-white px-3 py-1 rounded-r">
          +
         </button>
        </div>
       </div>
       <button class="bg-gray-700 text-white px-4 py-2 rounded mt-4 w-full flex items-center justify-center">
        Lanjutkan Pembelian
        <i class="fas fa-shopping-cart ml-2">
        </i>
       </button>
      </div>
     </div>
@endsection
