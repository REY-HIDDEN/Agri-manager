<?php

namespace Tests\Feature\Controllers;

use App\Models\Buyer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyerControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_cannot_access_buyers(): void
    {
        $response = $this->get('/buyers');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_buyers(): void
    {
        Buyer::create(['name' => 'Alice', 'phone' => '+250788000000']);

        $response = $this->actingAs($this->user)->get('/buyers');
        $response->assertStatus(200);
        $response->assertSee('Alice');
    }

    public function test_buyers_can_be_searched_by_name(): void
    {
        Buyer::create(['name' => 'Alice', 'phone' => '+250788000000']);
        Buyer::create(['name' => 'Bob', 'phone' => '+250788111111']);

        $response = $this->actingAs($this->user)->get('/buyers?search=Alice');
        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');
    }

    public function test_buyers_can_be_searched_by_phone(): void
    {
        Buyer::create(['name' => 'Alice', 'phone' => '+250788000000']);
        Buyer::create(['name' => 'Bob', 'phone' => '+250788111111']);

        $response = $this->actingAs($this->user)->get('/buyers?search=111111');
        $response->assertStatus(200);
        $response->assertSee('Bob');
        $response->assertDontSee('Alice');
    }

    public function test_can_access_create_buyer_form(): void
    {
        $response = $this->actingAs($this->user)->get('/buyers/create');
        $response->assertStatus(200);
    }

    public function test_can_store_buyer(): void
    {
        $response = $this->actingAs($this->user)->post('/buyers', [
            'name' => 'New Buyer',
            'phone' => '+250788222333',
            'email' => 'buyer@test.com',
            'address' => 'Kigali',
        ]);

        $response->assertRedirect(route('buyers.index'));
        $this->assertDatabaseHas('buyers', ['name' => 'New Buyer', 'phone' => '+250788222333']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post('/buyers', []);
        $response->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_store_validates_phone_format(): void
    {
        $response = $this->actingAs($this->user)->post('/buyers', [
            'name' => 'Test',
            'phone' => 'invalid-phone!@#',
        ]);
        $response->assertSessionHasErrors('phone');
    }

    public function test_can_update_buyer(): void
    {
        $buyer = Buyer::create(['name' => 'Old Name', 'phone' => '+250788000000']);

        $response = $this->actingAs($this->user)->put("/buyers/{$buyer->id}", [
            'name' => 'Updated Name',
            'phone' => '+250788999999',
        ]);

        $response->assertRedirect(route('buyers.index'));
        $this->assertDatabaseHas('buyers', ['id' => $buyer->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_buyer(): void
    {
        $buyer = Buyer::create(['name' => 'To Delete', 'phone' => '+250788000000']);

        $response = $this->actingAs($this->user)->delete("/buyers/{$buyer->id}");
        $response->assertRedirect(route('buyers.index'));
        $this->assertDatabaseMissing('buyers', ['id' => $buyer->id]);
    }
}
