<!-- resources/views/menus/index.blade.php -->
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Daftar Menu — WebTransaksi</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <style>body{background:linear-gradient(180deg,#f8fafc 0,#eef2f7 100%);} .glass{background:rgba(255,255,255,0.86);backdrop-filter:blur(6px);}</style>
</head>
<body class="antialiased text-slate-800">

  <header class="sticky top-0 z-30 bg-white/60 backdrop-blur-sm border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-gradient-to-tr from-rose-500 to-indigo-500 rounded-xl flex items-center justify-center text-white font-bold shadow">🍽</div>
        <div>
          <h1 class="text-2xl font-extrabold">Daftar Menu</h1>
          <p class="text-sm text-slate-500">Pilih menu, tambahkan jumlah, langsung pesan.</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <input id="search" type="search" placeholder="Cari menu (contoh: Nasi)" class="px-3 py-2 rounded-md border border-slate-200 shadow-sm">
        <a href="#" id="cartBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md shadow">Keranjang</a>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-10">
    <section class="mb-6">
      <div class="glass p-4 rounded-2xl shadow-sm flex items-center justify-between">
        <div>
          <h2 class="text-lg font-semibold">Pilihan Terpopuler</h2>
          <p class="text-sm text-slate-500">Klik “Pesan” untuk tambah ke keranjang lalu checkout.</p>
        </div>
      </div>
    </section>

    <section>
      <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($menus as $menu)
        <div class="card glass p-4 rounded-2xl shadow-sm" data-id="{{ $menu->id }}" data-name="{{ strtolower($menu->name) }}" data-available="{{ $menu->is_available }}">
          <div class="flex gap-4">
            <div class="w-24 h-24 rounded-lg bg-slate-100 flex items-center justify-center overflow-hidden shrink-0">
              @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover">
              @else
                <div class="text-slate-400 text-sm px-2 text-center">No Image</div>
              @endif
            </div>

            <div class="flex-1">
              <div class="flex items-start justify-between">
                <div>
                  <h3 class="text-lg font-semibold">{{ $menu->name }}</h3>
                  <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $menu->description }}</p>
                </div>
                <div class="text-right">
                  <div class="text-indigo-700 font-bold">Rp {{ number_format($menu->price,0,',','.') }}</div>
                  <div class="text-xs text-slate-400 mt-1">{{ $menu->stock }} stok</div>
                </div>
              </div>

              <div class="mt-4 flex items-center gap-2">
                <button class="order-btn inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500 text-white rounded-md text-sm" data-id="{{ $menu->id }}" data-name="{{ e($menu->name) }}" data-price="{{ $menu->price }}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M9 21h6" /></svg>
                  Pesan
                </button>

                <a href="{{ route('menus.show', $menu->id) }}" class="px-3 py-1.5 border border-slate-200 rounded-md text-sm">Detail</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </section>
  </main>

  <!-- Order Modal -->
  <div id="orderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow max-w-lg w-full p-5">
      <h3 class="text-lg font-semibold mb-3">Tambah ke Keranjang</h3>

      <div id="modalContent">
        <div class="flex gap-4">
          <div class="w-24 h-24 bg-slate-100 rounded-md flex items-center justify-center">No Image</div>
          <div class="flex-1">
            <div id="modalName" class="font-semibold"></div>
            <div id="modalPrice" class="text-indigo-700 font-bold"></div>
            <div class="mt-2">
              <label class="text-sm">Jumlah</label>
              <input id="modalQty" type="number" min="1" value="1" class="w-24 px-2 py-1 border rounded-md">
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label class="text-sm block">Nama (opsional)</label>
          <input id="customerName" type="text" class="w-full px-3 py-2 border rounded-md" placeholder="Nama pembeli">
          <label class="text-sm block mt-2">No HP (opsional)</label>
          <input id="customerPhone" type="text" class="w-full px-3 py-2 border rounded-md" placeholder="0812xxxx">
        </div>

        <div class="mt-4 flex justify-end gap-2">
          <button id="modalClose" class="px-4 py-2 bg-slate-100 rounded-md">Batal</button>
          <button id="modalCheckout" class="px-4 py-2 bg-emerald-500 text-white rounded-md">Checkout</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="py-8 text-center text-sm text-slate-500">© {{ date('Y') }} WebTransaksi</footer>

<script>
  feather.replace();

  // modal helpers
  const orderModal = document.getElementById('orderModal');
  const modalName = document.getElementById('modalName');
  const modalPrice = document.getElementById('modalPrice');
  const modalQty = document.getElementById('modalQty');
  const modalClose = document.getElementById('modalClose');
  const modalCheckout = document.getElementById('modalCheckout');
  const customerName = document.getElementById('customerName');
  const customerPhone = document.getElementById('customerPhone');

  let currentMenu = null;

  document.querySelectorAll('.order-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      currentMenu = {
        id: btn.dataset.id,
        name: btn.dataset.name,
        price: btn.dataset.price
      };
      modalName.textContent = currentMenu.name;
      modalPrice.textContent = 'Rp ' + Number(currentMenu.price).toLocaleString('id-ID');
      modalQty.value = 1;
      customerName.value = '';
      customerPhone.value = '';
      orderModal.classList.remove('hidden');
      orderModal.classList.add('flex');
    });
  });

  modalClose.addEventListener('click', () => {
    orderModal.classList.add('hidden');
    orderModal.classList.remove('flex');
  });

  // checkout -> POST /orders
  modalCheckout.addEventListener('click', async () => {
    const qty = parseInt(modalQty.value) || 1;
    const payload = {
      customer_name: customerName.value || null,
      customer_phone: customerPhone.value || null,
      items: [{ menu_id: parseInt(currentMenu.id), quantity: qty }]
    };

    modalCheckout.disabled = true;
    modalCheckout.textContent = 'Mengirim...';

    try {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const res = await fetch("{{ route('orders.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const json = await res.json();
      if (res.ok && json.success) {
        alert('Pesanan berhasil dibuat. Nomor: ' + json.order_number);
        // close modal
        orderModal.classList.add('hidden'); orderModal.classList.remove('flex');
      } else {
        alert('Gagal membuat pesanan: ' + (json.message || 'server error'));
      }
    } catch (e) {
      alert('Terjadi kesalahan: ' + e.message);
    } finally {
      modalCheckout.disabled = false;
      modalCheckout.textContent = 'Checkout';
    }
  });

  // basic search
  const search = document.getElementById('search');
  const grid = document.getElementById('grid');
  search?.addEventListener('input', () => {
    const q = (search.value || '').toLowerCase().trim();
    Array.from(grid.children).forEach(card => {
      const name = (card.dataset.name || '').toLowerCase();
      card.style.display = (!q || name.includes(q)) ? '' : 'none';
    });
  });

</script>
</body>
</html>
