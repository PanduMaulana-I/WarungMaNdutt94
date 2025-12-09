<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Orderan — WebTransaksi</title>

  {{-- Tailwind + Sweetalert --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- ❌ JANGAN PAKAI VITE (INI YANG BIKIN ERROR) --}}
  {{-- @vite(['resources/js/app.js']) --}}
</head>

<body class="bg-slate-50 min-h-screen">

  <div class="max-w-6xl mx-auto py-8 px-6">

    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-bold text-slate-800">🧾 Daftar Orderan</h1>
      <a href="/seller/dashboard" 
         class="px-4 py-2 bg-slate-200 text-slate-700 rounded-md hover:bg-slate-300 transition-all">
        ← Dashboard
      </a>
    </div>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
      <div class="mb-5 bg-green-100 text-green-700 p-3 rounded-lg shadow-sm border border-green-200">
        ✅ {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-sm text-left">
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

        <tbody id="orders-table" class="divide-y divide-slate-100">

          @foreach($orders as $order)

          @php
            $color = match($order->status) {
              'pending' => 'bg-yellow-100 text-yellow-700',
              'processing' => 'bg-blue-100 text-blue-700',
              'delivering' => 'bg-purple-100 text-purple-700',
              'completed' => 'bg-green-100 text-green-700',
              'cancelled' => 'bg-rose-100 text-rose-700',
              default => 'bg-slate-100 text-slate-700'
            };
          @endphp

          <tr id="order-{{ $order->id }}" class="hover:bg-indigo-50 transition-all">

            <td class="px-5 py-3 font-mono text-slate-700">
              #{{ $order->order_number }}
            </td>

            <td class="px-5 py-3 text-slate-800 font-medium">
              {{ $order->customer_name }}
            </td>

            <td class="px-5 py-3 text-slate-700">
              Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </td>

            <td class="px-5 py-3">
              <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>

            <td class="px-5 py-3 text-slate-600">
              {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y • H:i') }}
            </td>

            <td class="px-5 py-3 text-center">
              <div class="inline-flex gap-2 items-center">

                <select id="status-{{ $order->id }}" 
                        class="border border-slate-300 rounded-md px-2 py-1 text-sm">

                  <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                  <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                  <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Diantar</option>
                  <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                  <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>

                </select>

                <button data-id="{{ $order->id }}"
                  class="update-status px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700">
                  Ubah
                </button>

              </div>
            </td>

          </tr>

          @endforeach

        </tbody>
      </table>
    </div>

  </div>

  {{-- =============================== --}}
  {{--    AUTO REFRESH + NOTIF BARU     --}}
  {{-- =============================== --}}

  <script>
    let lastCount = {{ count($orders) }};

    function refreshOrders() {
      fetch("/api/orders/latest")
        .then(res => res.json())
        .then(data => {

          // notif ada order baru
          if (data.length > lastCount) {
            Swal.fire({
              icon: "info",
              title: "Pesanan Baru Masuk!",
              timer: 2000,
              showConfirmButton: false
            });

            new Audio('/notif.mp3').play();
            lastCount = data.length;
          }

          // update tabel, tapi UI aman
          let tbody = document.getElementById("orders-table");
          let html = "";

          data.forEach(order => {

            const colors = {
              pending: 'bg-yellow-100 text-yellow-700',
              processing: 'bg-blue-100 text-blue-700',
              delivering: 'bg-purple-100 text-purple-700',
              completed: 'bg-green-100 text-green-700',
              cancelled: 'bg-rose-100 text-rose-700',
            };

            html += `
              <tr class="hover:bg-indigo-50 transition-all">

                <td class="px-5 py-3 font-mono text-slate-700">#${order.order_number}</td>

                <td class="px-5 py-3 text-slate-800 font-medium">${order.customer_name}</td>

                <td class="px-5 py-3 text-slate-700">
                  Rp ${Number(order.total_price).toLocaleString('id-ID')}
                </td>

                <td class="px-5 py-3">
                  <span class="px-3 py-1 text-xs font-semibold rounded-full ${colors[order.status]}">
                    ${order.status}
                  </span>
                </td>

                <td class="px-5 py-3 text-slate-600">
                  ${new Date(order.created_at).toLocaleString("id-ID")}
                </td>

                <td class="px-5 py-3 text-center">
                  <div class="inline-flex items-center gap-2">
                    <select id="status-${order.id}" class="border border-slate-300 rounded-md px-2 py-1 text-sm">

                      <option value="pending" ${order.status == 'pending' ? 'selected' : ''}>Menunggu</option>
                      <option value="processing" ${order.status == 'processing' ? 'selected' : ''}>Diproses</option>
                      <option value="delivering" ${order.status == 'delivering' ? 'selected' : ''}>Diantar</option>
                      <option value="completed" ${order.status == 'completed' ? 'selected' : ''}>Selesai</option>
                      <option value="cancelled" ${order.status == 'cancelled' ? 'selected' : ''}>Dibatalkan</option>

                    </select>

                    <button data-id="${order.id}"
                      class="update-status px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs">
                      Ubah
                    </button>
                  </div>
                </td>

              </tr>
            `;
          });

          tbody.innerHTML = html;
        });
    }

    // auto refresh tiap 3 detik
    setInterval(refreshOrders, 3000);


    // ========= UPDATE STATUS ==========
    document.addEventListener("click", async e => {
      if (e.target.classList.contains("update-status")) {

        const id = e.target.dataset.id;
        const status = document.getElementById(`status-${id}`).value;
        const token = "{{ csrf_token() }}";

        const res = await fetch(`/seller/orders/${id}/status`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
          },
          body: JSON.stringify({ status })
        });

        const response = await res.json();

        if (response.success) {
          Swal.fire({
            icon: "success",
            title: "Status diperbarui!",
            timer: 1500,
            showConfirmButton: false
          });
        }
      }
    });

  </script>

</body>
</html>
