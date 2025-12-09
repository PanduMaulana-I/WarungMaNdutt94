<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <title>Tambah Menu — Warung Mas Ndutt94</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    :root {
        --primary: #dc2626;   /* merah */
        --secondary: #facc15; /* kuning emas */
        --bg-soft: #E6DCD2;   /* krem */
    }

    body {
        background: var(--bg-soft);
        font-family: "Inter", sans-serif;
    }

    /* Wave Background */
    .wave {
        position: relative;
    }
    .wave::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 230px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' d='M0,192L80,208C160,224,320,256,480,256C640,256,800,224,960,202.7C1120,181,1280,171,1360,165.3L1440,160L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z'%3E%3C/path%3E%3C/svg%3E");
        background-size: cover;
        opacity: .85;
        z-index: -1;
    }
  </style>
</head>

<body class="wave flex items-center justify-center min-h-screen p-5">

  <div class="bg-white w-full max-w-xl p-8 rounded-2xl shadow-xl border border-[var(--secondary)]/40">

    {{-- TITLE --}}
    <h1 class="text-3xl font-bold mb-6 text-center text-[var(--primary)] tracking-tight">
      Tambah Menu
    </h1>

    {{-- ERROR --}}
    @if($errors->any())
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 border border-red-200">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- FORM --}}
    <form action="{{ route('seller.menus.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Nama --}}
      <label class="block font-semibold text-slate-700 mb-1">Nama Menu</label>
      <input type="text" name="name"
             class="w-full border rounded-lg px-3 py-2 mb-4 border-slate-300 
                    focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
             required>

      {{-- Deskripsi --}}
      <label class="block font-semibold text-slate-700 mb-1">Deskripsi</label>
      <textarea name="description" rows="3"
                class="w-full border rounded-lg px-3 py-2 mb-4 border-slate-300 
                       focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"></textarea>

      {{-- Harga --}}
      <label class="block font-semibold text-slate-700 mb-1">Harga (Rp)</label>
      <input type="number" name="price"
             class="w-full border rounded-lg px-3 py-2 mb-4 border-slate-300
                    focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
             required>

      {{-- Stok --}}
      <label class="block font-semibold text-slate-700 mb-1">Stok</label>
      <input type="number" name="stock"
             class="w-full border rounded-lg px-3 py-2 mb-4 border-slate-300
                    focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
             required>

      {{-- Gambar --}}
      <label class="block font-semibold text-slate-700 mb-1">Gambar Menu</label>
      <input type="file" name="image"
             class="w-full border rounded-lg px-3 py-2 mb-6 border-slate-300">

      {{-- Actions --}}
      <div class="flex justify-between items-center">

        <a href="{{ route('seller.dashboard') }}"
           class="text-slate-600 hover:text-slate-900 transition font-medium">
          Dashboard
        </a>

        <a href="{{ route('seller.menus.index') }}"
           class="text-slate-600 hover:text-slate-900 transition font-medium">
          Kembali
        </a>

        <button class="bg-[var(--primary)] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-red-700 transition shadow">
          Simpan
        </button>

      </div>

    </form>
  </div>

</body>
</html>
