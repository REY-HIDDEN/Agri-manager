<?php

namespace Tests\Feature\Controllers;

use App\Models\Buyer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_correct_metrics(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'Rice',
            'quantity' => 100,
            'buying_price' => 2.00,
            'selling_price' => 3.50,
        ]);

        $buyer = Buyer::create(['name' => 'Buyer A', 'phone' => '+250788000000']);

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

        Delivery::create([
            'sale_id' => $sale->id,
            'delivery_date' => now(),
            'destination' => 'Kigali',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas('totalProducts', 1);
        $response->assertViewHas('totalBuyers', 1);
        $response->assertViewHas('totalSales', 1);
        $response->assertViewHas('pendingDeliveries', 1);
    }

    public function test_dashboard_displays_low_stock_products(): void
    {
        $user = User::factory()->create();

        Product::create(['name' => 'Low Stock', 'quantity' => 3, 'buying_price' => 1.00, 'selling_price' => 2.00]);
        Product::create(['name' => 'Normal Stock', 'quantity' => 100, 'buying_price' => 1.00, 'selling_price' => 2.00]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas('lowStockProducts');

        $lowStock = $response->viewData('lowStockProducts');
        $this->assertCount(1, $lowStock);
        $this->assertEquals('Low Stock', $lowStock->first()->name);
    }

    public function test_dashboard_calculates_profit(): void
    {
        $user = User::factory()->create();

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

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertViewHas('totalRevenue', 35.00);
        $response->assertViewHas('totalCost', 20.00);
        $response->assertViewHas('totalProfit', 15.00);
    }
}
