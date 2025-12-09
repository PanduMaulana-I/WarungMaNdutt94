@extends('layouts.seller')

@section('content')

{{-- === STYLE KHUSUS DASHBOARD === --}}
<style>
    :root {
        --color-primary: #dc2626;   /* merah */
        --color-secondary: #facc15; /* kuning */
        --color-bg: #E6DCD2;        /* krem */
    }

    body {
        background-color: var(--color-bg) !important;
        overflow-x: hidden;
        font-family: "Inter", sans-serif;
    }

    .bg-wave {
        position: relative;
    }

    /* Gelombang background lembut konsisten dengan halaman lain */
    .bg-wave::before {
        content: "";
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        background-image:
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' fill-opacity='0.72' d='M0,224L48,229.3C96,235,192,245,288,229.3C384,213,480,171,576,170.7C672,171,768,213,864,240C960,267,1056,277,1152,256C1248,235,1344,181,1392,154.7L1440,128V0H0Z'/%3E%3C/svg%3E");
        background-size: cover;
        background-repeat: no-repeat;
        opacity: .75;
        z-index: -1;
    }

    .title-font {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
    }

    .card-shadow {
        box-shadow:
            0 4px 12px rgba(0,0,0,0.08),
            0 2px 4px rgba(0,0,0,0.04);
    }

    .chart-wrapper {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 8px;
    }
</style>

<div class="p-4 md:p-6 bg-wave">

    <div class="max-w-screen-xl mx-auto">

        {{-- Judul Halaman --}}
        <h1 class="text-3xl md:text-4xl title-font text-[var(--color-primary)] mb-8 drop-shadow-sm">
            Dashboard Penjual
        </h1>

        {{-- === KARTU RINGKASAN === --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

            <div class="bg-white p-6 rounded-2xl card-shadow border-b-4 border-[var(--color-secondary)]">
                <h2 class="text-gray-600 text-xs font-medium uppercase tracking-wide">
                    Total Pendapatan
                </h2>
                <p class="text-3xl font-bold text-[var(--color-primary)] mt-3">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl card-shadow border-b-4 border-[var(--color-secondary)]">
                <h2 class="text-gray-600 text-xs font-medium uppercase tracking-wide">
                    Jumlah Pesanan
                </h2>
                <p class="text-3xl font-bold text-[var(--color-primary)] mt-3">
                    {{ $totalOrders }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl card-shadow border-b-4 border-[var(--color-secondary)]">
                <h2 class="text-gray-600 text-xs font-medium uppercase tracking-wide">
                    Menu Aktif
                </h2>
                <p class="text-3xl font-bold text-[var(--color-primary)] mt-3">
                    {{ $activeMenus }}
                </p>
            </div>

        </div>

        {{-- === GRAFIK PENJUALAN === --}}
        <div class="bg-white p-8 rounded-3xl card-shadow border border-yellow-300/40">
            <h2 class="text-xl title-font text-gray-900 mb-6">
                Grafik Penjualan (7 Hari Terakhir)
            </h2>

            <div class="chart-wrapper">
                <canvas id="salesChart" height="130"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- === CHART.JS === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: "Pendapatan Harian",
                data: {!! json_encode($chartValues) !!},

                borderColor: "#dc2626",
                backgroundColor: "rgba(220, 38, 38, 0.18)",
                borderWidth: 3,
                tension: 0.35,
                fill: true,

                pointRadius: 5,
                pointBorderWidth: 2,
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#dc2626",
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: "#444",
                        callback: value => "Rp " + value.toLocaleString("id-ID")
                    },
                    grid: { color: "rgba(0,0,0,0.08)" }
                },
                x: {
                    ticks: { color: "#444" },
                    grid: { display: false }
                }
            },

            plugins: {
                legend: {
                    labels: { color: "#333", font: { weight: 600 } }
                }
            }
        }
    });
</script>

@endsection
