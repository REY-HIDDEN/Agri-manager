<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
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

    public function profit()
    {
        // Total Revenue
        $totalRevenue = Sale::sum('total_amount');

        // Total Cost (buying_price * quantity sold)
        $totalCost = SaleDetail::join('products', 'sale_details.product_id', '=', 'products.id')
            ->selectRaw('COALESCE(SUM(sale_details.quantity * products.buying_price), 0) as total_cost')
            ->value('total_cost');

        $netProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) : 0;

        // Monthly breakdown
        $monthlyData = Sale::selectRaw(
            "strftime('%Y-%m', sale_date) as month,
             SUM(total_amount) as revenue,
             (SELECT COALESCE(SUM(sd.quantity * p.buying_price), 0)
              FROM sale_details sd
              JOIN products p ON p.id = sd.product_id
              JOIN sales s2 ON s2.id = sd.sale_id
              WHERE strftime('%Y-%m', s2.sale_date) = strftime('%Y-%m', sales.sale_date)
             ) as cost"
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->map(function ($item) {
                $item->profit = $item->revenue - $item->cost;
                return $item;
            });

        return view('reports.profit', compact('totalRevenue', 'totalCost', 'netProfit', 'profitMargin', 'monthlyData'));
    }
}
