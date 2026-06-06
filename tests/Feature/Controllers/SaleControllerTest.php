<?php

namespace Tests\Feature\Controllers;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $salesOfficer;

    private Buyer $buyer;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->salesOfficer = User::factory()->create(['role' => 'sales_officer']);
        $this->buyer = Buyer::create(['name' => 'Test Buyer', 'phone' => '+250788000000']);
        $this->product = Product::create([
            'name' => 'Rice',
            'quantity' => 100,
            'buying_price' => 2.00,
            'selling_price' => 3.50,
        ]);
    }

    public function test_guest_cannot_access_sales(): void
    {
        $response = $this->get('/sales');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_sales(): void
    {
        $response = $this->actingAs($this->salesOfficer)->get('/sales');
        $response->assertStatus(200);
    }

    public function test_can_access_create_sale_form(): void
    {
        $response = $this->actingAs($this->salesOfficer)->get('/sales/create');
        $response->assertStatus(200);
    }

    public function test_can_store_sale_and_stock_is_decremented(): void
    {
        $response = $this->actingAs($this->salesOfficer)->post('/sales', [
            'buyer_id' => $this->buyer->id,
            'sale_date' => '2024-06-15',
            'payment_status' => 'paid',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 10],
            ],
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertDatabaseHas('sales', ['buyer_id' => $this->buyer->id, 'payment_status' => 'paid']);
        $this->assertEquals(90, $this->product->fresh()->quantity);

        $sale = Sale::first();
        $this->assertEquals(35.00, $sale->total_amount);
    }

    public function test_store_sale_fails_when_insufficient_stock(): void
    {
        $response = $this->actingAs($this->salesOfficer)->post('/sales', [
            'buyer_id' => $this->buyer->id,
            'sale_date' => '2024-06-15',
            'payment_status' => 'paid',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 999],
            ],
        ]);

        $response->assertSessionHasErrors('error');
        $this->assertEquals(100, $this->product->fresh()->quantity);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->salesOfficer)->post('/sales', []);
        $response->assertSessionHasErrors(['buyer_id', 'sale_date', 'payment_status', 'products']);
    }

    public function test_store_validates_payment_status(): void
    {
        $response = $this->actingAs($this->salesOfficer)->post('/sales', [
            'buyer_id' => $this->buyer->id,
            'sale_date' => '2024-06-15',
            'payment_status' => 'invalid',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
        ]);

        $response->assertSessionHasErrors('payment_status');
    }

    public function test_can_view_single_sale(): void
    {
        $sale = Sale::create([
            'buyer_id' => $this->buyer->id,
            'total_amount' => 35.00,
            'sale_date' => '2024-06-15',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->salesOfficer)->get("/sales/{$sale->id}");
        $response->assertStatus(200);
    }

    public function test_admin_can_update_payment_status(): void
    {
        $sale = Sale::create([
            'buyer_id' => $this->buyer->id,
            'total_amount' => 35.00,
            'sale_date' => '2024-06-15',
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->put("/sales/{$sale->id}", [
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertDatabaseHas('sales', ['id' => $sale->id, 'payment_status' => 'paid']);
    }

    public function test_admin_can_delete_sale_and_stock_is_restored(): void
    {
        $this->actingAs($this->admin)->post('/sales', [
            'buyer_id' => $this->buyer->id,
            'sale_date' => '2024-06-15',
            'payment_status' => 'paid',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 10],
            ],
        ]);

        $this->assertEquals(90, $this->product->fresh()->quantity);

        $sale = Sale::first();
        $response = $this->actingAs($this->admin)->delete("/sales/{$sale->id}");

        $response->assertRedirect(route('sales.index'));
        $this->assertEquals(100, $this->product->fresh()->quantity);
    }

    public function test_sales_officer_cannot_delete_sale(): void
    {
        $sale = Sale::create([
            'buyer_id' => $this->buyer->id,
            'total_amount' => 35.00,
            'sale_date' => '2024-06-15',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->salesOfficer)->delete("/sales/{$sale->id}");
        $response->assertStatus(403);
    }

    public function test_sales_can_be_filtered_by_payment_status(): void
    {
        Sale::create(['buyer_id' => $this->buyer->id, 'total_amount' => 10, 'sale_date' => now(), 'payment_status' => 'paid']);
        Sale::create(['buyer_id' => $this->buyer->id, 'total_amount' => 20, 'sale_date' => now(), 'payment_status' => 'pending']);

        $response = $this->actingAs($this->admin)->get('/sales?status=paid');
        $response->assertStatus(200);
    }

    public function test_store_sale_with_multiple_products(): void
    {
        $product2 = Product::create([
            'name' => 'Beans',
            'quantity' => 50,
            'buying_price' => 1.00,
            'selling_price' => 2.00,
        ]);

        $response = $this->actingAs($this->salesOfficer)->post('/sales', [
            'buyer_id' => $this->buyer->id,
            'sale_date' => '2024-06-15',
            'payment_status' => 'partial',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 5],
                ['product_id' => $product2->id, 'quantity' => 3],
            ],
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertEquals(95, $this->product->fresh()->quantity);
        $this->assertEquals(47, $product2->fresh()->quantity);

        $sale = Sale::first();
        $this->assertEquals(23.50, $sale->total_amount);
        $this->assertCount(2, $sale->saleDetails);
    }
}
