<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminJwtTest extends TestCase
{
    public function test_admin_api_login_returns_jwt_token(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@estilo.com',
            'password' => 'Admin@123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
                'user' => ['id', 'name', 'email', 'role']
            ]);
    }

    public function test_unauthenticated_user_cannot_access_admin_stats(): void
    {
        $response = $this->getJson('/api/admin/dashboard-stats');
        $response->assertStatus(401);
    }
}

