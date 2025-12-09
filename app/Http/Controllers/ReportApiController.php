<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Langsung arahkan ke halaman laporan utama
        return redirect()->route('seller.reports.sales');
    }

    public function sales()
    {
        // Data dummy buat testing
        $dates = [];
        $sales = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->format('d M');
            $sales[] = rand(0, 50000);
        }

        return view('reports.index', [
            'dates' => $dates,
            'sales' => $sales,
            'totalRevenue' => array_sum($sales),
            'totalOrders' => rand(5, 30),
            'totalItems' => rand(10, 50),
        ]);
    }
}
