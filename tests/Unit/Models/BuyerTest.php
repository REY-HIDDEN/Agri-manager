<?php

namespace Tests\Unit\Models;

use App\Models\Buyer;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyerTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $buyer = new Buyer;
        $this->assertEquals(
            ['name', 'phone', 'email', 'address'],
            $buyer->getFillable()
        );
    }

    public function test_can_create_buyer(): void
    {
        $buyer = Buyer::create([
            'name' => 'John Doe',
            'phone' => '+250788111222',
            'email' => 'john@example.com',
            'address' => 'Kigali, Rwanda',
        ]);

        $this->assertDatabaseHas('buyers', ['name' => 'John Doe']);
        $this->assertEquals('+250788111222', $buyer->phone);
    }

    public function test_email_and_address_are_nullable(): void
    {
        $buyer = Buyer::create([
            'name' => 'Jane Doe',
            'phone' => '+250788333444',
        ]);

        $this->assertNull($buyer->email);
        $this->assertNull($buyer->address);
    }

    public function test_sales_relationship(): void
    {
        $buyer = Buyer::create([
            'name' => 'Buyer A',
            'phone' => '+250788555666',
        ]);

        Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 100.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);

        Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 50.00,
            'sale_date' => now(),
            'payment_status' => 'pending',
        ]);

        $this->assertCount(2, $buyer->sales);
        $this->assertInstanceOf(Sale::class, $buyer->sales->first());
    }
}
