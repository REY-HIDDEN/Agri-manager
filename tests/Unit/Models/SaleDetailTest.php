<?php

namespace Tests\Unit\Models;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $detail = new SaleDetail;
        $this->assertEquals(
            ['sale_id', 'product_id', 'quantity', 'unit_price', 'subtotal'],
            $detail->getFillable()
        );
    }

    public function test_sale_relationship(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        $product = Product::create([
            'name' => 'Potato',
            'quantity' => 200,
            'buying_price' => 0.50,
            'selling_price' => 1.00,
        ]);
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 5.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);
        $detail = SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 1.00,
            'subtotal' => 5.00,
        ]);

        $this->assertInstanceOf(Sale::class, $detail->sale);
        $this->assertEquals($sale->id, $detail->sale->id);
    }

    public function test_product_relationship(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        $product = Product::create([
            'name' => 'Carrot',
            'quantity' => 150,
            'buying_price' => 0.70,
            'selling_price' => 1.30,
        ]);
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 3.90,
            'sale_date' => now(),
            'payment_status' => 'pending',
        ]);
        $detail = SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'unit_price' => 1.30,
            'subtotal' => 3.90,
        ]);

        $this->assertInstanceOf(Product::class, $detail->product);
        $this->assertEquals($product->id, $detail->product->id);
    }
}
