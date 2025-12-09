<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Keranjang — WebTransaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="max-w-3xl mx-auto p-6">
    <a href="/menus" class="text-sm text-indigo-600 hover:underline">&larr; Kembali</a>
    <h1 class="text-2xl font-bold mt-4 mb-6">🛒 Keranjang</h1>

    <div id="cartList"></div>
    <div id="empty" class="text-slate-500">Keranjang kosong.</div>
    <div id="totalWrap" class="hidden mt-6">
      <h2 class="font-semibold text-lg">Total: <span id="total" class="text-indigo-700"></span></h2>
      <button id="checkoutBtn" class="mt-4 px-5 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">Checkout</button>
    </div>
  </div>

<script>
  const cart = JSON.parse(localStorage.getItem('cart')||'[]');
  const list = document.getElementById('cartList');
  const totalWrap = document.getElementById('totalWrap');
  const empty = document.getElementById('empty');
  let total = 0;

  if(cart.length){
    empty.classList.add('hidden');
    totalWrap.classList.remove('hidden');
    cart.forEach(i=>{
      total += i.price * i.qty;
      list.innerHTML += `
        <div class="bg-white rounded-md p-4 shadow mb-3 flex justify-between">
          <div>
            <p class="font-semibold">${i.name}</p>
            <p class="text-sm text-slate-500">Rp ${i.price.toLocaleString()} × ${i.qty}</p>
          </div>
          <p class="font-semibold text-indigo-700">Rp ${(i.price*i.qty).toLocaleString()}</p>
        </div>`;
    });
    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString();
  }

  document.getElementById('checkoutBtn').addEventListener('click', async ()=>{
    const res = await fetch('/orders', {
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content
      },
      body:JSON.stringify({
        items:cart.map(i=>({menu_id:i.menu_id, quantity:i.qty}))
      })
    });
    const data = await res.json();
    if(data.success){
      localStorage.removeItem('cart');
      location.href = data.redirect;
    }else{
      alert('❌ Gagal: '+data.message);
    }
  });
</script>
</body>
</html>
