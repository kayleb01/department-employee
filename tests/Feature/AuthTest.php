<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_logs_in_a_user()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email
                ]
            ]
        ]);
    }

    /** @test */
    public function it_login_user_with_invalid_credentials () {
        $credentials = ['email' => 'invalid@email.com', 'password' => 'wrong password'];
    
        $response = $this->postJson('/api/v1/login', $credentials);
    
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertStringContainsString('Credentials do not match our records', $response->json()['message']);
    }

    /** @test */
    public function it_registers_a_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/register', $userData);

        $response->assertJson([
            'message' => 'Registration successful',
            'data' => [
                'name' => $userData['name'], 
                'email' => $userData['email']
            ]
        ]);
    }

    /** @test */
    public function register_user_with_missing_required_fields()
     {
        $data = [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];
    
        $response = $this->postJson('/api/v1/register', $data);
    
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertStringContainsString('The name field is required', $response->json()['message']);
    }
}
