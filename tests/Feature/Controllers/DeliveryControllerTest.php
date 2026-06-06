<?php

namespace Tests\Feature\Controllers;

use App\Models\Buyer;
use App\Models\Delivery;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $salesOfficer;

    private Sale $sale;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->salesOfficer = User::factory()->create(['role' => 'sales_officer']);

        $buyer = Buyer::create(['name' => 'Buyer', 'phone' => '+250788000000']);
        $this->sale = Sale::create([
            'buyer_id' => $buyer->id,
            'total_amount' => 100.00,
            'sale_date' => now(),
            'payment_status' => 'paid',
        ]);
    }

    public function test_guest_cannot_access_deliveries(): void
    {
        $response = $this->get('/deliveries');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_deliveries(): void
    {
        $response = $this->actingAs($this->salesOfficer)->get('/deliveries');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_create_delivery_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/deliveries/create');
        $response->assertStatus(200);
    }

    public function test_sales_officer_cannot_create_delivery(): void
    {
        $response = $this->actingAs($this->salesOfficer)->get('/deliveries/create');
        $response->assertStatus(403);
    }

    public function test_admin_can_store_delivery(): void
    {
        $response = $this->actingAs($this->admin)->post('/deliveries', [
            'sale_id' => $this->sale->id,
            'delivery_date' => '2024-07-01',
            'destination' => 'Kigali Market',
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('deliveries.index'));
        $this->assertDatabaseHas('deliveries', [
            'sale_id' => $this->sale->id,
            'destination' => 'Kigali Market',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/deliveries', []);
        $response->assertSessionHasErrors(['sale_id', 'delivery_date', 'destination', 'status']);
    }

    public function test_store_validates_status_values(): void
    {
        $response = $this->actingAs($this->admin)->post('/deliveries', [
            'sale_id' => $this->sale->id,
            'delivery_date' => '2024-07-01',
            'destination' => 'Kigali',
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_store_validates_unique_sale_id(): void
    {
        Delivery::create([
            'sale_id' => $this->sale->id,
            'delivery_date' => now(),
            'destination' => 'Existing',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post('/deliveries', [
            'sale_id' => $this->sale->id,
            'delivery_date' => '2024-07-01',
            'destination' => 'New Location',
            'status' => 'pending',
        ]);

        $response->assertSessionHasErrors('sale_id');
    }

    public function test_admin_can_update_delivery(): void
    {
        $delivery = Delivery::create([
            'sale_id' => $this->sale->id,
            'delivery_date' => now(),
            'destination' => 'Old Place',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->put("/deliveries/{$delivery->id}", [
            'delivery_date' => '2024-08-01',
            'destination' => 'New Place',
            'status' => 'delivered',
        ]);

        $response->assertRedirect(route('deliveries.index'));
        $this->assertDatabaseHas('deliveries', [
            'id' => $delivery->id,
            'destination' => 'New Place',
            'status' => 'delivered',
        ]);
    }

    public function test_admin_can_delete_delivery(): void
    {
        $delivery = Delivery::create([
            'sale_id' => $this->sale->id,
            'delivery_date' => now(),
            'destination' => 'To Delete',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->delete("/deliveries/{$delivery->id}");
        $response->assertRedirect(route('deliveries.index'));
        $this->assertDatabaseMissing('deliveries', ['id' => $delivery->id]);
    }

    public function test_deliveries_can_be_filtered_by_status(): void
    {
        Delivery::create(['sale_id' => $this->sale->id, 'delivery_date' => now(), 'destination' => 'A', 'status' => 'pending']);

        $response = $this->actingAs($this->admin)->get('/deliveries?status=pending');
        $response->assertStatus(200);
    }
}
