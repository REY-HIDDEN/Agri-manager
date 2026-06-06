<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalProducts = Product::count();
            $totalBuyers = Buyer::count();
            $totalSales = Sale::count();
            $totalRevenue = Sale::sum('total_amount');
            $pendingDeliveries = Delivery::where('status', '!=', 'delivered')->count();

            // Profit calculation
            $totalCost = DB::table('sale_details')
                ->join('products', 'sale_details.product_id', '=', 'products.id')
                ->selectRaw('SUM(sale_details.quantity * products.buying_price) as total_cost')
                ->value('total_cost') ?? 0;

            $totalProfit = $totalRevenue - $totalCost;

            // Sales chart data (last 7 days)
            $salesChart = Sale::selectRaw('DATE(sale_date) as date, SUM(total_amount) as total')
                ->where('sale_date', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Revenue chart data (last 6 months)
            $revenueChart = Sale::selectRaw("strftime('%Y-%m', sale_date) as month, SUM(total_amount) as total")
                ->where('sale_date', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Products with low stock
            $lowStockProducts = Product::where('quantity', '<', 10)->orderBy('quantity')->take(5)->get();

            // Stock chart data (top 10 products by quantity)
            $stockChart = Product::orderBy('quantity', 'desc')->take(10)->get(['name', 'quantity']);
        } catch (\Exception $e) {
            Log::error('Dashboard failed to load metrics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('dashboard', [
                'totalProducts' => 0,
                'totalBuyers' => 0,
                'totalSales' => 0,
                'totalRevenue' => 0,
                'totalCost' => 0,
                'totalProfit' => 0,
                'pendingDeliveries' => 0,
                'salesChart' => collect(),
                'revenueChart' => collect(),
                'lowStockProducts' => collect(),
                'stockChart' => collect(),
            ])->withErrors(['error' => 'Some dashboard data could not be loaded. Please try again later.']);
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalBuyers',
            'totalSales',
            'totalRevenue',
            'totalCost',
            'totalProfit',
            'pendingDeliveries',
            'salesChart',
            'revenueChart',
            'lowStockProducts',
            'stockChart'
        ));
    }
}
