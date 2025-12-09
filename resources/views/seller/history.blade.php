@extends('layouts.seller')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">

  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <h2 class="text-2xl font-bold text-slate-800">Riwayat Transaksi</h2>

    <form method="GET" action="{{ route('riwayat.index') }}" class="flex items-center gap-2">
      <input type="date" name="tanggal" value="{{ request('tanggal') }}"
             class="border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-filter mr-2"></i>Filter
      </button>
      <a href="{{ route('riwayat.index') }}"
         class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300 transition">
        <i class="fas fa-rotate-left mr-2"></i>Reset
      </a>
    </form>
  </div>

  <div class="bg-white shadow-sm rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-100">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">No Order</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Pembeli</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Total</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Metode</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Status</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Tanggal</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse ($transactions as $t)
        <tr class="hover:bg-slate-50 transition">
          <td class="px-4 py-3 font-medium text-slate-700">{{ $t->order_number }}</td>
          <td class="px-4 py-3">{{ $t->customer_name ?? 'Pembeli Umum' }}</td>
          <td class="px-4 py-3">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
          <td class="px-4 py-3">
            @if($t->payment_method == 'qris')
              <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-md">QRIS</span>
            @elseif($t->payment_method == 'transfer')
              <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-md">Transfer</span>
            @else
              <span class="px-2 py-1 text-xs bg-slate-100 text-slate-700 rounded-md">Tunai</span>
            @endif
          </td>
          <td class="px-4 py-3">
            <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-md">
              {{ $t->status_label }}
            </span>
          </td>
          <td class="px-4 py-3 text-slate-600">{{ $t->formatted_date }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-4 py-6 text-center text-slate-500">
            <i class="fas fa-inbox fa-lg mb-2"></i>
            <div>Tidak ada transaksi ditemukan.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="p-4 border-t border-slate-100 flex justify-end">
      {{ $transactions->links() }}
    </div>
  </div>

</div>
@endsection
