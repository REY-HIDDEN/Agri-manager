<?php

namespace Tests\Unit\Models;

use App\Models\Buyer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    private function createBuyer(): Buyer
    {
        return Buyer::create([
            'name' => 'Test Buyer',
            'phone' => '+250788000000',
        ]);
    }

    public function test_fillable_attributes(): void
    {
        $sale = new Sale;
        $this->assertEquals(
            ['buyer_id', 'total_amount', 'sale_date', 'payment_status'],
            $sale->getFillable()
        );
    }

    public function test_sale_date_is_cast_to_date(): void
    {
        $buyer = $this->createBuyer();
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 100.00,
            'sale_date' => '2024-06-15',
            'payment_status' => 'paid',
        ]);

        $this->assertInstanceOf(Carbon::class, $sale->sale_date);
        $this->assertEquals('2024-06-15', $sale->sale_date->toDateString());
    }

    public function test_buyer_relationship(): void
    {
        $buyer = $this->createBuyer();
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 50.00,
            'sale_date' => now(),
            'payment_status' => 'pending',
        ]);

        $this->assertInstanceOf(Buyer::class, $sale->buyer);
        $this->assertEquals($buyer->id, $sale->buyer->id);
    }

    public function test_sale_details_relationship(): void
    {
        $buyer = $this->createBuyer();
        $product = Product::create([
            'name' => 'Tomato',
            'quantity' => 100,
            'buying_price' => 1.00,
            'selling_price' => 2.00,
        ]);

        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 4.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 2.00,
            'subtotal' => 4.00,
        ]);

        $this->assertCount(1, $sale->saleDetails);
        $this->assertInstanceOf(SaleDetail::class, $sale->saleDetails->first());
    }

    public function test_delivery_relationship(): void
    {
        $buyer = $this->createBuyer();
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 100.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        Delivery::create([
            'sale_id' => $sale->id,
            'delivery_date' => now(),
            'destination' => 'Kigali',
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(Delivery::class, $sale->delivery);
    }
}
