<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Orderan — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-50 min-h-screen">

  <div class="max-w-6xl mx-auto py-8 px-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-bold text-slate-800">🧾 Daftar Orderan</h1>
      <a href="{{ route('seller.dashboard') }}" 
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

    {{-- Wrapper tabel --}}
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
        <tbody class="divide-y divide-slate-100">
          @forelse($orders as $order)
          <tr class="hover:bg-indigo-50 transition-all">
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
              <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>

            {{-- Tanggal --}}
            <td class="px-5 py-3 text-slate-600">
              {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y • H:i') }}
            </td>

            {{-- Aksi --}}
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

                {{-- Tombol tidak boleh submit --}}
                <button 
                  type="button"
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
  </div>

  {{-- ✅ SCRIPT --}}
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ Script aktif - binding tombol Ubah");

    const buttons = document.querySelectorAll(".update-status");
    buttons.forEach(btn => {
      btn.addEventListener("click", async function (e) {
        e.preventDefault();
        e.stopPropagation(); // 🧩 penting! cegah reload & bubbling

        const orderId = this.dataset.id;
        const status = document.getElementById(`status-${orderId}`).value;
        const token = "{{ csrf_token() }}";

        console.log(`➡️ Update order #${orderId} ke status ${status}`);

        try {
          const response = await fetch(`/seller/orders/${orderId}/status`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify({ status }),
          });

          const data = await response.json();
          console.log("🔁 Response:", data);

          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "Berhasil!",
              text: "Status pesanan berhasil diperbarui!",
              confirmButtonColor: "#4F46E5",
            });
          } else {
            Swal.fire("Gagal", "Tidak bisa memperbarui status", "error");
          }
        } catch (err) {
          console.error("❌ Fetch error:", err);
          Swal.fire("Error", "Terjadi kesalahan jaringan.", "error");
        }
      });
    });
  });
  </script>

</body>
</html>
