<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pilih Role — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-xl shadow-lg text-center space-y-6">
    <h1 class="text-2xl font-bold">Masuk Sebagai</h1>
    <div class="flex gap-4 justify-center">
      <a href="{{ route('seller.login') }}" 
         class="bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700">👨‍🍳 Penjual</a>
      <a href="{{ route('buyer.qr') }}" 
         class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">🧍 Pembeli (Scan QR)</a>
    </div>
  </div>
</body>
</html>
