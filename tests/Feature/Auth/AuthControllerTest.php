<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login_with_username(): void
    {
        User::factory()->create([
            'username' => 'admin',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'role' => 'admin',
            'login' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_sales_officer_can_login_with_email(): void
    {
        User::factory()->create([
            'email' => 'sales@test.com',
            'password' => 'password',
            'role' => 'sales_officer',
        ]);

        $response = $this->post('/login', [
            'role' => 'sales_officer',
            'login' => 'sales@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::factory()->create([
            'username' => 'admin',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'role' => 'admin',
            'login' => 'admin',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_login_validation_requires_all_fields(): void
    {
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors(['role', 'login', 'password']);
    }

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'new@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'role' => 'sales_officer',
        ]);
    }

    public function test_register_validates_unique_username(): void
    {
        User::factory()->create(['username' => 'taken']);

        $response = $this->post('/register', [
            'name' => 'New User',
            'username' => 'taken',
            'email' => 'new@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_register_validates_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'new@test.com',
            'password' => 'password',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }
}
