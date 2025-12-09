<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Menu Pembeli') — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-slate-800 min-h-screen flex flex-col">

  {{-- 🔝 Header --}}
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
      <h1 class="text-xl font-bold text-indigo-600">🍜 WebTransaksi</h1>
      <a href="{{ route('buyer.menu') }}"> 
         class="px-4 py-2 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 transition">
        ← Dashboard
      </a>
    </div>
  </header>

  {{-- 🧾 Konten Utama --}}
  <main class="flex-1 max-w-6xl mx-auto p-6">
    @yield('content')
  </main>

  {{-- ⚓ Footer --}}
  <footer class="text-center text-sm text-slate-500 py-6">
    © {{ date('Y') }} WebTransaksi — Sistem Pemesanan Warung Online
  </footer>

</body>
</html>
