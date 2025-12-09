<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Menu — Warung Mas Ndutt94</title>

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --color-primary: #dc2626;
      --color-secondary: #facc15;
      --color-bg: #E6DCD2;
    }

    body {
      background-color: var(--color-bg);
      font-family: Inter, sans-serif;
      overflow-x: hidden;
    }

    /* SOFT BACKGROUND WAVE */
    .bg-wave {
      position: relative;
    }
    .bg-wave::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' d='M0,224L48,229.3C96,235,192,245,288,229.3C384,213,480,171,576,170.7C672,171,768,213,864,240C960,267,1056,277,1152,256C1248,235,1344,181,1392,154.7L1440,128V0H0Z'/%3E%3C/svg%3E");
      background-size: cover;
      opacity: .65;
      z-index: -1;
    }

    /* Category Tabs */
    .category-tab {
      background: #e5e7eb;
      padding: 8px 18px;
      border-radius: 20px;
      font-weight: 600;
      transition: .25s ease;
      cursor: pointer;
    }
    .category-tab:hover { background: #fca5a5; }
    .category-active {
      background: var(--color-primary) !important;
      color: white !important;
    }
  </style>
</head>

<body>

<div class="p-6 bg-wave">

  <!-- MAIN WRAPPER -->
  <div class="max-w-7xl mx-auto bg-white rounded-3xl shadow-xl p-10 border border-yellow-300/40">

    <!-- HEADER -->
    <div class="text-center mb-10">
      <h1 class="text-5xl font-extrabold text-[var(--color-primary)] uppercase" style="font-family:Poppins;">
        Warung Mas Ndutt94
      </h1>

      <p class="text-lg text-gray-600 mt-2 font-medium">Pilih Menu Spesial</p>
      <p class="mt-2 text-gray-800 text-lg">
        Nomor Antrian:
        <span class="text-[var(--color-primary)] font-extrabold">
          #{{ $queue_number }}
        </span>
      </p>
    </div>

    <!-- BACK BUTTON -->
    <a href="{{ route('buyer.dashboard') }}"
       class="inline-block mb-6 px-5 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold shadow">
       ← Kembali ke Dashboard
    </a>

    <!-- OUTLET INFO -->
    <a href="{{ route('buyer.outlet.info') }}"
       class="block bg-white shadow-md rounded-2xl p-4 border-l-4 border-yellow-400 mb-8">
      <div class="flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold text-gray-900">Outlet Info</h3>
          <p class="text-sm text-gray-600">Jam Operasional • Alamat • Kontak</p>
        </div>
        <div class="text-gray-600 text-xl">›</div>
      </div>
    </a>

    <!-- SEARCH BAR -->
    <div class="max-w-3xl mx-auto mb-6">
      <input id="searchMenu" type="text" placeholder="Cari menu..."
             class="w-full p-3 border-2 border-gray-300 rounded-xl shadow focus:ring-2 focus:ring-red-300 text-lg">
    </div>

   <!-- CATEGORY TABS -->
<div class="flex flex-wrap gap-3 justify-center mb-8">
  @php
    $categories = ['Semua','Nasi','Ayam','Bihun','Kwetiau','Mie','Katsu','Minuman','Gorengan'];
  @endphp

  @foreach ($categories as $i => $cat)
    <button class="category-tab {{ $i==0 ? 'category-active':'' }}"
            data-category="{{ strtolower($cat) }}">
      {{ $cat }}
    </button>
  @endforeach
</div>

<!-- MENU GRID -->
<div id="menu-grid"
     class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">

  @foreach($menus as $menu)

  @php
    $n = strtolower($menu->name);

    if (str_contains($n,'nasi')) $cat='nasi';
    elseif (str_contains($n,'ayam')) $cat='ayam';
    elseif (str_contains($n,'bihun')) $cat='bihun';
    elseif (str_contains($n,'kwetiau') || str_contains($n,'kwetiaw')) $cat='kwetiau';
    elseif (str_contains($n,'mie')) $cat='mie';
    elseif (str_contains($n,'katsu')) $cat='katsu';
    elseif (
      str_contains($n,'air') || str_contains($n,'mineral') ||
      str_contains($n,'es')  || str_contains($n,'teh') ||
      str_contains($n,'kopi')|| str_contains($n,'jus') ||
      str_contains($n,'milk')|| str_contains($n,'drink')
    ) $cat='minuman';
    elseif (
      str_contains($n,'tempe') || str_contains($n,'tahu')
    ) $cat='gorengan';
    else $cat='gorengan';
  @endphp


      <!-- MENU CARD -->
      <div data-menu-card="{{ $menu->id }}"
           data-category="{{ $cat }}"
           class="bg-white rounded-2xl shadow-xl border-b-4 border-yellow-400 p-6 text-center
           @if($menu->stock == 0) opacity-50 grayscale @endif">

        <!-- IMAGE -->
        <div class="h-40 rounded-xl overflow-hidden border-2 border-yellow-300 mb-5 relative">
          <img src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://placehold.co/300x150/dc2626/fff?text=Menu' }}"
               class="w-full h-full object-cover">

          @if($menu->stock == 0)
          <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <span class="bg-red-600 text-white font-bold px-4 py-1 rounded-xl shadow">
              SOLD OUT
            </span>
          </div>
          @endif
        </div>

        <!-- NAME -->
        <h3 class="text-xl font-bold text-gray-900">{{ $menu->name }}</h3>

        <!-- DESC -->
        <p class="text-gray-600 text-sm mt-1 min-h-[44px]">
          {{ $menu->description ?? 'Enak banget!' }}
        </p>

        <!-- PRICE -->
        <p class="text-[var(--color-primary)] text-3xl font-extrabold mt-4">
          Rp {{ number_format($menu->price,0,',','.') }}
        </p>

        <!-- QUANTITY CONTROL -->
        <div class="mt-5 flex flex-col items-center gap-2"
             data-menu-wrapper="{{ $menu->id }}">

          <input type="hidden" class="menu-id" value="{{ $menu->id }}">
          <input type="hidden" class="menu-qty" value="0">

          @if($menu->stock == 0)

            <button class="w-full px-4 py-2 rounded-full bg-gray-400 text-white font-semibold cursor-not-allowed">
              Stok Habis
            </button>

          @else

            <!-- BUTTON SHOW QTY -->
            <button type="button"
              class="btn-show-qty inline-flex items-center justify-center px-4 py-2 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold shadow hover:bg-red-700 transition">
              Tambah
            </button>

            <!-- QTY CONTROL -->
            <div class="qty-control hidden flex items-center gap-3">

              <button type="button"
                      class="btn-minus w-9 h-9 flex items-center justify-center rounded-full border border-red-400 text-red-600 text-xl font-bold hover:bg-red-50">
                −
              </button>

              <span class="qty-label min-w-[26px] text-center text-lg font-semibold text-gray-900">
                1
              </span>

              <button type="button"
                      class="btn-plus w-9 h-9 flex items-center justify-center rounded-full bg-[var(--color-primary)] text-white text-xl font-bold hover:bg-red-700">
                +
              </button>

            </div>

          @endif

        </div>

      </div>
      @endforeach

    </div>

    <!-- CHECKOUT BUTTON -->
    <div class="text-center mt-14">
      <button id="orderBtn"
              class="bg-[var(--color-primary)] text-white font-bold text-lg px-12 py-4 rounded-2xl shadow-xl hover:bg-red-700 transition">
        Lanjutkan ke Pembayaran
      </button>

      <p class="mt-4">
        <a href="{{ route('buyer.orders') }}"
           class="text-[var(--color-primary)] font-semibold hover:underline">
          Lihat Status Pesanan
        </a>
      </p>
    </div>

  </div>
</div>

<!-- SEARCH & CATEGORY FILTER -->
<script>
document.getElementById("searchMenu").addEventListener("input", e => {
  const key = e.target.value.toLowerCase();
  document.querySelectorAll("[data-menu-card]").forEach(card => {
    let name = card.querySelector("h3").innerText.toLowerCase();
    card.style.display = name.includes(key) ? "" : "none";
  });
});

document.querySelectorAll(".category-tab").forEach(btn => {
  btn.addEventListener("click", () => {

    document.querySelectorAll(".category-tab")
      .forEach(t => t.classList.remove("category-active"));

    btn.classList.add("category-active");

    let selected = btn.dataset.category;

    document.querySelectorAll("[data-menu-card]").forEach(card => {
      card.style.display =
        (selected === "semua" || selected === card.dataset.category)
          ? "" : "none";
    });

  });
});
</script>

<!-- QTY CONTROL LOGIC -->
<script>
document.querySelectorAll("[data-menu-wrapper]").forEach(wrap => {

  const input = wrap.querySelector(".menu-qty");
  const btnShow = wrap.querySelector(".btn-show-qty");
  const ctrl = wrap.querySelector(".qty-control");

  const minus = wrap.querySelector(".btn-minus");
  const plus  = wrap.querySelector(".btn-plus");
  const label = wrap.querySelector(".qty-label");

  let qty = 0;

  if (btnShow) {
    btnShow.addEventListener("click", () => {
      qty = 1;
      input.value = 1;
      label.textContent = "1";
      btnShow.classList.add("hidden");
      ctrl.classList.remove("hidden");
    });
  }

  if (plus) {
    plus.addEventListener("click", () => {
      qty++;
      input.value = qty;
      label.textContent = qty;
    });
  }

  if (minus) {
    minus.addEventListener("click", () => {
      if (qty > 1) {
        qty--;
        input.value = qty;
        label.textContent = qty;
      } else {
        qty = 0;
        input.value = 0;
        ctrl.classList.add("hidden");
        btnShow.classList.remove("hidden");
      }
    });
  }

});
</script>

<!-- CHECKOUT -->
<script>
document.getElementById("orderBtn").addEventListener("click", async () => {

  let items = [];

  document.querySelectorAll("[data-menu-wrapper]").forEach(wrap => {
    const id  = Number(wrap.querySelector(".menu-id").value);
    const qty = Number(wrap.querySelector(".menu-qty").value);

    if (qty > 0) {
      items.push({ menu_id: id, quantity: qty });
    }
  });

  if (items.length === 0) {
    Swal.fire({ icon:"warning", title:"Belum memilih menu" });
    return;
  }

  try {

    let res = await axios.post(
      "{{ route('buyer.checkout') }}",
      { items }
    );

    if (res.data.redirect_url) {
      window.location.href = res.data.redirect_url;
    }

  } catch (err) {
    Swal.fire({
      icon:"error",
      title:"Stok Tidak Cukup",
      text: err.response?.data?.error || "Terjadi kesalahan."
    });
  }
});
</script>

<!-- PUSHER REALTIME -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8/dist/web/pusher.min.js"></script>

<script>
window.Echo = new Echo({
  broadcaster: "pusher",
  key: "{{ config('broadcasting.connections.pusher.key') }}",
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
});

window.Echo.channel("menu-updates")
  .listen(".menu.updated", e => console.log("UPDATE MENU REALTIME:", e.menu));
</script>

</body>
</html>
