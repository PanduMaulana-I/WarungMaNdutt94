<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar Orderan — WebTransaksi</title>

  {{-- ✅ CSS & SweetAlert --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-50 text-slate-800 flex">

  {{-- ✅ SIDEBAR --}}
  <aside class="w-64 bg-white h-screen shadow-md fixed top-0 left-0 flex flex-col">
    <div class="p-5 border-b border-slate-200">
      <h1 class="text-xl font-bold text-indigo-600">🍜 WebTransaksi</h1>
      <p class="text-sm text-slate-500">Panel Penjual</p>
    </div>

    <nav class="flex-1 p-4 space-y-2 text-slate-700">
      <a href="{{ route('seller.dashboard') }}"
         class="block px-3 py-2 rounded hover:bg-indigo-50 {{ request()->routeIs('seller.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : '' }}">
         🏠 Dashboard
      </a>

      <a href="{{ route('seller.menus.index') }}"
         class="block px-3 py-2 rounded hover:bg-indigo-50 {{ request()->is('seller/menus*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : '' }}">
         📋 Kelola Menu
      </a>

      <a href="{{ route('seller.orders.index') }}"
         class="block px-3 py-2 rounded hover:bg-indigo-50 {{ request()->is('seller/orders*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : '' }}">
         🧾 Orderan
      </a>

      <a href="{{ route('seller.reports.index') }}"
         class="block px-3 py-2 rounded hover:bg-indigo-50 {{ request()->is('seller/reports*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : '' }}">
         📊 Laporan Penjualan
      </a>
    </nav>

    <form method="POST" action="{{ route('seller.logout') }}" class="p-4 border-t border-slate-200">
      @csrf
      <button type="submit"
        class="w-full px-3 py-2 bg-rose-500 text-white rounded hover:bg-rose-600 transition font-medium">
        Keluar
      </button>
    </form>
  </aside>

  {{-- ✅ KONTEN UTAMA --}}
  <main class="ml-64 flex-1 p-8">
    <h1 class="text-2xl font-bold mb-6">🧾 Daftar Orderan</h1>

    {{-- ✅ Notifikasi sukses --}}
    @if(session('success'))
      <div class="mb-5 bg-green-100 text-green-700 p-3 rounded-lg shadow-sm border border-green-200">
        ✅ {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-sm text-left" id="order-table">
        <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
          <tr>
            <th class="px-5 py-3">No. Order</th>
            <th class="px-5 py-3">Pembeli</th>
            <th class="px-5 py-3">Total</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Tanggal</th>
            <th class="px-5 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="order-body" class="divide-y divide-slate-100">
          @forelse($orders as $order)
          <tr class="hover:bg-indigo-50 transition-all" id="order-row-{{ $order->id }}">
            <td class="px-5 py-3 font-mono text-slate-700">#{{ $order->order_number }}</td>
            <td class="px-5 py-3 text-slate-800 font-medium">{{ $order->customer_name }}</td>
            <td class="px-5 py-3 text-slate-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>

            {{-- Badge Status --}}
            <td class="px-5 py-3">
              @php
                $color = match($order->status) {
                  'pending' => 'bg-yellow-100 text-yellow-700',
                  'processing' => 'bg-blue-100 text-blue-700',
                  'delivering' => 'bg-purple-100 text-purple-700',
                  'completed' => 'bg-green-100 text-green-700',
                  'cancelled' => 'bg-rose-100 text-rose-700',
                  default => 'bg-slate-100 text-slate-700',
                };
              @endphp
              <span id="status-badge-{{ $order->id }}" class="transition-all duration-500 px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>

            <td class="px-5 py-3 text-slate-600">
              {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y • H:i') }}
            </td>

            {{-- Aksi Update Status --}}
            <td class="px-5 py-3 text-center">
              <div class="inline-flex items-center gap-2 justify-center">
                <select id="status-{{ $order->id }}" 
                        class="border border-slate-300 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-200 focus:outline-none">
                  <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                  <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                  <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Diantar</option>
                  <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                  <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <button 
                  data-id="{{ $order->id }}"
                  class="update-status px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700 transition-all">
                  Ubah
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-5 py-6 text-center text-slate-500">
              Belum ada orderan masuk 😴
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </main>

  {{-- ✅ SCRIPT UTAMA --}}
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    console.log("🔥 Script interaktif aktif: seller/orders.blade.php");

    // 🧠 Realtime listener dari Reverb
    if (window.Echo) {
      console.log("📡 Listening channel: orders ...");
      window.Echo.channel('orders')
        .listen('.OrderCreated', (e) => {
          console.log("🆕 Pesanan baru diterima:", e);

          Swal.fire({
            title: "Pesanan Baru Masuk!",
            text: `Nomor Order: ${e.order.order_number} | Total: Rp ${Number(e.order.total_price).toLocaleString('id-ID')}`,
            icon: "info",
            confirmButtonText: "Lihat Sekarang",
            confirmButtonColor: "#4F46E5"
          });

          // 🧩 Sisipkan row baru di tabel tanpa reload
          const tbody = document.getElementById('order-body');
          const newRow = document.createElement('tr');
          newRow.className = "hover:bg-indigo-50 transition-all";
          newRow.innerHTML = `
            <td class="px-5 py-3 font-mono text-slate-700">#${e.order.order_number}</td>
            <td class="px-5 py-3 text-slate-800 font-medium">${e.order.customer_name}</td>
            <td class="px-5 py-3 text-slate-700">Rp ${Number(e.order.total_price).toLocaleString('id-ID')}</td>
            <td class="px-5 py-3"><span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Menunggu</span></td>
            <td class="px-5 py-3 text-slate-600">${new Date(e.order.created_at).toLocaleString('id-ID')}</td>
            <td class="px-5 py-3 text-center text-slate-400 italic">Pesanan baru 🚀</td>
          `;
          tbody.prepend(newRow);
        });
    } else {
      console.warn("❌ Laravel Echo belum aktif.");
    }

    // 🔄 Update status manual
    document.querySelectorAll('.update-status').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const orderId = btn.dataset.id;
        const status = document.getElementById(`status-${orderId}`).value;
        const token = "{{ csrf_token() }}";

        try {
          const response = await fetch(`/seller/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ status })
          });

          const data = await response.json();
          if (data.success) {
            const badge = document.querySelector(`#status-badge-${orderId}`);
            if (badge) {
              const map = {
                'pending': ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                'processing': ['bg-blue-100 text-blue-700', 'Diproses'],
                'delivering': ['bg-purple-100 text-purple-700', 'Diantar'],
                'completed': ['bg-green-100 text-green-700', 'Selesai'],
                'cancelled': ['bg-rose-100 text-rose-700', 'Dibatalkan']
              };
              const [cls, text] = map[status] || ['bg-slate-100 text-slate-700', status];
              badge.className = `transition-all duration-500 px-3 py-1 text-xs font-semibold rounded-full ${cls}`;
              badge.textContent = text;
            }

            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Status pesanan diperbarui!',
              timer: 1300,
              showConfirmButton: false
            });
          } else {
            Swal.fire('Gagal', 'Tidak bisa memperbarui status', 'error');
          }
        } catch (error) {
          Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
        }
      });
    });
  });
  </script>

</body>
</html>
