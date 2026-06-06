<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $user = new User;
        $this->assertEquals(
            ['name', 'username', 'email', 'password', 'role'],
            $user->getFillable()
        );
    }

    public function test_hidden_attributes(): void
    {
        $user = new User;
        $this->assertEquals(['password', 'remember_token'], $user->getHidden());
    }

    public function test_casts_include_hashed_password_and_datetime(): void
    {
        $user = new User;
        $casts = $user->getCasts();
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }

    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isSalesOfficer());
    }

    public function test_is_sales_officer_returns_true_for_sales_officer_role(): void
    {
        $user = User::factory()->create(['role' => 'sales_officer']);
        $this->assertTrue($user->isSalesOfficer());
        $this->assertFalse($user->isAdmin());
    }

    public function test_is_admin_returns_false_for_non_admin(): void
    {
        $user = User::factory()->create(['role' => 'sales_officer']);
        $this->assertFalse($user->isAdmin());
    }

    public function test_factory_creates_valid_user(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertNotNull($user->username);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->role);
    }

    public function test_password_is_hashed_on_create(): void
    {
        $user = User::factory()->create(['password' => 'plaintext']);
        $this->assertNotEquals('plaintext', $user->password);
    }
}
