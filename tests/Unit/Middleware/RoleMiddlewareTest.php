<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new RoleMiddleware;
        $response = $middleware->handle($request, fn ($req) => response('OK'), 'admin');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_sales_officer_cannot_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => 'sales_officer']);
        $this->actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $this->expectException(HttpException::class);

        $middleware = new RoleMiddleware;
        $middleware->handle($request, fn ($req) => response('OK'), 'admin');
    }

    public function test_unauthenticated_user_is_rejected(): void
    {
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => null);

        $this->expectException(HttpException::class);

        $middleware = new RoleMiddleware;
        $middleware->handle($request, fn ($req) => response('OK'), 'admin');
    }

    public function test_user_with_any_allowed_role_can_access(): void
    {
        $user = User::factory()->create(['role' => 'sales_officer']);
        $this->actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new RoleMiddleware;
        $response = $middleware->handle($request, fn ($req) => response('OK'), 'admin', 'sales_officer');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
