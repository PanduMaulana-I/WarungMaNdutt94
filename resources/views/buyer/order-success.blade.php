<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pesanan Berhasil — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

  <div class="bg-white shadow-md rounded-xl p-8 text-center max-w-md">
    <div class="text-4xl mb-3">✅</div>
    <h1 class="text-2xl font-bold text-green-600">Pesanan Berhasil!</h1>
    <p class="text-gray-600 mt-2">
      Pesanan kamu sudah masuk dan sedang diproses oleh penjual.
    </p>

    <a href="{{ route('menus.index') }}" 
       class="inline-block mt-5 bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
      Kembali ke Menu
    </a>
  </div>

</body>
</html>
