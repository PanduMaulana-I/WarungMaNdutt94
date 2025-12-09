<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $menu->name }} — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
  <div class="max-w-3xl mx-auto p-6">
    <div class="flex justify-between items-center">
      <a href="{{ route('seller.menus.index') }}" class="text-sm text-slate-600 hover:text-slate-900">← Kembali</a>
      <a href="{{ route('seller.dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900">← Dashboard</a>
    </div>

    <div class="bg-white mt-4 p-6 rounded-xl shadow-sm">
      <div class="flex gap-6">
        <div class="w-40 h-40 bg-slate-100 rounded-md flex items-center justify-center overflow-hidden">
          @if($menu->image)
            <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover">
          @else
            <div class="text-slate-400">No Image</div>
          @endif
        </div>

        <div class="flex-1">
          <h1 class="text-2xl font-semibold">{{ $menu->name }}</h1>
          <p class="text-slate-600 mt-2">{{ $menu->description ?? '-' }}</p>
          <div class="mt-4 text-indigo-700 font-bold text-xl">Rp {{ number_format($menu->price,0,',','.') }}</div>
          <div class="mt-2 text-slate-500 text-sm">Stok: {{ $menu->stock }}</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
