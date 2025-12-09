@extends('layouts.buyer')

@section('title', $menu->name)

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
  <h1 class="text-2xl font-bold mb-2">{{ $menu->name }}</h1>
  <p class="text-gray-600 mb-4">{{ $menu->description }}</p>
  <p class="text-xl font-semibold text-indigo-600 mb-6">
    Rp {{ number_format($menu->price, 0, ',', '.') }}
  </p>

  <form action="{{ route('orders.store') }}" method="POST" class="space-y-3">
    @csrf
    <input type="hidden" name="menu_id" value="{{ $menu->id }}">

    <div>
      <label class="block text-sm text-gray-700">Nama Pembeli (opsional)</label>
      <input type="text" name="customer_name" class="border rounded w-full px-3 py-2">
    </div>

    <div>
      <label class="block text-sm text-gray-700">No HP (opsional)</label>
      <input type="text" name="customer_phone" class="border rounded w-full px-3 py-2">
    </div>

    <div>
      <label class="block text-sm text-gray-700">Jumlah</label>
      <input type="number" name="quantity" min="1" value="1" class="border rounded w-full px-3 py-2">
    </div>

    <button class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">
      Pesan Sekarang
    </button>
  </form>
</div>
@endsection
