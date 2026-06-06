<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use App\Services\ProfitCalculator;

class DashboardController extends Controller
{
    public function index(ProfitCalculator $profitCalculator)
    {
        $totalProducts = Product::count();
        $totalBuyers = Buyer::count();
        $totalSales = Sale::count();
        $totalRevenue = $profitCalculator->totalRevenue();
        $pendingDeliveries = Delivery::where('status', '!=', 'delivered')->count();

        $totalCost = $profitCalculator->totalCost();
        $totalProfit = $profitCalculator->netProfit();

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
