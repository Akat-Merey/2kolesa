<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void 
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Akhat',
            'email' => 'akhat@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token'])
            ->assertJsonMissingPath('user.passwrod');

        $this->assertDatabaseHas('users', ['email' => 'akhat@example.com']);
    }

    public function test_registration_fails_when_is_taken(): void 
    {
        User::factory()->create(['email' => 'akhat@example.com']);

        $this->postJson('/api/register', [
            'name' => 'Akhat',
            'email' => 'akhat@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertUnprocessable()
          ->assertJsonValidationErrors('email');
    }

    public function test_user_can_login(): void
    {
        User::factory()->create([
            'email'    => 'akhat@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/login', [
            'email'    => 'akhat@example.com',
            'password' => 'password123',
        ])->assertOk()
          ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'akhat@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/login', [
            'email'    => 'akhat@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized();
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_logout_deletes_token(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertNoContent();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
