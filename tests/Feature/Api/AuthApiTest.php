<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        User::factory(1)->create();
    }

    public function test_user_can_login(): void
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => 'test@user.com',
            'password' => 'TestPass123',
        ]);

        $response->assertStatus(200);
        $responseBody = $response->json();

        $this->assertArrayHasKey('email', $responseBody['data']);
        $this->assertArrayHasKey('token', $responseBody['data']);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test_user@user.com',
            'password' => 'TestPass123',
        ]);

        $response->assertStatus(201);
        $responseBody = $response->json();

        $this->assertArrayHasKey('email', $responseBody['data']);
        $this->assertArrayHasKey('token', $responseBody['data']);
    }

    public function test_user_cannot_register_with_the_same_email_twice(): void
    {
        $response = $this->post('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => 'TestPass123',
        ]);

        $response->assertStatus(422);
        $responseBody = $response->json();

        $this->assertFalse($responseBody['success']);
        $this->assertEquals('The email has already been taken.', $responseBody['errors']['email'][0]);
    }
}
