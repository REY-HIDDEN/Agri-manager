<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_users_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/users');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_create_user_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/users/create');
        $response->assertStatus(200);
    }

    public function test_admin_can_store_user(): void
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'New Admin',
            'username' => 'newadmin',
            'email' => 'newadmin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['username' => 'newadmin', 'role' => 'admin']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/users', []);
        $response->assertSessionHasErrors(['name', 'username', 'password', 'role']);
    }

    public function test_store_validates_unique_username(): void
    {
        User::factory()->create(['username' => 'existing']);

        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test',
            'username' => 'existing',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_store_validates_password_minimum_length(): void
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test',
            'username' => 'testuser',
            'password' => 'short',
            'password_confirmation' => 'short',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_store_validates_role_values(): void
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test',
            'username' => 'testuser',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'invalid_role',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create(['name' => 'Old Name', 'role' => 'sales_officer']);

        $response = $this->actingAs($this->admin)->put("/users/{$user->id}", [
            'name' => 'Updated Name',
            'username' => $user->username,
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name', 'role' => 'admin']);
    }

    public function test_update_without_password_preserves_existing(): void
    {
        $user = User::factory()->create(['password' => 'original']);
        $originalPassword = $user->password;

        $this->actingAs($this->admin)->put("/users/{$user->id}", [
            'name' => $user->name,
            'username' => $user->username,
            'role' => $user->role,
        ]);

        $this->assertEquals($originalPassword, $user->fresh()->password);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $other = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/users/{$other->id}");
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $other->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete("/users/{$this->admin->id}");
        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
