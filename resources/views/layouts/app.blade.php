
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title') — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <button onclick="history.back()" class="text-slate-600 hover:text-slate-900">← Kembali</button>
    <h1 class="font-semibold text-lg">@yield('title')</h1>
    <div></div>
  </header>
  <main class="p-4">
    @yield('content')
  </main>
</body>
</html>

