@extends('layouts.seller')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-md border border-slate-200">

  <h1 class="text-3xl font-extrabold mb-6 text-center text-[var(--color-primary)]">
      🧾 Detail Pesanan
  </h1>

  {{-- ==================== INFO UTAMA ==================== --}}
  <div class="mb-6 border-b pb-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

          <p>
              <span class="font-semibold text-slate-600">No. Pesanan:</span>
              {{ $order->order_number }}
          </p>

          <p>
              <span class="font-semibold text-slate-600">Tanggal:</span>
              {{ $order->created_at->format('d M Y H:i') }}
          </p>

          <p>
              <span class="font-semibold text-slate-600">Nama Pembeli:</span>
              {{ $order->customer_name ?? 'Pembeli Umum' }}
          </p>

          <p>
              <span class="font-semibold text-slate-600">Nomor HP:</span>
              {{ $order->customer_phone ?? '-' }}
          </p>

          <p>
              <span class="font-semibold text-slate-600">Total Harga:</span>
              <span class="font-bold text-[var(--primary)]">
                  Rp {{ number_format($order->total_price, 0, ',', '.') }}
              </span>
          </p>

          <p>
              <span class="font-semibold text-slate-600">Status:</span>
              <span class="px-2 py-1 rounded text-xs font-semibold
                  @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                  @elseif($order->status == 'processing') bg-blue-100 text-blue-700
                  @elseif($order->status == 'delivering') bg-purple-100 text-purple-700
                  @elseif($order->status == 'completed') bg-green-100 text-green-700
                  @else bg-rose-100 text-rose-700 @endif">
                  {{ ucfirst($order->status) }}
              </span>
          </p>

      </div>
  </div>

  {{-- ==================== BUKTI PEMBAYARAN ==================== --}}
  <div class="mb-6">
      <h2 class="font-bold text-lg mb-2 text-slate-700">Bukti Pembayaran</h2>

      @if($order->payment_proof)
          <div class="flex flex-col sm:flex-row items-start gap-4">
              <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank">
                  <img src="{{ asset('storage/'.$order->payment_proof) }}"
                      alt="Bukti Pembayaran"
                      class="w-40 h-40 object-cover rounded-lg border shadow">
              </a>

              <p class="text-xs text-slate-600 leading-relaxed">
                  Pembeli sudah mengirimkan bukti pembayaran. Silakan verifikasi kesesuaian nominal & nama.
                  <br><br>
                  Jika valid → ubah status menjadi <strong>Dimasak</strong> atau <strong>Selesai</strong>.
              </p>
          </div>
      @else
          <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 px-3 py-2 rounded-lg">
              Belum ada bukti pembayaran diunggah pembeli.
          </p>
      @endif
  </div>

  {{-- ==================== RINCIAN ITEM ==================== --}}
  <h2 class="font-bold text-lg mb-3 text-slate-700">Rincian Item</h2>

  <div class="overflow-x-auto rounded-xl border">
      <table class="w-full text-sm">
          <thead>
              <tr class="bg-slate-100 text-slate-700 text-sm">
                  <th class="py-2 text-left px-3">Menu</th>
                  <th class="py-2 text-center">Jumlah</th>
                  <th class="py-2 text-right px-3">Subtotal</th>
              </tr>
          </thead>

          <tbody>
              @forelse(($details ?? []) as $item)
              <tr class="border-t hover:bg-slate-50">
                  <td class="py-2 px-3">{{ $item['menu'] }}</td>
                  <td class="py-2 text-center">{{ $item['quantity'] }}</td>
                  <td class="py-2 text-right px-3">
                      Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                  </td>
              </tr>
              @empty
              <tr>
                  <td colspan="3" class="text-center text-slate-400 py-4">
                      Tidak ada detail item untuk pesanan ini.
                  </td>
              </tr>
              @endforelse
          </tbody>
      </table>
  </div>

  {{-- ==================== TOTAL AKHIR ==================== --}}
  <div class="text-right mt-4 text-base font-semibold text-[var(--primary)]">
      Total Akhir:
      Rp {{ number_format($order->total_price, 0, ',', '.') }}
  </div>

  {{-- ==================== TOMBOL AKSI ==================== --}}
  <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">

      {{-- Kembali --}}
      <a href="{{ route('seller.orders.index') }}"
          class="text-slate-600 hover:text-[var(--primary)] text-sm">
          ← Kembali ke Orderan
      </a>

      {{-- Form Update --}}
      <form method="POST" action="{{ route('seller.orders.updateStatus', $order->id) }}"
            class="flex items-center gap-2">
          @csrf

          <select name="status" class="border rounded px-2 py-1 text-sm">
              <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
              <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Dimasak</option>
              <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Diantar</option>
              <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
              <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batal</option>
          </select>

          <button class="px-3 py-1 bg-[var(--primary)] text-white rounded text-sm hover:bg-red-700">
              Update Status
          </button>
      </form>

  </div>
</div>
@endsection
