<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scan QR — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Inter & Fredoka One -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Fredoka+One&display=swap" rel="stylesheet">

  <style>
    :root {
      --color-primary: #dc2626;
      --color-secondary: #facc15;
    }

    body {
      font-family: 'Inter', sans-serif;
    }

    /* Background krem + gelombang */
    .custom-bg {
      background-color: #E6DCD2;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    .custom-bg::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' fill-opacity='1' d='M0,224L48,229.3C96,235,192,245,288,229.3C384,213,480,171,576,170.7C672,171,768,213,864,240C960,267,1056,277,1152,256C1248,235,1344,181,1392,154.7L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z'%3E%3C/path%3E%3C/svg%3E");
      background-size: cover;
      background-repeat: no-repeat;
      opacity: .75;
      z-index: 1;
    }

    /* Kartu QR */
    .qr-card {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.88);
    }

    .brand-title {
      font-family: 'Fredoka One', cursive;
    }
  </style>
</head>

<body class="custom-bg flex flex-col items-center justify-start pt-16 px-4">

  <!-- Header -->
  <div class="text-center mb-10 relative z-20">
    <h1 class="text-4xl font-black brand-title text-[var(--color-primary)] tracking-wide">
      Warung Mas Ndutt94
    </h1>
    <p class="text-sm text-gray-700 mt-1 font-medium">Sistem Pemesanan Digital</p>
  </div>

  <!-- Card -->
  <div class="qr-card shadow-2xl border border-white/40 rounded-3xl px-10 py-10 w-[24rem] text-center relative z-20">

    <h2 class="text-xl font-extrabold text-gray-900 mb-3">
      Scan QR untuk Membuka Menu Pembeli
    </h2>

    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
      Arahkan kamera HP Anda ke QR kode di bawah untuk mendapatkan akses
      menu dan menerima nomor antrian otomatis.
    </p>

    <!-- QR IMAGE -->
    <div class="flex justify-center mb-6">
      <img 
        src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&data={{ urlencode($url) }}" 
        alt="QR Code"
        class="rounded-xl border-4 border-[var(--color-secondary)] bg-white shadow-xl p-1"
      >
    </div>

    <!-- ANTRIAN -->
    <div class="bg-[var(--color-primary)] text-white py-2 px-5 rounded-xl inline-block shadow-md">
      <span class="font-semibold">Nomor Antrian:</span>
      <span class="font-extrabold text-yellow-300">#{{ $nextQueue }}</span>
    </div>

    <p class="mt-5 text-xs text-gray-500 italic">
      Nomor antrian baru dihasilkan setiap kali QR discan.
    </p>
  </div>

  <!-- Footer -->
  <footer class="mt-12 mb-6 text-gray-700 text-xs text-center relative z-20 font-medium">
    © {{ date('Y') }} Warung Mas Ndutt94 — Sistem Pemesanan
  </footer>

</body>
</html>
