<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Token Tidak Valid</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-50 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md text-center">
    <h1 class="text-2xl font-bold text-red-600 mb-2">⚠️ Token Tidak Valid</h1>
    <p class="text-gray-600 mb-4">Kode QR sudah kadaluarsa atau tidak dikenali.</p>
    <a href="{{ route('buyer.qr') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
      🔁 Coba Lagi
    </a>
  </div>
</body>
</html>
