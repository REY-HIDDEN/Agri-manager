<?php

namespace Tests\Feature\Controllers;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get('/reports');
        $response->assertRedirect('/login');
    }

    public function test_reports_index_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get('/reports');
        $response->assertStatus(200);
    }

    public function test_daily_sales_report(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 50.00,
            'sale_date' => now()->toDateString(),
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->get('/reports/daily-sales?date='.now()->toDateString());
        $response->assertStatus(200);
        $response->assertViewHas('totalSales', 50.00);
        $response->assertViewHas('transactionCount', 1);
    }

    public function test_daily_sales_defaults_to_today(): void
    {
        $response = $this->actingAs($this->user)->get('/reports/daily-sales');
        $response->assertStatus(200);
        $response->assertViewHas('date', now()->toDateString());
    }

    public function test_monthly_sales_report(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 200.00,
            'sale_date' => now()->toDateString(),
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->get('/reports/monthly-sales?month='.now()->format('Y-m'));
        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 200.00);
        $response->assertViewHas('transactionCount', 1);
    }

    public function test_products_report(): void
    {
        Product::create(['name' => 'Rice', 'quantity' => 100, 'buying_price' => 2.00, 'selling_price' => 3.50]);

        $response = $this->actingAs($this->user)->get('/reports/products');
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_buyers_report(): void
    {
        Buyer::create(['name' => 'Buyer A', 'phone' => '+250788000000']);

        $response = $this->actingAs($this->user)->get('/reports/buyers');
        $response->assertStatus(200);
        $response->assertViewHas('buyers');
    }

    public function test_buyers_report_can_be_searched(): void
    {
        Buyer::create(['name' => 'Alice', 'phone' => '+250788000000']);
        Buyer::create(['name' => 'Bob', 'phone' => '+250788111111']);

        $response = $this->actingAs($this->user)->get('/reports/buyers?search=Alice');
        $response->assertStatus(200);
    }

    public function test_buyer_history_report(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);

        $response = $this->actingAs($this->user)->get("/reports/buyers/{$buyer->id}/history");
        $response->assertStatus(200);
        $response->assertViewHas('buyer');
    }

    public function test_profit_report(): void
    {
        $product = Product::create([
            'name' => 'Rice',
            'quantity' => 100,
            'buying_price' => 2.00,
            'selling_price' => 3.50,
        ]);

        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 35.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 3.50,
            'subtotal' => 35.00,
        ]);

        $response = $this->actingAs($this->user)->get('/reports/profit');
        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 35.00);
        $response->assertViewHas('netProfit', 15.00);
        $response->assertViewHas('profitMargin');
    }

    public function test_profit_report_with_no_sales(): void
    {
        $response = $this->actingAs($this->user)->get('/reports/profit');
        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 0);
        $response->assertViewHas('profitMargin', 0);
    }
}
