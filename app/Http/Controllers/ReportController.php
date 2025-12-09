<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * ================================
     *   HALAMAN LAPORAN PENJUALAN
     *   (MENGIKUTI DASHBOARD)
     *   -> hanya menghitung COMPLETED
     * ================================
     */
    public function index(Request $req)
    {
        /**
         * Gunakan startOfDay & endOfDay
         * agar tanggal dihitung penuh.
         */
        $from = $req->from
            ? Carbon::parse($req->from)->startOfDay()
            : now()->subDays(7)->startOfDay();

        $to = $req->to
            ? Carbon::parse($req->to)->endOfDay()
            : now()->endOfDay();

        // ===============================
        // Ambil order COMPLETED saja
        // ===============================
        $orders = Order::whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')   // <-- ⚡ FOLLOW DASHBOARD
            ->get();

        // Summary
        $totalRevenue = $orders->sum('total_price');
        $totalOrders  = $orders->count();

        // Hitung total item terjual
        $totalItems = 0;
        foreach ($orders as $o) {
            $details = json_decode($o->details, true);

            if (is_array($details)) {
                foreach ($details as $d) {
                    $totalItems += $d['quantity'] ?? 0;
                }
            }
        }

        // ===============================
        // REKAP PENJUALAN PER MENU
        // ===============================
        $menuSales = Order::whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')   // <-- ⚡ SAMAKAN
            ->selectRaw("
                JSON_UNQUOTE(JSON_EXTRACT(details, '$[0].menu')) as name,
                SUM(JSON_EXTRACT(details, '$[0].quantity')) as qty,
                SUM(JSON_EXTRACT(details, '$[0].subtotal')) as revenue
            ")
            ->groupBy('name')
            ->get();

        // ===============================
        //   GRAFIK PENJUALAN 7 HARI
        // ===============================
        $dates = collect();
        $sales = collect();

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();

            $dates->push(Carbon::parse($day)->format('d M'));

            // Hanya completed
            $dayRevenue = Order::whereDate('created_at', $day)
                ->where('status', 'completed')   // <-- ⚡ SAMAKAN
                ->sum('total_price');

            $sales->push($dayRevenue);
        }

        return view('seller.reports.index', [
            'totalRevenue' => $totalRevenue,
            'totalOrders'  => $totalOrders,
            'totalItems'   => $totalItems,
            'menuSales'    => $menuSales,
            'dates'        => $dates,
            'sales'        => $sales,
            'from'         => $from,
            'to'           => $to,
        ]);
    }
}
