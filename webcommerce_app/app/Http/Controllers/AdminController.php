<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', $today)->count();

        $totalRevenue = Order::where('transaction_status', 'paid')->sum('total');
        $todayRevenue = Order::where('transaction_status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('total');

        $totalCustomers = Customer::count();
        $totalMenus = Menu::count();

        $pendingOrders = Order::where('status', 'pending')->count();
        $processOrders = Order::where('status', 'process')->count();
        $doneOrders = Order::where('status', 'done')->count();

        $waitingPayments = Order::where('transaction_status', 'waiting')->count();
        $paidOrders = Order::where('transaction_status', 'paid')->count();

        $latestOrders = Order::with('customer')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $bestMenus = OrderItem::with('menu')
            ->selectRaw('menu_id, SUM(qty) as total_qty, SUM(subtotal) as total_sales')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // CHART
        $salesChart = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total_sales')
        )
            ->where('transaction_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        $salesPerMonth = [];
        $monthLabels = [];

        foreach ($months as $key => $month) {
            $monthLabels[] = $month;

            $salesPerMonth[] = (int) optional(
                $salesChart->firstWhere('month', $key)
            )->total_sales ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'todayOrders',
            'totalRevenue',
            'todayRevenue',
            'totalCustomers',
            'totalMenus',
            'pendingOrders',
            'processOrders',
            'doneOrders',
            'waitingPayments',
            'paidOrders',
            'latestOrders',
            'bestMenus',
            'monthLabels',
            'salesPerMonth',
        ));
    }
}
