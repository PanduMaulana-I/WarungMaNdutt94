<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login Pembeli — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
    <h1 class="text-2xl font-bold mb-2">📱 Scan QR untuk Masuk</h1>
    <p class="text-slate-600 mb-6">Arahkan kamera HP Anda ke QR di bawah untuk login otomatis.</p>

    <img src="{{ $qrUrl }}" alt="QR Login" class="mx-auto w-56 h-56 border p-2 rounded-lg shadow-md mb-3">

    <div class="text-slate-500 text-sm mb-6">
      Token: <code class="font-mono text-emerald-600">{{ $bt->token }}</code>
    </div>

    <a href="{{ route('login.choice') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
      ← Kembali ke Pilihan Role
    </a>

    <footer class="text-xs text-slate-400 mt-8">
      © {{ date('Y') }} WebTransaksi — dibuat untuk tugas.
    </footer>
  </div>
</body>
</html>
