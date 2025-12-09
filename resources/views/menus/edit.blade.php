<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Menu — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    :root {
        --primary: #dc2626;    /* merah */
        --secondary: #facc15;  /* kuning emas */
        --bg-soft: #E6DCD2;    /* krem */
    }

    body {
        background: var(--bg-soft);
        font-family: "Inter", sans-serif;
    }

    /* Wave top */
    .wave {
        position: relative;
    }
    .wave::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 220px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' d='M0,160L80,165.3C160,171,320,181,480,197.3C640,213,800,235,960,224C1120,213,1280,171,1360,149.3L1440,128L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z'%3E%3C/path%3E%3C/svg%3E");
        background-size: cover;
        opacity: .8;
        z-index: -1;
    }
  </style>
</head>

<body class="wave flex items-center justify-center min-h-screen p-4">

  <div class="bg-white w-full max-w-xl p-8 rounded-2xl shadow-xl border border-[var(--secondary)]/40">

    {{-- TITLE --}}
    <h1 class="text-3xl font-bold text-[var(--primary)] text-center mb-6 tracking-tight">
      Edit Menu
    </h1>

    {{-- ERROR --}}
    @if($errors->any())
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 border border-red-200">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- SUCCESS --}}
    @if(session('success'))
      <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 border border-green-200">
        {{ session('success') }}
      </div>
    @endif

    {{-- FORM --}}
    <form action="{{ route('seller.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      {{-- Nama --}}
      <label class="block font-semibold mb-1 text-slate-700">Nama Menu</label>
      <input type="text" name="name" value="{{ old('name', $menu->name) }}"
             class="w-full border border-slate-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
             required>

      {{-- Deskripsi --}}
      <label class="block font-semibold mb-1 text-slate-700">Deskripsi</label>
      <textarea name="description" rows="3"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none">{{ old('description', $menu->description) }}</textarea>

      {{-- Harga --}}
      <label class="block font-semibold mb-1 text-slate-700">Harga (Rp)</label>
      <input type="number" name="price" value="{{ old('price', $menu->price) }}"
             class="w-full border border-slate-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
             required>

      {{-- Stok --}}
      <label class="block font-semibold mb-1 text-slate-700">Stok</label>
      <input type="number" name="stock" value="{{ old('stock', $menu->stock) }}"
             class="w-full border border-slate-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
             required>

      {{-- Gambar --}}
      <label class="block font-semibold mb-1 text-slate-700">Gambar Menu</label>
      @if($menu->image)
        <img src="{{ asset('storage/'.$menu->image) }}"
             class="w-32 h-32 object-cover rounded-lg shadow mb-3 border">
      @endif

      <input type="file" name="image"
             class="w-full border border-slate-300 rounded-lg px-3 py-2 mb-6">

      {{-- Aksi --}}
      <div class="flex justify-between items-center">

        <a href="{{ route('seller.menus.index') }}"
           class="text-slate-600 hover:text-slate-900 font-medium transition">
          Kembali
        </a>

        <button class="px-6 py-2.5 bg-[var(--primary)] text-white font-semibold rounded-lg hover:bg-red-700 transition shadow">
          Simpan Perubahan
        </button>
      </div>

    </form>
  </div>

</body>
</html>
