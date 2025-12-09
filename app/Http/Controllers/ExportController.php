<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Carbon\Carbon;
use App\Exports\ArrayExport;

class ExportController extends Controller
{
    /**
     * ===================================
     *   EXPORT EXCEL (PAKE JSON DETAILS)
     * ===================================
     */
public function exportExcel(Request $request)
{
    $from = $request->query('from')
        ? Carbon::parse($request->query('from'))->startOfDay()
        : Carbon::now()->subDays(7)->startOfDay();

    $to = $request->query('to')
        ? Carbon::parse($request->query('to'))->endOfDay()
        : Carbon::now()->endOfDay();

    // Ambil semua order dalam range
    $orders = Order::whereBetween('created_at', [$from, $to])
        ->where('status', '!=', 'cancelled')
        ->get();

    $rows = [];

    foreach ($orders as $o) {

        // ambil JSON
        $details = json_decode($o->details, true);

        if (!$details) continue;

        foreach ($details as $item) {

            $rows[] = [
                'order_number' => $o->order_number,
                'date'         => $o->created_at->format('Y-m-d H:i'),
                'customer'     => $o->customer_name ?? '-',
                'status'       => ucfirst($o->status),   // ✅ STATUS BARU
                'menu'         => $item['menu'] ?? '-',
                'qty'          => $item['quantity'] ?? 0,
                'price'        => ($item['subtotal'] ?? 0) / max(1, $item['quantity'] ?? 1),
                'subtotal'     => $item['subtotal'] ?? 0,
            ];
        }
    }

    return Excel::download(new ArrayExport($rows), 'sales.xlsx');
}


    /**
     * ===================================
     *   EXPORT PDF (TETAP, TIDAK DIUBAH)
     * ===================================
     */
    public function exportPdf(Request $request)
{
    $from = $request->query('from')
        ? Carbon::parse($request->query('from'))->startOfDay()
        : Carbon::now()->subDays(7)->startOfDay();

    $to = $request->query('to')
        ? Carbon::parse($request->query('to'))->endOfDay()
        : Carbon::now()->endOfDay();

    // Ambil semua order dalam range
    $orders = Order::whereBetween('created_at', [$from, $to])
        ->where('status', '!=', 'cancelled')
        ->get();

    // ===============================
    //  RAPIHKAN DATA DETAIL PER ITEM
    // ===============================
    $rows = [];

    foreach ($orders as $o) {
        $details = json_decode($o->details, true);

        if (!$details) continue;

        foreach ($details as $item) {

            $rows[] = [
                'order_number' => $o->order_number,
                'date'         => $o->created_at->format('Y-m-d H:i'),
                'customer'     => $o->customer_name ?? '-',
                'status'       => ucfirst($o->status),
                'menu'         => $item['menu'] ?? '-',
                'qty'          => $item['quantity'] ?? 0,
                'price'        => ($item['subtotal'] ?? 0) / max(1, $item['quantity'] ?? 1),
                'subtotal'     => $item['subtotal'] ?? 0,
            ];
        }
    }

    // Kirim rows ke PDF
    $pdf = PDF::loadView('seller.reports.pdf', [
        'rows' => $rows,
        'from' => $from,
        'to'   => $to
    ]);

    return $pdf->download('sales.pdf');
}

}
