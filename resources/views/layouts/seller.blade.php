<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Dashboard Penjual') — Warung Mas Ndutt94</title>

  {{-- Wajib untuk menjalankan Echo & Reverb --}}
  @vite(['resources/js/app.js'])

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root {
      --primary: #dc2626;
      --secondary: #facc15;
      --bg-soft: #F3EEE7;
      --sidebar-bg: rgba(255,255,255,0.92);
    }
    body { background: var(--bg-soft); font-family: "Inter", sans-serif; }

    .wave-bg::before{
      content:""; position:absolute; top:0; left:0;
      width:100%; height:260px; background-size:cover;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ddbfa8' fill-opacity='0.45' d='M0,160L80,176C160,192,320,224,480,218.7C640,213,800,171,960,160C1120,149,1280,171,1360,176L1440,181V0H0Z'%3E%3C/path%3E%3C/svg%3E");
      z-index:-1; opacity:.85;
    }

    .sidebar { backdrop-filter: blur(18px); background:var(--sidebar-bg); }
    .nav-active { background: rgba(220,38,38,0.15); border-left:4px solid var(--primary); color:var(--primary) !important; }

    /* ================== CUSTOM TOAST NOTIF ================== */
    .toast-notif {
      position: fixed;
      top: 20px;
      right: 20px;
      min-width: 260px;
      max-width: 330px;
      background: rgba(255, 255, 255, 0.92);
      border-left: 6px solid var(--primary);
      padding: 14px 18px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      opacity: 0;
      transform: translateX(60px);
      transition: all .35s ease;
      display: none;
      backdrop-filter: blur(12px);
      z-index: 9999;
    }
    .toast-notif.show {
      opacity: 1;
      transform: translateX(0);
      display: block;
    }
    .toast-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 4px;
    }
    .toast-msg {
      font-size: 13px;
      color: #333;
    }
  </style>
</head>

<body class="flex min-h-screen wave-bg relative">

<!-- ☰ MOBILE TOGGLE BUTTON -->
<button id="toggleSidebar"
        class="md:hidden fixed top-4 left-4 z-30 bg-white p-2 rounded-lg shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- ================= SIDEBAR ================= -->
<aside id="sidebar"
       class="sidebar w-64 h-screen shadow-xl flex flex-col fixed left-0 top-0 z-20
              border-r border-slate-200 transform transition-transform duration-300
              -translate-x-full md:translate-x-0">

    <div class="p-6 border-b border-slate-200">
      <h1 class="text-2xl font-extrabold text-[var(--primary)] tracking-tight">
        Warung Mas Ndutt94
      </h1>
      <p class="text-[11px] text-slate-500">Panel Penjual</p>
    </div>

    <nav class="flex-1 px-4 py-5 space-y-1">

      <a href="{{ route('seller.dashboard') }}"
         class="block px-4 py-3 rounded-md {{ request()->routeIs('seller.dashboard') ? 'nav-active' : '' }}">
        Dashboard
      </a>

      <a href="{{ route('seller.menus.index') }}"
         class="block px-4 py-3 rounded-md {{ request()->routeIs('seller.menus.*') ? 'nav-active' : '' }}">
        Kelola Menu
      </a>

      <a href="{{ route('seller.orders.index') }}"
         class="block px-4 py-3 rounded-md flex items-center justify-between {{ request()->routeIs('seller.orders.*') ? 'nav-active' : '' }}">
        <span>Orderan</span>

        {{-- 🔥 BADGE ORDER BARU --}}
        <span id="order-badge"
              class="hidden text-xs bg-red-600 text-white px-2 py-0.5 rounded-full">
          Baru
        </span>
      </a>

      <a href="{{ route('seller.reports.index') }}"
         class="block px-4 py-3 rounded-md {{ request()->routeIs('seller.reports.*') ? 'nav-active' : '' }}">
        Laporan Penjualan
      </a>
    </nav>

    <form method="POST" action="{{ route('seller.logout') }}" class="p-4 border-t border-slate-200">
      @csrf
      <button class="w-full py-2.5 rounded-lg bg-red-100 text-red-700 font-medium hover:bg-red-200 transition shadow">
        Keluar
      </button>
    </form>
</aside>

<!-- ================= MAIN CONTENT ================= -->
<main class="flex-1 p-10 ml-0 md:ml-64 transition-all duration-300">

    {{-- ====================== POPUP ======================= --}}
    @if(session('login_success'))
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Selamat Datang!',
          text: "{{ session('login_success') }}",
          timer: 2200,
          showConfirmButton: false
        });
      </script>
    @endif

    @if(session('menu_added'))
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Menu Ditambahkan!',
          text: "{{ session('menu_added') }}",
          timer: 2200,
          showConfirmButton: false
        });
      </script>
    @endif

    @if(session('menu_updated'))
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Menu Diperbarui!',
          text: "{{ session('menu_updated') }}",
          timer: 2200,
          showConfirmButton: false
        });
      </script>
    @endif

    @if(session('order_updated'))
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Status Pesanan!',
          text: "{{ session('order_updated') }}",
          timer: 2000,
          showConfirmButton: false
        });
      </script>
    @endif

    @if(session('success'))
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: "{{ session('success') }}",
          timer: 2000,
          showConfirmButton: false
        });
      </script>
    @endif

    {{-- ===================================================== --}}

    @yield('content')
</main>

<!-- 🔥 MOBILE SIDEBAR -->
<script>
document.getElementById("toggleSidebar").addEventListener("click", function () {
    document.getElementById("sidebar").classList.toggle("-translate-x-full");
});
</script>

<!-- ================= TOAST NOTIFICATION ================= -->
<div id="notifToast" class="toast-notif">
  <div class="toast-title">Order Baru</div>
  <div class="toast-msg" id="notifMessage">Pesanan masuk.</div>
</div>

<script type="module">
function showToast(msg) {
    const toast = document.getElementById("notifToast");
    const text = document.getElementById("notifMessage");

    text.textContent = msg;

    toast.style.display = "block";

    setTimeout(() => toast.classList.add("show"), 20);

    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.style.display = "none", 300);
    }, 3500);
}

window.Echo.channel("order-channel")
    .listen(".order-created", (data) => {

        showToast("Pesanan dari: " + data.order.customer_name);

        new Audio("/notifications/new_order.mp3").play();

        let badge = document.getElementById("order-badge");
        if (badge) badge.classList.remove("hidden");
    });
</script>

</body>
</html>
