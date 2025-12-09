<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pilih Role — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Fredoka+One&display=swap" rel="stylesheet">

  <style>
    :root {
        --color-primary: #dc2626;
        --color-secondary: #facc15;
        --color-bg: #E6DCD2;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--color-bg);
        min-height: 100vh;
        margin: 0;
        position: relative;
        overflow-x: hidden;
        padding: 20px; /* biar aman di HP */
    }

    /* 🌊 BACKGROUND WAVE */
    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
          url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%23BC9F8F" fill-opacity="0.8" d="M0,192L48,181.3C96,171,192,149,288,149.3C384,149,480,171,576,192C672,213,768,235,864,240C960,245,1056,235,1152,213.3C1248,192,1344,160,1392,144L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"%3E%3C/path%3E%3C/svg%3E'),
          url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%23DCCFC4" fill-opacity="1" d="M0,160L48,149.3C96,139,192,117,288,106.7C384,96,480,96,576,117.3C672,139,768,181,864,181.3C960,181,1056,139,1152,128C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: top left, bottom right;
        background-size: 160% auto, 160% auto;
        opacity: 0.75;
        z-index: 1;
    }

    .content-wrapper {
        position: relative;
        z-index: 10;
    }

    .card {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from { opacity:0; transform:translateY(25px); }
      to   { opacity:1; transform:translateY(0); }
    }

    .btn-primary {
        background-color: var(--color-primary);
        transition: .2s ease;
    }
    .btn-primary:hover {
        background-color: #b91c1c;
        transform: scale(1.02);
    }

    .btn-secondary {
        background-color: #6366f1;
        transition: .2s ease;
    }
    .btn-secondary:hover {
        background-color: #4f46e5;
        transform: scale(1.02);
    }
  </style>
</head>

<body>

<div class="content-wrapper flex items-center justify-center min-h-screen">

  <div class="card bg-white p-10 rounded-2xl shadow-2xl max-w-md w-full text-center border-t-8 border-[var(--color-primary)]">

    <!-- Judul -->
    <h1 class="text-4xl font-black mb-3 text-[var(--color-primary)]" style="font-family:'Fredoka One', cursive;">
      WARUNG MAS NDUTT94
    </h1>

    <h2 class="text-lg font-bold text-gray-700 mb-2">
      Pilih Mode Akses
    </h2>

    <p class="text-gray-500 mb-8 text-sm">
      Silakan pilih peran sebelum melanjutkan.
    </p>

    <!-- Tombol Role -->
    <div class="flex flex-col gap-4">

      <a href="{{ route('seller.login') }}"
         class="btn-primary text-white font-semibold py-3 rounded-xl shadow-md block">
        Masuk sebagai Penjual
      </a>

      <a href="{{ route('buyer.qr') }}"
         class="btn-secondary text-white font-semibold py-3 rounded-xl shadow-md block">
        Masuk sebagai Pembeli (Scan QR)
      </a>

    </div>

    <div class="mt-8 border-t pt-4 text-xs text-gray-600">
      © {{ date('Y') }} Warung Mas Ndutt94 — Sistem Pemesanan
    </div>

  </div>

</div>

</body>
</html>
