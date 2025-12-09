<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Dashboard Penjual') — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 flex min-h-screen">

  {{-- 🧭 SIDEBAR --}}
  <aside class="w-64 bg-white h-screen shadow-md flex flex-col fixed left-0 top-0 border-r border-slate-200">
    <div class="p-5 border-b border-slate-200">
      <h1 class="text-xl font-bold text-indigo-600">WebTransaksi</h1>
      <p class="text-xs text-slate-500 mt-1">Dashboard Penjual</p>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto">
      <a href="{{ route('seller.dashboard') }}"
         class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200 hover:bg-indigo-50 mt-1 
         {{ request()->routeIs('seller.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-700' }}">
         🏠 <span>Dashboard</span>
      </a>

      <a href="{{ route('seller.menus.index') }}"
         class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200 hover:bg-indigo-50 mt-1 
         {{ request()->routeIs('seller.menus.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-700' }}">
         🍜 <span>Kelola Menu</span>
      </a>

      <a href="{{ route('seller.orders.index') }}" 
         class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200 hover:bg-indigo-50 mt-1 
         {{ request()->routeIs('seller.orders.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-700' }}">
         🧾 <span>Orderan</span>
      </a>

      <a href="{{ route('riwayat.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('riwayat.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-700' }}">
         📜 <span>Riwayat Transaksi</span>
      </a>

      <a href="{{ route('seller.reports.sales') }}"
         class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200 hover:bg-indigo-50 mt-1 
         {{ request()->routeIs('seller.reports*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-700' }}">
         📊 <span>Laporan</span>
      </a>
    </nav>

    {{-- Logout --}}
    <form method="POST" action="{{ route('seller.logout') }}" class="p-4 border-t border-slate-200">
      @csrf
      <button type="submit"
        class="w-full bg-rose-100 text-rose-700 py-2 rounded-md hover:bg-rose-200 transition font-medium flex items-center justify-center gap-1">
        🚪 Logout
      </button>
    </form>
  </aside>

  {{-- 🧾 MAIN CONTENT --}}
  <main class="ml-64 flex-1 p-8">
    @yield('content')
  </main>

  {{-- 🧠 INJEK SCRIPT --}}
  @yield('scripts')
</body>
</html>
