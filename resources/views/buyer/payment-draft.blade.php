<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Pembayaran — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<style>
:root {
    --color-primary: #dc2626;
}
.text-primary { color: var(--color-primary); }
.bg-primary { background-color: var(--color-primary); }
.hover-bg-primary:hover { background-color: #b91c1c; }

/* Background */
.custom-bg {
    background-color: #E6DCD2;
    position: relative;
    min-height: 100vh;
    overflow: hidden;
}
.custom-bg::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23BC9F8F' fill-opacity='0.4' d='M0,128L1440,0L1440,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
    background-size: cover;
    opacity: .25;
}

/* Chef watermark */
.chef-bg {
    background-image: url('https://i.imgur.com/e5l1M1N.png');
    position: fixed;
    width: 200px;
    height: 200px;
    bottom: -10px;
    right: -20px;
    opacity: .35;
    background-size: contain;
    background-repeat: no-repeat;
    z-index: 0;
}

.main-content {
    z-index: 10;
    font-family: Inter, sans-serif;
}
.fredoka-font {
    font-family: 'Fredoka One', cursive;
}
</style>

<body class="custom-bg min-h-screen overflow-y-auto py-8 flex justify-center">

  <!-- Chef watermark -->
  <div class="chef-bg hidden md:block"></div>

  <div class="main-content bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center border-t-4 border-primary">

    <!-- Judul -->
    <h1 class="text-4xl font-black fredoka-font text-primary">Konfirmasi Pembayaran</h1>
    <p class="text-gray-600 text-sm mt-1">Periksa pesanan Anda sebelum lanjut pembayaran</p>

    <!-- ANTRIAN -->
    <div class="bg-gray-50 rounded-xl p-5 my-6 border border-primary/20 shadow-sm">
      <p class="text-gray-600 text-sm mb-1">Nomor Antrian:</p>
      <p class="font-mono text-xl font-black text-primary">#{{ $queue_number }}</p>

      <p class="text-gray-600 text-sm mt-3">Total Bayar:</p>
      <p class="text-3xl font-black text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</p>
    </div>

    <!-- RINCIAN PESANAN -->
    <div class="bg-gray-50 rounded-xl p-4 mb-5 text-left border border-gray-200">

      <h2 class="text-sm font-bold text-gray-700 mb-2">Rincian Pesanan</h2>

      <div class="max-h-40 overflow-y-auto text-sm">
        @foreach ($cart as $item)
          <div class="flex justify-between items-center py-1 border-b last:border-b-0">
            <div>
              <p class="font-semibold text-gray-800">{{ $item['menu'] }}</p>
              <p class="text-xs text-gray-500">{{ $item['quantity'] }}x</p>
            </div>
            <p class="font-semibold text-gray-800">
              Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
            </p>
          </div>
        @endforeach
      </div>

      <a href="{{ route('buyer.menu') }}" class="mt-3 inline-block text-xs text-primary hover:underline">
        Ubah / Tambah Pesanan →
      </a>
    </div>

    <!-- Form -->
    <h2 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">
      Data Pemesan & Metode Pembayaran
    </h2>

    <form id="paymentForm"
          action="{{ route('buyer.payment.submit') }}"
          method="POST"
          enctype="multipart/form-data">

      @csrf

      <!-- DATA PEMESAN -->
      <div class="text-left space-y-3 mb-4">

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pemesan</label>
          <input type="text" name="customer_name"
                 class="w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/60"
                 placeholder="Contoh: Budi"
                 required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>
          <input type="text" name="customer_phone"
                 class="w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/60"
                 placeholder="08123456789"
                 required>
        </div>
      </div>


      <!-- METODE PEMBAYARAN -->
      <div class="space-y-4 text-left">

        <!-- TRANSFER -->
        <label class="block p-4 border rounded-xl bg-white cursor-pointer shadow-sm hover:border-primary transition">
          <div class="flex justify-between items-center">
            <span class="flex items-center gap-3">
              <img src="https://upload.wikimedia.org/wikipedia/commons/6/6c/QRIS_logo.png" class="w-6 opacity-90">
              <span class="font-semibold text-sm">Transfer / QRIS (Upload Bukti)</span>
            </span>
            <input type="radio" name="payment_method" value="transfer" checked>
          </div>
          <p class="text-xs text-gray-500 mt-2">
            Upload bukti transfer atau pembayaran QRIS.
          </p>
        </label>

        <!-- CASH -->
        <label class="block p-4 border rounded-xl bg-white cursor-pointer shadow-sm hover:border-primary transition">
          <div class="flex justify-between items-center">
            <span class="flex items-center gap-3">
              <img src="https://img.icons8.com/color/48/cash-in-hand.png" class="w-7">
              <span class="font-semibold text-sm">Bayar Tunai</span>
            </span>
            <input type="radio" name="payment_method" value="cash">
          </div>
          <p class="text-xs text-gray-500 mt-2">
            Lakukan pembayaran tunai di kasir.
          </p>
        </label>

      </div>


      <!-- Upload Bukti -->
      <div class="mt-4 text-left">
        <label class="block text-sm font-semibold text-gray-700 mb-1">
          Upload Bukti Pembayaran
        </label>

        <input type="file" name="payment_proof"
               accept="image/png,image/jpeg"
               class="w-full text-sm file:mr-3 file:px-3 file:py-1.5 file:border-0 file:rounded-lg file:bg-primary file:text-white file:cursor-pointer">

        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, max 2MB. (Tidak wajib jika cash)</p>
      </div>


      <!-- SUBMIT BUTTON -->
      <button type="submit"
        class="w-full bg-primary text-white py-3 mt-6 rounded-xl font-black hover-bg-primary shadow-md">
        Kirim Bukti / Konfirmasi Pembayaran
      </button>

    </form>

    <a href="{{ route('buyer.menu') }}" class="block text-center mt-4 text-gray-600 hover:underline">
      Batalkan Pembayaran
    </a>

  </div>

</body>
</html>
