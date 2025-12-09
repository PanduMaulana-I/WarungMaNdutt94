<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerDashboardController extends Controller
{
    public function index()
    {
        // range waktu 7 hari terakhir (sinkron dengan laporan)
        $start = Carbon::now()->subDays(6)->startOfDay();
        $end   = Carbon::now()->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | 💰 TOTAL PENDAPATAN — hanya pesanan yang sudah completed
        |--------------------------------------------------------------------------
        */
        $totalRevenue = DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | 🧾 TOTAL PESANAN — hanya completed (biar sama dengan laporan)
        |--------------------------------------------------------------------------
        */
        $totalOrders = DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 🍜 TOTAL MENU AKTIF
        |--------------------------------------------------------------------------
        | Tidak usah filter user_id karena warung cuma 1 seller.
        */
        $activeMenus = DB::table('menus')
            ->where('is_available', 1)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 📈 DATA GRAFIK PENJUALAN (7 hari terakhir)
        |--------------------------------------------------------------------------
        */
        $salesData = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format tanggal untuk chart
        $chartLabels = $salesData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartValues = $salesData->pluck('total');

        // Jika tidak ada data, isi dummy biar chart tetap tampil
        if ($chartLabels->isEmpty()) {
            $chartLabels = collect([Carbon::now()->format('d M')]);
            $chartValues = collect([0]);
        }

        /*
        |--------------------------------------------------------------------------
        | 📤 KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */
        return view('seller.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'activeMenus',
            'chartLabels',
            'chartValues'
        ));
    }
}
