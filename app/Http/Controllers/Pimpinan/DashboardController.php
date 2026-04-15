<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\TransOrder;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter Month
        $selectedMonth = $request->input('month', Carbon::now()->format('m'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));

        // Stats
        $stats = [
            'total_income' => TransOrder::where('order_status', 1)
                                ->whereMonth('order_date', $selectedMonth)
                                ->whereYear('order_date', $selectedYear)
                                ->sum('total'),
                                
            'completed_orders' => TransOrder::where('order_status', 1)
                                ->whereMonth('order_date', $selectedMonth)
                                ->whereYear('order_date', $selectedYear)
                                ->count(),
                                
            'pending_orders' => TransOrder::where('order_status', 0)
                                ->whereMonth('order_date', $selectedMonth)
                                ->whereYear('order_date', $selectedYear)
                                ->count(),
                                
            'total_customers' => Customer::count(),
        ];

        // Laporan Transaksi Selesai
        $reports = TransOrder::with(['customer', 'pickup'])
                    ->where('order_status', 1)
                    ->whereMonth('order_date', $selectedMonth)
                    ->whereYear('order_date', $selectedYear)
                    ->orderBy('order_end_date', 'desc')
                    ->paginate(15);

        return view('pimpinan.dashboard', compact('stats', 'reports', 'selectedMonth', 'selectedYear'));
    }
}
