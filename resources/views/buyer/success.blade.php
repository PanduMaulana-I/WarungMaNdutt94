<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Status Pesanan — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">

  <style>
    /* Tema Warna */
    :root {
        --color-primary: #dc2626;
        --color-secondary: #facc15;
        --color-accent: #eab308;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #E6DCD2;
        min-height: 100vh;
    }

    .text-primary { color: var(--color-primary); }
    .bg-primary { background-color: var(--color-primary); }
    .bg-accent { background-color: var(--color-accent); }
    .hover-bg-primary:hover { background-color: #b91c1c; }

    .shadow-heavy {
        box-shadow: 0 15px 25px -5px rgba(0,0,0,0.12), 
                    0 8px 10px -5px rgba(0,0,0,0.04);
    }

    /* Membuat card lebih center secara vertikal */
    .page-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
  </style>
</head>

<body>

<div class="page-wrapper">
  <div class="bg-white shadow-heavy rounded-3xl p-8 w-full max-w-md text-center border-t-8 border-primary relative z-10">

    <!-- Judul -->
    <div class="text-primary text-5xl mb-3">✔️</div>
    <h1 class="text-3xl font-black text-primary mb-2" style="font-family:'Fredoka One', cursive;">
      Pesanan Berhasil!
    </h1>
    <p class="text-slate-600 mb-6 text-sm font-semibold">
      Pesanan kamu sedang diproses oleh penjual.
    </p>

    <!-- Informasi Pesanan -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-left mb-6 font-medium">
      <p><b>No. Antrian:</b> {{ $queueNumber ?? '-' }}</p>
      <p><b>No. Pesanan:</b> {{ $order->order_number }}</p>
      <p><b>Status:</b> 
        <span id="order-status" class="font-bold text-accent">
          {{ ucfirst($order->status) }}
        </span>
      </p>
    </div>

    <!-- Progress Bar -->
    <div class="relative pt-2 mb-6">
      <div class="flex justify-between text-xs font-black text-slate-700 mb-2">
        <span>Menunggu</span>
        <span>Dimasak</span>
        <span>Diantar</span>
        <span>Selesai</span>
      </div>
      <div class="w-full bg-slate-200 rounded-full h-2.5">
        <div id="progress-bar" 
             class="bg-accent h-2.5 rounded-full transition-all duration-700 ease-out"
             style="width:10%">
        </div>
      </div>
    </div>

    <!-- Rincian Pesanan -->
    <h2 class="font-bold text-xl text-slate-800 mb-3 border-t pt-4">Rincian Pesanan:</h2>

    <table class="w-full text-sm mb-4 border-collapse">
      <thead>
        <tr class="text-white bg-primary">
          <th class="py-2 text-left px-3 rounded-tl-lg">Menu</th>
          <th class="py-2 text-center">Qty</th>
          <th class="py-2 text-right px-3 rounded-tr-lg">Subtotal</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach(($details ?? []) as $item)
        <tr class="bg-white hover:bg-red-50/50 transition-all">
          <td class="py-2 text-left px-3">{{ $item['menu'] }}</td>
          <td class="py-2 text-center font-semibold">{{ $item['quantity'] }}</td>
          <td class="py-2 text-right px-3">Rp {{ number_format($item['subtotal'],0,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-right font-black text-slate-800 text-xl mb-4 p-2 border-t-2 border-gray-200">
      Total: <span class="text-primary">Rp {{ number_format($order->total_price,0,',','.') }}</span>
    </div>

    <!-- Tombol -->
    <a href="{{ route('buyer.menu') }}"
      class="inline-block bg-primary text-white font-black px-6 py-3 rounded-xl hover-bg-primary transition transform hover:scale-[1.02] shadow-md">
      Kembali ke Menu
    </a>

    <p class="text-slate-400 text-xs mt-6">
      Terima kasih telah memesan di <span class="text-primary font-semibold">Warung Mas Ndutt94</span>!
    </p>
  </div>
</div>

<!-- Realtime Polling -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const orderId = "{{ $order->id }}";
    const orderStatus = document.getElementById("order-status");
    const progressBar = document.getElementById("progress-bar");

    const statusProgress = { pending:10, processing:40, delivering:70, completed:100 };
    const statusColor = {
        pending:'text-yellow-600',
        processing:'text-blue-600',
        delivering:'text-purple-600',
        completed:'text-green-600',
        default:'text-slate-600'
    };

    async function updateStatus() {
        try {
            const res = await fetch(`/buyer/orders/status/${orderId}`);
            const data = await res.json();
            if (!data.status) return;

            const s = data.status.toLowerCase();
            const formatted = s.charAt(0).toUpperCase() + s.slice(1);

            orderStatus.textContent = formatted;
            orderStatus.className = `font-bold ${statusColor[s]||statusColor.default}`;
            progressBar.style.width = (statusProgress[s]||10) + "%";

        } catch(e) {
            console.error("Gagal update status:", e);
        }
    }

    setInterval(updateStatus, 5000);
});
</script>

</body>
</html>
