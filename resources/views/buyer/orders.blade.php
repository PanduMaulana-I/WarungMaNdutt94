@extends('layouts.app')

@section('content')

<!-- Styling Kustom (Disesuaikan dengan Menu Page) -->
<style>
    :root {
        --color-primary: #dc2626;      /* Merah */
        --color-secondary: #facc15;    /* Kuning */
        --color-secondary-dark: #eab308;
        --color-accent: #eab308;
    }

    .text-primary { color: var(--color-primary); }
    .bg-primary { background-color: var(--color-primary); }
    .bg-accent { background-color: var(--color-accent); }
    .hover-bg-primary:hover { background-color: #b91c1c; }
    .fredoka-font { font-family: 'Fredoka One', cursive; }
    .shadow-heavy { 
        box-shadow: 
          0 20px 25px -5px rgba(0, 0, 0, 0.1), 
          0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* BACKGROUND */
    .custom-bg {
        background-color: #E6DCD2;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .custom-bg::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image:
            url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%23BC9F8F" fill-opacity="0.8" d="M0,192L48,181.3C96,171,192,149,288,149.3C384,149,480,171,576,192C672,213,768,235,864,240C960,245,1056,235,1152,213.3C1248,192,1344,160,1392,144L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"%3E%3C/path%3E%3C/svg%3E'),
            url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%23DCCFC4" fill-opacity="1" d="M0,160L48,149.3C96,139,192,117,288,106.7C384,96,480,96,576,117.3C672,139,768,181,864,181.3C960,181,1056,139,1152,128C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: top left, bottom right;
        background-size: 150% auto, 150% auto;
        opacity: .7;
        z-index: 0;
    }

    /* Chef Image */
    .chef-bg {
        position: fixed;
        bottom: -20px; right: -30px;
        width: 250px; height: 250px;
        background-image: url('https://i.imgur.com/e5l1M1N.png');
        background-size: contain;
        background-repeat: no-repeat;
        opacity: .6;
        transform: rotate(5deg);
        z-index: 1;
        pointer-events: none;
    }

    .main-content { position: relative; z-index: 10; }
</style>

<div class="custom-bg">
  <div class="chef-bg hidden md:block"></div>

  <div class="main-content flex flex-col items-center py-10 px-4">

    <div class="bg-white rounded-3xl shadow-heavy p-8 w-full max-w-3xl border-t-4 border-primary">

      <h1 class="text-4xl fredoka-font font-black text-primary text-center mb-1">
        Pantau Pesanan Anda
      </h1>

      <p class="text-center text-gray-700 mb-6 font-semibold">
        Nomor Antrian: 
        <span class="font-black text-primary text-2xl">#{{ $queue_number }}</span>
      </p>

      {{-- LIST PESANAN --}}
      @if ($orders->isEmpty())
        <p class="text-center text-gray-500 p-4 border-dashed border-2 border-gray-300 rounded-xl">
            Belum ada pesanan untuk antrian ini. Yuk, pesan dulu!
        </p>
      @else

        <div class="space-y-6">

        @foreach ($orders as $order)
          <div class="bg-white border-2 border-primary/20 rounded-xl p-5 shadow-sm hover:shadow-lg transition"
               data-order-id="{{ $order->id }}">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
              <h2 class="font-bold text-xl text-gray-900">Pesanan: {{ $order->order_number }}</h2>

              <span class="status-text text-sm px-3 py-1 rounded-full font-black uppercase tracking-wider
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                @elseif($order->status == 'delivering') bg-green-100 text-green-800
                @elseif($order->status == 'completed') bg-primary text-white
                @else bg-gray-200 text-gray-800 @endif">
                {{ ucfirst($order->status) }}
              </span>
            </div>

            {{-- WAKTU --}}
            <p class="text-gray-500 text-sm mb-5">
              Tanggal Pemesanan: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
            </p>

            {{-- PROGRESS BAR --}}
            <div class="mb-6">
              <div class="flex justify-between text-[10px] font-bold text-gray-500 mb-1 px-1">
                <span>MENUNGGU</span>
                <span>DIMASAK</span>
                <span>DIANTAR</span>
                <span>SELESAI</span>
              </div>

              <div class="relative h-3 bg-gray-200 rounded-full overflow-hidden">
                <div class="progress-bar absolute top-0 left-0 h-3 bg-accent rounded-full transition-all duration-700"
                  style="width:
                    @switch($order->status)
                      @case('pending') 10%; @break
                      @case('processing') 40%; @break
                      @case('delivering') 70%; @break
                      @case('completed') 100%; @break
                      @default 10%;
                    @endswitch">
                </div>
              </div>
            </div>

            {{-- DETAIL PESANAN --}}
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-gray-800 font-bold bg-gray-100/50">
                    <th class="text-left py-2 pl-2">Menu</th>
                    <th class="text-center py-2">Qty</th>
                    <th class="text-right py-2 pr-2">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @php $details = json_decode($order->details, true); @endphp
                  @foreach ($details ?? [] as $item)
                    <tr class="border-t border-gray-100">
                      <td class="py-2 text-gray-700">{{ $item['menu'] ?? 'Tidak diketahui' }}</td>
                      <td class="text-center py-2 font-semibold">{{ $item['quantity'] }}</td>
                      <td class="text-right py-2">Rp {{ number_format($item['subtotal'],0,',','.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            {{-- TOTAL --}}
            <div class="text-right mt-4 font-black text-2xl text-primary pt-2 border-t border-gray-200">
              Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </div>

          </div>
        @endforeach

        </div>

      @endif

      {{-- BUTTON KEMBALI --}}
      <div class="text-center mt-10">
        <a href="{{ route('buyer.menu') }}"
           class="bg-primary hover-bg-primary text-white font-black px-6 py-3 rounded-xl shadow-lg transition transform hover:scale-[1.02]">
          Kembali ke Menu
        </a>
      </div>

    </div>
  </div>
</div>

{{-- REALTIME UPDATE 5 DETIK SEKALI --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

  const statusColors = {
    pending:    "bg-yellow-100 text-yellow-800",
    processing: "bg-blue-100 text-blue-800",
    delivering: "bg-green-100 text-green-800",
    completed:  "bg-primary text-white",
    default:    "bg-gray-100 text-gray-800"
  };

  const progressMap = {
    pending: 10,
    processing: 40,
    delivering: 70,
    completed: 100
  };

  async function updateStatuses() {
    const orders = document.querySelectorAll("[data-order-id]");

    for (const order of orders) {
      const orderId = order.dataset.orderId;
      const statusElem = order.querySelector(".status-text");
      const progressBar = order.querySelector(".progress-bar");

      if (!progressBar) continue;

      try {
        const response = await fetch(`/buyer/orders/status/${orderId}`);
        const data = await response.json();

        if (data.status) {
          const s = data.status.toLowerCase();

          let label = s;
          if (s === "pending") label = "Menunggu";
          if (s === "processing") label = "Dimasak";
          if (s === "delivering") label = "Diantar";
          if (s === "completed") label = "Selesai";

          statusElem.textContent = label;
          statusElem.className =
            `status-text text-sm px-3 py-1 rounded-full font-black uppercase tracking-wider ${statusColors[s] || statusColors.default}`;

          progressBar.style.width = (progressMap[s] || 10) + "%";
        }

      } catch (err) {
        console.error("Update status gagal:", err);
      }
    }
  }

  setInterval(updateStatuses, 5000);
});
</script>

@endsection
