@extends('layouts.seller')

@section('title', 'Laporan Penjualan')

@section('content')

<style>
    :root {
        --primary: #dc2626;
        --secondary: #facc15;
        --soft: #E6DCD2;
    }
</style>

{{-- HEADER --}}
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-[var(--primary)] tracking-tight">Laporan Penjualan</h1>

    <a href="{{ route('seller.dashboard') }}"
       class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition font-medium">
       Kembali
    </a>
</div>

{{-- FILTER PERIODE --}}
<form method="GET" class="flex flex-wrap md:flex-nowrap gap-4 mb-8 items-end">

    <div class="flex flex-col">
        <label class="text-sm font-medium text-slate-700 mb-1">Dari Tanggal</label>
        <input type="date" name="from" value="{{ request('from') ?? $from->toDateString() }}"
               class="border rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-[var(--primary)]">
    </div>

    <div class="flex flex-col">
        <label class="text-sm font-medium text-slate-700 mb-1">Sampai Tanggal</label>
        <input type="date" name="to" value="{{ request('to') ?? $to->toDateString() }}"
               class="border rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-[var(--primary)]">
    </div>

    <button class="px-5 py-2 bg-[var(--primary)] text-white rounded-lg shadow hover:bg-red-700 transition h-[42px]">
        Tampilkan
    </button>
</form>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-white p-6 rounded-2xl shadow border-b-4 border-[var(--secondary)]">
        <h3 class="text-slate-500 text-sm font-medium">Total Pendapatan</h3>
        <p class="text-3xl font-bold text-[var(--primary)] mt-2">
            Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
        </p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow border-b-4 border-[var(--secondary)]">
        <h3 class="text-slate-500 text-sm font-medium">Jumlah Pesanan</h3>
        <p class="text-3xl font-bold text-[var(--primary)] mt-2">
            {{ $totalOrders ?? 0 }}
        </p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow border-b-4 border-[var(--secondary)]">
        <h3 class="text-slate-500 text-sm font-medium">Menu Terjual</h3>
        <p class="text-3xl font-bold text-[var(--primary)] mt-2">
            {{ $totalItems ?? 0 }}
        </p>
    </div>

</div>

{{-- CHART SECTION --}}
<div class="bg-white p-8 rounded-3xl shadow-xl border border-[var(--secondary)]/40 mb-12">
    <h2 class="text-xl font-bold text-slate-800 mb-6 tracking-tight">
        Grafik Penjualan (7 Hari Terakhir)
    </h2>

    <canvas id="salesChart" height="120"></canvas>
</div>

{{-- TABEL PENJUALAN PER MENU --}}
<div class="bg-white p-8 rounded-2xl shadow mb-12">
    <h2 class="text-xl font-semibold mb-4 text-slate-800">Penjualan per Menu</h2>

    <table class="w-full text-sm">
        <thead class="bg-slate-100 border-b">
            <tr>
                <th class="py-2 px-3 text-left">Menu</th>
                <th class="py-2 px-3 text-right">Qty</th>
                <th class="py-2 px-3 text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($menuSales as $m)
                <tr class="border-b hover:bg-slate-50 transition">
                    <td class="py-2 px-3">{{ $m->name }}</td>
                    <td class="py-2 px-3 text-right">{{ $m->qty }}</td>
                    <td class="py-2 px-3 text-right">
                        Rp {{ number_format($m->revenue, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-4 text-center text-slate-500">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- EXPORT BUTTONS --}}
<div class="flex items-center justify-between pb-20">

    <a href="{{ route('seller.dashboard') }}"
       class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition font-medium">
       Kembali ke Dashboard
    </a>

    <div class="flex gap-4">
        <a href="{{ route('seller.reports.export.excel') }}"
           class="px-5 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition shadow">
           Ekspor Excel
        </a>

        <a href="{{ route('seller.reports.export.pdf') }}"
           class="px-5 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition shadow">
           Ekspor PDF
        </a>
    </div>

</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dates ?? []) !!},
        datasets: [{
            label: 'Pendapatan Harian',
            data: {!! json_encode($sales ?? []) !!},
            borderColor: '#dc2626',
            backgroundColor: 'rgba(220,38,38,0.15)',
            borderWidth: 3,
            fill: true,
            tension: 0.3,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#dc2626',
            pointBorderWidth: 2,
            pointRadius: 4
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: (value) => "Rp " + value.toLocaleString("id-ID"),
                    color: "#444"
                }
            },
            x: {
                ticks: { color: "#444" }
            }
        },
        plugins: {
            legend: { labels: { color: "#333" } }
        }
    }
});
</script>

@endsection
