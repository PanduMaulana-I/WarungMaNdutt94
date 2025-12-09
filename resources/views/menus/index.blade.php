@extends('layouts.seller')

@section('title', 'Kelola Menu')

@section('content')

<style>
    .brand-title {
        color: var(--primary);
        font-weight: 700;
    }
</style>

<div class="mb-10">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl brand-title tracking-tight">Kelola Menu</h1>

        <a href="{{ route('seller.dashboard') }}"
           class="px-4 py-2.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition shadow-sm">
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
      <div class="mb-5 bg-green-100 text-green-700 p-3 rounded-lg border border-green-200 shadow-sm">
        {{ session('success') }}
      </div>
    @endif

    {{-- Tombol tambah menu --}}
    <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-200 mb-6">
      <a href="{{ route('seller.menus.create') }}"
         class="px-5 py-2.5 bg-[var(--primary)] text-white rounded-lg hover:bg-red-700 transition font-medium shadow">
        Tambah Menu Baru
      </a>
    </div>

    {{-- Tabel Menu --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-slate-100 border-b border-slate-200">
          <tr>
            <th class="px-5 py-3 text-sm font-semibold text-slate-700">Nama Menu</th>
            <th class="px-5 py-3 text-sm font-semibold text-slate-700">Harga</th>
            <th class="px-5 py-3 text-sm font-semibold text-slate-700">Deskripsi</th>
            <th class="px-5 py-3 text-sm font-semibold text-center text-slate-700">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
          @forelse($menus as $menu)
          <tr class="hover:bg-slate-50 transition">
            <td class="px-5 py-3 font-medium text-slate-800">
                {{ $menu->name }}
            </td>

            <td class="px-5 py-3 text-slate-700">
                Rp {{ number_format($menu->price, 0, ',', '.') }}
            </td>

            <td class="px-5 py-3 text-slate-600 max-w-xs">
                {{ $menu->description }}
            </td>

            <td class="px-5 py-3 text-center">
                <a href="{{ route('seller.menus.edit', $menu->id) }}"
                   class="text-[var(--primary)] font-medium hover:underline mr-3">
                    Edit
                </a>

                <form method="POST" action="{{ route('seller.menus.destroy', $menu->id) }}"
                      class="inline-block"
                      onsubmit="return confirm('Hapus menu ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-rose-600 font-medium hover:underline">
                        Hapus
                    </button>
                </form>
            </td>
          </tr>

          @empty
          <tr>
            <td colspan="4" class="px-5 py-8 text-center text-slate-500">
              Belum ada menu ditambahkan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

</div>

@endsection
