<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $salesOfficer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->salesOfficer = User::factory()->create(['role' => 'sales_officer']);
    }

    public function test_guest_cannot_access_products(): void
    {
        $response = $this->get('/products');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_products(): void
    {
        Product::create([
            'name' => 'Rice',
            'quantity' => 100,
            'buying_price' => 2.00,
            'selling_price' => 3.50,
        ]);

        $response = $this->actingAs($this->salesOfficer)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Rice');
    }

    public function test_products_can_be_searched(): void
    {
        Product::create(['name' => 'Organic Rice', 'quantity' => 100, 'buying_price' => 2.00, 'selling_price' => 3.50]);
        Product::create(['name' => 'Maize Flour', 'quantity' => 50, 'buying_price' => 1.50, 'selling_price' => 2.50]);

        $response = $this->actingAs($this->admin)->get('/products?search=Rice');
        $response->assertStatus(200);
        $response->assertSee('Organic Rice');
        $response->assertDontSee('Maize Flour');
    }

    public function test_products_can_be_filtered_by_low_stock(): void
    {
        Product::create(['name' => 'Low Stock Item', 'quantity' => 5, 'buying_price' => 1.00, 'selling_price' => 2.00]);
        Product::create(['name' => 'Well Stocked', 'quantity' => 200, 'buying_price' => 1.00, 'selling_price' => 2.00]);

        $response = $this->actingAs($this->admin)->get('/products?low_stock=1');
        $response->assertStatus(200);
        $response->assertSee('Low Stock Item');
        $response->assertDontSee('Well Stocked');
    }

    public function test_admin_can_access_create_product_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_sales_officer_cannot_create_product(): void
    {
        $response = $this->actingAs($this->salesOfficer)->get('/products/create');
        $response->assertStatus(403);
    }

    public function test_admin_can_store_product(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => 'New Product',
            'quantity' => 50,
            'buying_price' => 5.00,
            'selling_price' => 8.00,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', []);
        $response->assertSessionHasErrors(['name', 'quantity', 'buying_price', 'selling_price']);
    }

    public function test_store_validates_unique_name(): void
    {
        Product::create(['name' => 'Existing', 'quantity' => 10, 'buying_price' => 1.00, 'selling_price' => 2.00]);

        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => 'Existing',
            'quantity' => 5,
            'buying_price' => 1.00,
            'selling_price' => 2.00,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::create(['name' => 'Old Name', 'quantity' => 10, 'buying_price' => 1.00, 'selling_price' => 2.00]);

        $response = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'name' => 'New Name',
            'quantity' => 20,
            'buying_price' => 1.50,
            'selling_price' => 2.50,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New Name']);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::create(['name' => 'To Delete', 'quantity' => 10, 'buying_price' => 1.00, 'selling_price' => 2.00]);

        $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_sales_officer_cannot_delete_product(): void
    {
        $product = Product::create(['name' => 'Protected', 'quantity' => 10, 'buying_price' => 1.00, 'selling_price' => 2.00]);

        $response = $this->actingAs($this->salesOfficer)->delete("/products/{$product->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_products_can_be_filtered_by_price_range(): void
    {
        Product::create(['name' => 'Cheap', 'quantity' => 10, 'buying_price' => 0.50, 'selling_price' => 1.00]);
        Product::create(['name' => 'Expensive', 'quantity' => 10, 'buying_price' => 5.00, 'selling_price' => 10.00]);

        $response = $this->actingAs($this->admin)->get('/products?price_min=5&price_max=15');
        $response->assertStatus(200);
        $response->assertSee('Expensive');
        $response->assertDontSee('Cheap');
    }
}
