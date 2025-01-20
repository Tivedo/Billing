<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: black
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-gray-700 bg-opacity-50 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Masuk</h2>
        <form action="{{route('login.submit')}}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 mb-2" for="username">Username</label>
                <input class="w-full p-3 rounded bg-gray-800 text-white" type="text" id="username" name="username">
            </div>
            <div class="mb-6 relative">
                <label class="block text-gray-400 mb-2" for="password">Password</label>
                <div class="flex items-center">
                    <input class="w-full p-3 rounded bg-gray-800 text-white" type="password" id="password" name="password">
                    <span class="cursor-pointer absolute right-2" id="showHide"></span>
                </div>
            </div>
            <button class="w-full bg-purple-600 text-white p-3 rounded-lg font-semibold hover:bg-purple-700 transition duration-300">Masuk</button>
        </form>
        <p class="text-gray-400 mt-4 text-center">Belum punya akun? <a href="register" class="text-blue-400">Registrasi</a></p>
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
</script>
</html>