<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\ProfitCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function dailySales(Request $request)
    {
        $date = $request->date ?: now()->toDateString();

        $sales = Sale::with('buyer', 'saleDetails.product')
            ->whereDate('sale_date', $date)
            ->get();

        $totalSales = $sales->sum('total_amount');
        $transactionCount = $sales->count();

        return view('reports.daily-sales', compact('sales', 'date', 'totalSales', 'transactionCount'));
    }

    public function monthlySales(Request $request)
    {
        $month = $request->month ?: now()->format('Y-m');

        $sales = Sale::with('buyer')
            ->whereRaw("strftime('%Y-%m', sale_date) = ?", [$month])
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $transactionCount = $sales->count();

        return view('reports.monthly-sales', compact('sales', 'month', 'totalRevenue', 'transactionCount'));
    }

    public function products()
    {
        $products = Product::withCount(['saleDetails as sold_quantity' => function ($q) {
            $q->select(DB::raw('COALESCE(SUM(quantity), 0)'));
        }])->orderBy('name')->get();

        return view('reports.products', compact('products'));
    }

    public function buyers(Request $request)
    {
        $query = Buyer::withCount('sales')
            ->withSum('sales', 'total_amount');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $buyers = $query->orderBy('name')->paginate(10);
        return view('reports.buyers', compact('buyers'));
    }

    public function buyerHistory(Buyer $buyer)
    {
        $buyer->load('sales.saleDetails.product', 'sales.delivery');
        return view('reports.buyer-history', compact('buyer'));
    }

    public function profit(ProfitCalculator $profitCalculator)
    {
        $totalRevenue = $profitCalculator->totalRevenue();
        $totalCost = $profitCalculator->totalCost();
        $netProfit = $profitCalculator->netProfit();
        $profitMargin = $profitCalculator->profitMargin();
        $monthlyData = $profitCalculator->monthlySummary();

        return view('reports.profit', compact('totalRevenue', 'totalCost', 'netProfit', 'profitMargin', 'monthlyData'));
    }
}
