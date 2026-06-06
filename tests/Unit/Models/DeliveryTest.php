<?php

namespace Tests\Unit\Models;

use App\Models\Buyer;
use App\Models\Delivery;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $delivery = new Delivery;
        $this->assertEquals(
            ['sale_id', 'delivery_date', 'destination', 'status'],
            $delivery->getFillable()
        );
    }

    public function test_delivery_date_is_cast_to_date(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 100.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        $delivery = Delivery::create([
            'sale_id' => $sale->id,
            'delivery_date' => '2024-07-01',
            'destination' => 'Musanze',
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(Carbon::class, $delivery->delivery_date);
        $this->assertEquals('2024-07-01', $delivery->delivery_date->toDateString());
    }

    public function test_sale_relationship(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        $sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 100.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        $delivery = Delivery::create([
            'sale_id' => $sale->id,
            'delivery_date' => now(),
            'destination' => 'Kigali',
            'status' => 'in_transit',
        ]);

        $this->assertInstanceOf(Sale::class, $delivery->sale);
        $this->assertEquals($sale->id, $delivery->sale->id);
    }

    public function test_can_create_delivery_with_different_statuses(): void
    {
        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);

        foreach (['pending', 'in_transit', 'delivered'] as $index => $status) {
            $sale = Sale::create([
                'buyer_id' => $buyer->id,
                'total_amount' => 50.00,
                'sale_date' => now(),
                'payment_status' => 'paid',
            ]);

            $delivery = Delivery::create([
                'sale_id' => $sale->id,
                'delivery_date' => now(),
                'destination' => 'Location '.$index,
                'status' => $status,
            ]);

            $this->assertEquals($status, $delivery->status);
        }
    }
}
