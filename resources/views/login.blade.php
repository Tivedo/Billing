<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <style>
    </style>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<body class="flex items-center justify-center min-h-screen bg-[#E6F0FA]">
    <div class="bg-[#F9FBFD] bg-opacity-50 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-[#1E3A8A] mb-6 text-center">Masuk</h2>
        <form action="{{route('login.submit')}}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-[#1E3A8A]  mb-2" for="username">Username</label>
                <input class="w-full p-3 border border-[#C7D2FE] rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]" type="text" id="username" name="username">
            </div>
            <div class="mb-6 relative">
                <label class="block text-[#1E3A8A] mb-2" for="password">Password</label>
                <div class="flex items-center">
                    <input class="w-full p-3 border border-[#C7D2FE] rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]" type="password" id="password" name="password">
                    <span class="cursor-pointer absolute right-2" id="showHide"></span>
                </div>
            </div>
            <button class="w-full bg-[#3B82F6] hover:bg-[#2563EB] text-white font-semibold py-3 rounded-lg transition">Masuk</button>
        </form>
        <p class="text-[#1E3A8A] mt-4 text-center">Belum punya akun? <a href="register" class="text-[#0EA5E9] hover:underline ml-1">Registrasi</a></p>
    </div>
</body>
<script>
    const showHide = document.getElementById('showHide');

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
    @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
    @endif
</script>
</html>