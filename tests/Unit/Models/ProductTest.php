<?php

namespace Tests\Unit\Models;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $product = new Product;
        $this->assertEquals(
            ['name', 'quantity', 'buying_price', 'selling_price'],
            $product->getFillable()
        );
    }

    public function test_can_create_product(): void
    {
        $product = Product::create([
            'name' => 'Test Product',
            'quantity' => 100,
            'buying_price' => 5.00,
            'selling_price' => 10.00,
        ]);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertEquals(100, $product->quantity);
        $this->assertEquals(5.00, $product->buying_price);
        $this->assertEquals(10.00, $product->selling_price);
    }

    public function test_sale_details_relationship(): void
    {
        $product = Product::create([
            'name' => 'Rice',
            'quantity' => 50,
            'buying_price' => 2.00,
            'selling_price' => 3.50,
        ]);

        $buyer = Buyer::create([
            'name' => 'Test Buyer',
            'phone' => '+250788000000',
        ]);

        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 7.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 3.50,
            'subtotal' => 7.00,
        ]);

        $this->assertCount(1, $product->saleDetails);
        $this->assertInstanceOf(SaleDetail::class, $product->saleDetails->first());
    }
}
