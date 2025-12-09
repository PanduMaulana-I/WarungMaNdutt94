@extends('layouts.seller')

@section('title', 'Daftar Orderan')

@section('content')

<style>
    :root {
        --color-primary: #dc2626;
        --color-secondary: #facc15;
        --color-bg: #E6DCD2;
    }

    body {
        background-color: var(--color-bg) !important;
        font-family: "Inter", sans-serif !important;
    }

    .title-font {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
    }

    .card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        border-bottom: 4px solid var(--color-secondary);
        overflow-x: auto; /* penting untuk HP */
    }

    /* Agar tabel tidak pecah di layar kecil */
    table {
        min-width: 700px;
    }

    @media (max-width: 768px) {
        .filters-wrapper {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .filters-wrapper form {
            width: 100%;
            justify-content: space-between;
        }

        .filters-wrapper input {
            flex: 1;
        }
    }
</style>

<div class="p-6">

    <h1 class="text-3xl title-font text-[var(--color-primary)] mb-8">
        Daftar Orderan
    </h1>

    {{-- ==================== FILTER & SEARCH ==================== --}}
    <div class="flex justify-between items-center mb-6 filters-wrapper">

        {{-- FILTER TANGGAL --}}
        <form method="GET" class="flex items-center gap-3">
            <input type="date" name="date" value="{{ request('date') }}"
                class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[var(--color-primary)] focus:outline-none shadow-sm w-full md:w-auto">

            <button
                class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg font-medium hover:bg-red-700 transition">
                Filter
            </button>
        </form>

        {{-- SEARCH BOX --}}
        <form method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nomor order, pembeli, status..."
                class="px-3 py-2 border border-slate-300 rounded-lg w-full md:w-72">

            <button
                class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg hover:bg-red-700 transition">
                Cari
            </button>
        </form>

    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="mb-5 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ==================== TABEL ORDER ==================== --}}
    <div class="card">

        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b">
                <tr class="text-slate-700 text-sm font-semibold">
                    <th class="px-4 py-3">No Order</th>
                    <th class="px-4 py-3">Pembeli</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="orders-table" class="divide-y divide-slate-100">

                @forelse($orders as $order)
                    @php
                        $color = match($order->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'delivering' => 'bg-purple-100 text-purple-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-rose-100 text-rose-700',
                            default => 'bg-slate-200 text-slate-700',
                        };
                    @endphp

                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-semibold text-slate-800">#{{ $order->order_number }}</td>

                        <td class="px-4 py-3 text-slate-700">
                            {{ $order->customer_name ?? 'Pembeli Umum' }}
                        </td>

                        <td class="px-4 py-3 text-slate-800 font-medium">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-md {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $order->created_at->format('d M Y • H:i') }}
                        </td>

                        <td class="px-4 py-3 text-center whitespace-nowrap">

                            {{-- UPDATE STATUS --}}
                            <form method="POST" action="{{ route('seller.orders.updateStatus', $order->id) }}"
                                  class="inline-block">
                                @csrf

                                <select name="status"
                                    class="border border-slate-300 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-red-300 focus:outline-none">

                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Dimasak</option>
                                    <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Diantar</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batal</option>

                                </select>

                                <button
                                    class="ml-2 px-3 py-1 bg-[var(--color-primary)] text-white rounded-md text-sm hover:bg-red-700 transition">
                                    Ubah
                                </button>
                            </form>

                            <a href="{{ route('seller.orders.show', $order->id) }}"
                               class="ml-2 text-red-700 hover:underline text-sm font-medium">
                                Detail
                            </a>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-600">
                            Tidak ada pesanan masuk.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection


{{-- ==================== REALTIME NOTIFIKASI TANPA AUTO REFRESH ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let lastOrderCount = {{ count($orders) }};

function checkNewOrders() {
    fetch("/api/orders/latest")
        .then(res => res.json())
        .then(data => {
            if (data.length > lastOrderCount) {
                Swal.fire({
                    icon: "info",
                    title: "Pesanan Baru!",
                    text: "Ada pesanan baru masuk.",
                    timer: 2000,
                    showConfirmButton: false
                });

                new Audio("/notif.mp3").play();
                lastOrderCount = data.length;
            }
        });
}
</script>
