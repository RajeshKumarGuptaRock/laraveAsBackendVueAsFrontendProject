<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_token_and_sends_verification(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'SecurePass1a',
            'password_confirmation' => 'SecurePass1a',
            'device_name' => 'PHPUnit',
            'terms' => true,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.user.email', 'newuser@example.com')
            ->assertJsonPath('data.user.name', 'Test User')
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'email_verified_at', 'created_at'],
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'Test User',
        ]);

        $user = User::query()->where('email', 'newuser@example.com')->firstOrFail();
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Other',
            'email' => 'taken@example.com',
            'password' => 'SecurePass1a',
            'password_confirmation' => 'SecurePass1a',
            'terms' => true,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_requires_terms_acceptance(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'terms@example.com',
            'password' => 'SecurePass1a',
            'password_confirmation' => 'SecurePass1a',
            'terms' => false,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['terms']);
    }

    public function test_register_requires_password_confirmation(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'confirm@example.com',
            'password' => 'SecurePass1a',
            'terms' => true,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_email_verification_link_verifies_user(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.com',
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->get($url);

        $response->assertRedirect();
        $this->assertStringContainsString('verification=success', $response->headers->get('Location'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_resend_verification_requires_authentication(): void
    {
        $response = $this->postJson('/api/email/resend-verification');

        $response->assertUnauthorized();
    }

    public function test_resend_verification_sends_notification_when_unverified(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/email/resend-verification');

        $response->assertOk()
            ->assertJsonStructure(['message']);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_authenticated_user_endpoint_returns_profile(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertOk()
            ->assertJsonPath('email', $user->email);
    }
}
