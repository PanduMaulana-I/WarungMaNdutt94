<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ReportApiController extends Controller
{
    /**
     * Mengambil data total penjualan per hari (7 hari terakhir)
     * untuk ditampilkan pada grafik di dashboard penjual.
     */
    public function salesData(): JsonResponse
    {
        // Rentang waktu: 7 hari terakhir
        $from = Carbon::now()->subDays(6)->startOfDay();
        $to = Carbon::now()->endOfDay();

        $data = [];

        // Loop per hari dan ambil total transaksi per tanggal
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            $sum = Order::whereDate('created_at', $date->toDateString())
                ->sum('total');

            $data[] = [
                'date' => $date->translatedFormat('d M'), // contoh: "01 Nov"
                'total' => (float) $sum,
            ];
        }

        return response()->json($data);
    }
}
