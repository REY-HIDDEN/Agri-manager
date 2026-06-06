<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;

class ProfitCalculator
{
    public function totalRevenue(): float
    {
        return (float) Sale::sum('total_amount');
    }

    public function totalCost(): float
    {
        return (float) (SaleDetail::join('products', 'sale_details.product_id', '=', 'products.id')
            ->selectRaw('COALESCE(SUM(sale_details.quantity * products.buying_price), 0) as total_cost')
            ->value('total_cost') ?? 0);
    }

    public function netProfit(): float
    {
        return $this->totalRevenue() - $this->totalCost();
    }

    public function profitMargin(): float
    {
        $revenue = $this->totalRevenue();

        return $revenue > 0 ? round(($this->netProfit() / $revenue) * 100, 2) : 0;
    }

    public function monthlySummary(int $months = 12): \Illuminate\Support\Collection
    {
        return Sale::selectRaw(
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
            ->take($months)
            ->get()
            ->map(function ($item) {
                $item->profit = $item->revenue - $item->cost;
                return $item;
            });
    }
}
