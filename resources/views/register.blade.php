@extends('layout.main')
@section('title', 'Daftar Akun')
<style>
    .error-message {
        color: red;
        font-size: 0.875rem;
        display: none;
    }
</style>
@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-gray-700 bg-opacity-50 p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-semibold text-center text-white mb-6">Daftar Akun</h1>
        <form id="registrationForm" action="{{route('register.submit')}}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                <input type="text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500" name="nama">
            </div>
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Nomor NPWP Perusahaan <span class="text-red-500">*</span></label>
                <input id="npwp" type="text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500" name="npwp">
                <p class="error-message" id="npwpError">NPWP harus 16 digit</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Nomor Telepon Perusahaan <span class="text-red-500">*</span></label>
                <input id="telepon" type="text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500" name="telepon">
            </div>
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Username <span class="text-red-500">*</span></label>
                <input type="text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500" name="username">
            </div>
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500" name="email">
            </div>
            <div class="mb-4 relative">
                <label class="block text-gray-300 mb-1">Password <span class="text-red-500">*</span></label>
                <div class="flex items-center">
                    <input class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500" type="password" id="password" name="password">
                    <span class="cursor-pointer absolute right-2" id="showHide"></span>
                </div>
            </div>
            <button id="submitButton" type="submit" class="w-full p-2 rounded bg-gray-700 text-white hover:bg-gray-600 focus:outline-none">Submit</button>
        </form>
    </div>
</div>
<script>
    const showHide = document.getElementById('showHide');
    const form = document.getElementById('registrationForm');
    const inputs = form.querySelectorAll('input');
    const submitButton = document.getElementById('submitButton');
    const npwpInput = document.getElementById('npwp');
    const npwpError = document.getElementById('npwpError'); 
    const teleponInput = document.getElementById('telepon');

    function validateForm() {
        let allFilled = true;
        let isNpwpValid = npwpInput.value.length === 16;
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                allFilled = false;
            }
        });
        submitButton.disabled = !allFilled;
        npwpError.style.display = isNpwpValid ? 'none' : 'block';
        submitButton.style.backgroundColor = allFilled ? '#4CAF50' : '#4B5563'; // green when enabled, gray when disabled
    }

    inputs.forEach(input => {
        input.addEventListener('input', validateForm);
    });
    [npwpInput, teleponInput].forEach(input => {
        input.addEventListener('keypress', function (e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    });

    password.type = 'password';
    showHide.innerHTML = '<i class="fas fa-eye text-gray-400"></i>';
    showHide.style.cursor = 'pointer';

    showHide.addEventListener('click', () => {
        if (password.type === 'password') {
            password.type = 'text'; // ubah type menjadi text
            showHide.innerHTML =
                '<i class="fas fa-eye-slash text-gray-400"></i>'; // ubah icon menjadi eye slash
        } else {
            showHide.innerHTML = '<i class="fas fa-eye text-gray-400"></i>'; // ubah icon menjadi eye
            password.type = 'password'; // ubah type menjadi password
        }
    });
</script>
@endsection
