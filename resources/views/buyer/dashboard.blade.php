<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warung Mas Ndutt94 - Dashboard Pembeli</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    :root {
        --color-primary: #dc2626;
        --color-accent: #facc15;
        --color-accent-dark: #eab308;
        --color-bg: #E6DCD2;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--color-bg);
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    /* 🌊 BACKGROUND GELMBANG – SAMA DENGAN FILE MENU & PAYMENT */
    body::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image:
          url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' fill-opacity='0.7' d='M0,224L48,229.3C96,235,192,245,288,229.3C384,213,480,171,576,170.7C672,171,768,213,864,240C960,267,1056,277,1152,256C1248,235,1344,181,1392,154.7L1440,128V0H0Z'/%3E%3C/svg%3E");
        background-size: cover;
        opacity: 0.7;
        z-index: 1;
    }

    /* Semua isi harus di depan background */
    .content-wrapper {
        position: relative;
        z-index: 10;
    }

    .bg-primary { background-color: var(--color-primary); }
    .hover-bg-primary:hover { background-color: #b91c1c; }
    .bg-accent-light { background-color: #fef3c7; }

    .shadow-soft {
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1),
                    0 2px 4px -2px rgba(0,0,0,0.06);
    }

    .shadow-accent-subtle {
        box-shadow: 0 1px 3px rgba(250, 202, 21, 0.4),
                    0 1px 2px rgba(250, 202, 21, 0.2);
    }

    .token-box {
        border: 1px solid var(--color-accent-dark);
        transition: transform .2s ease;
    }
    .token-box:hover {
        transform: translateY(-2px);
    }

    #modal-container {
        transition: opacity .3s ease;
    }
  </style>
</head>

<body class="flex justify-center">

  <div class="content-wrapper max-w-xl w-full mx-auto py-12 px-5">

    <!-- HEADER -->
    <div class="text-center mb-8 pb-4 border-b border-yellow-600">
        <h2 class="text-3xl font-extrabold text-[var(--color-primary)] uppercase">
            WARUNG MAS NDUTT94
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Sajian Istimewa, Harga Bersahabat
        </p>
    </div>

    <!-- CARD UTAMA -->
    <div class="bg-white p-8 rounded-2xl shadow-soft border-t-4 border-[var(--color-primary)]">

      <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
          Halo Pembeli Setia
        </h1>

        <a href="{{ route('buyer.logout') }}"
           class="text-sm font-medium text-[var(--color-primary)] px-3 py-1 border border-red-300 rounded-full hover:bg-red-50 hover:text-red-700 transition">
           Keluar
        </a>
      </div>

      <!-- TOKEN -->
      <div class="mt-4">
        <p class="text-gray-700 font-medium mb-2">Token Pembelian Anda:</p>

        <div id="buyer-token"
             class="token-box bg-accent-light px-4 py-3 rounded-lg font-mono text-sm break-all select-all shadow-accent-subtle">
            {{ session('buyer_token') ?? 'TOKEN-TIDAK-TERSEDIA' }}
        </div>

        <p class="text-xs text-gray-500 mt-1">
            Token ini digunakan untuk proses pemesanan.
        </p>
      </div>

      <p class="mt-8 text-xl font-semibold text-gray-700 text-center">
        Pilih Menu Spesial Kami Sekarang!
      </p>

      <a href="{{ route('buyer.menu') }}"
         class="mt-6 inline-flex justify-center items-center w-full bg-primary text-white font-bold text-lg px-6 py-4 rounded-xl shadow-lg border-b-2 border-red-700 transition hover-bg-primary hover:scale-[1.01]">
         CEK DAFTAR MENU & PESAN
      </a>

    </div>

    <!-- KONTAK -->
    <div class="mt-8 p-6 bg-white rounded-2xl shadow-soft border-l-4 border-[var(--color-accent-dark)]">

      <h3 class="text-xl font-bold mb-3 text-[var(--color-primary)]">Hubungi Kami</h3>

      <ul class="space-y-3 text-sm text-gray-700">

        <li class="flex">
          <div class="w-20 font-extrabold text-[var(--color-primary)] flex justify-between mr-1">
            <span>WA</span><span>:</span>
          </div>
          <a href="https://wa.me/6283871524206" target="_blank"
             class="font-semibold hover:underline break-all">
            0838 7152 4206
          </a>
        </li>

        <li class="flex">
          <div class="w-20 font-extrabold text-[var(--color-primary)] flex justify-between mr-1">
            <span>IG</span><span>:</span>
          </div>
          <a href="https://instagram.com/warungmasndutt94" target="_blank"
             class="font-semibold hover:underline break-all">
             @warungmasndutt94
          </a>
        </li>

        <li class="flex">
          <div class="w-20 font-extrabold text-[var(--color-primary)] flex justify-between mr-1">
            <span>Alamat</span><span>:</span>
          </div>
          <span class="font-semibold break-words">
            Komplek Pakujaya Permai A1 No 29
          </span>
        </li>

      </ul>
    </div>
  </div>

  <!-- MODAL -->
  <div id="modal-container"
       class="fixed inset-0 bg-gray-900 bg-opacity-60 z-50 hidden items-center justify-center p-4">

      <div id="modal-content"
           class="bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full transform scale-95 transition duration-300 border-t-4 border-[var(--color-primary)]">

          <h3 id="modal-title" class="text-2xl font-bold mb-3 text-[var(--color-primary)]"></h3>
          <p id="modal-text" class="text-gray-700 mb-6"></p>

          <button onclick="closeModal()"
                  class="w-full bg-primary text-white py-3 rounded-xl shadow hover-bg-primary">
              Oke, Mengerti
          </button>
      </div>

  </div>

  <script>
    const modalContainer = document.getElementById('modal-container');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const modalText = document.getElementById('modal-text');

    function showModal(title, message) {
        modalTitle.textContent = title;
        modalText.textContent = message;
        modalContainer.classList.remove('hidden');
        modalContainer.classList.add('flex');
        setTimeout(() => modalContent.classList.replace('scale-95', 'scale-100'), 10);
    }

    function closeModal() {
        modalContent.classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            modalContainer.classList.replace('flex', 'hidden');
        }, 250);
    }
  </script>

</body>
</html>
