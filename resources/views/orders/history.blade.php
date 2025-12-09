<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Riwayat Pesanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<div class="max-w-4xl mx-auto p-6">
  <a href="{{ route('menus.index') }}" class="text-sm text-indigo-600">← Kembali</a>
  <h1 class="text-2xl font-bold mt-4 mb-4">Riwayat Pesanan</h1>

  <form method="get" action="{{ route('orders.history') }}" class="flex gap-2 mb-6">
    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari berdasarkan no HP atau nomor pesanan" class="flex-1 px-3 py-2 border rounded-md">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Cari</button>
  </form>

  @if(isset($orders) && $orders->count())
    @foreach($orders as $o)
      <div class="bg-white rounded-lg p-4 shadow mb-3">
        <div class="flex justify-between">
          <div>
            <div class="font-semibold text-lg">{{ $o->order_number }}</div>
            <div class="text-sm text-slate-500">{{ $o->customer_name }} ({{ $o->customer_phone }})</div>
          </div>
          <div class="text-right font-semibold text-indigo-700">Rp {{ number_format($o->total,0,',','.') }}</div>
        </div>
        <ul class="mt-2 text-sm text-slate-600 list-disc list-inside">
          @foreach($o->items as $it)
            <li>{{ $it->quantity }} × {{ optional($it->menu)->name }} — Rp {{ number_format($it->price,0,',','.') }}</li>
          @endforeach
        </ul>
      </div>
    @endforeach
  @elseif(isset($q))
    <div class="bg-white p-4 text-center text-slate-500 rounded">Tidak ada pesanan ditemukan.</div>
  @endif
</div>
</body>
</html>
