<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Mockery;
use Psr\Log\LoggerInterface;
use Resend\Exceptions\ErrorException as ResendErrorException;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_password_reset_link_without_redirecting_back_to_itself(): void
    {
        $this->get(route('password.reset', [
            'token' => 'reset-token',
            'email' => 'jane@example.com',
        ]))
            ->assertOk()
            ->assertSee('<div id="app"></div>', false);
    }

    public function test_guest_bootstrap_user_endpoint_returns_null_user(): void
    {
        $this->getJson('/api/auth/user')
            ->assertOk()
            ->assertJson(['user' => null]);
    }

    public function test_user_can_register_via_api(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.email', 'jane@example.com');

        Notification::assertSentTo(User::first(), VerifyEmail::class, 1);
    }

    public function test_authenticated_user_can_check_email_verification_status(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->getJson('/api/auth/email/status')
            ->assertOk()
            ->assertJson(['verified' => false]);

        $user->markEmailAsVerified();

        $this->getJson('/api/auth/email/status')
            ->assertOk()
            ->assertJson(['verified' => true]);
    }

    public function test_unverified_user_cannot_access_verified_api_features(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->getJson('/api/dashboard')->assertForbidden();
    }

    public function test_authenticated_unverified_user_can_resend_verification_email(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->postJson('/api/auth/email/verification-notification')
            ->assertOk()
            ->assertJson(['message' => 'Verification link sent.']);

        Notification::assertSentTo($user, VerifyEmail::class, 1);
    }

    public function test_verification_delivery_failure_is_logged_safely_and_returns_generic_service_error(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->setAttribute('id', 123);
        $user->shouldReceive('hasVerifiedEmail')->once()->andReturnFalse();
        $user->shouldReceive('sendEmailVerificationNotification')
            ->once()
            ->andThrow(new TransportException(
                'Provider failure containing private email and message content.',
                0,
                new ResendErrorException([
                    'message' => 'Rejected key secret-api for jane@example.com with message body content.',
                    'name' => 'invalid_api_key',
                    'statusCode' => 401,
                ])
            ));

        $request = Request::create('/api/auth/email/verification-notification', 'POST');
        $request->setUserResolver(fn (): User => $user);

        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('error')
            ->once()
            ->with('Verification email resend failed.', Mockery::on(function (array $context): bool {
                $this->assertSame(TransportException::class, $context['exception']);
                $this->assertSame(123, $context['user_id']);
                $this->assertSame('resend', $context['mailer']);
                $this->assertSame(ResendErrorException::class, $context['cause']);
                $this->assertSame(401, $context['provider_http_status']);
                $this->assertTrue($context['resend_api_key_configured']);
                $this->assertArrayNotHasKey('error', $context);
                $this->assertArrayNotHasKey('email', $context);
                $this->assertArrayNotHasKey('message', $context);

                return true;
            }));

        Log::shouldReceive('error')->once()->andReturnUsing(function (string $message, array $context) use ($logger): void {
            $logger->error($message, $context);
        });

        config([
            'mail.default' => 'resend',
            'services.resend.key' => 'secret-api',
        ]);

        $response = app(EmailVerificationController::class)->send($request);

        $this->assertSame(503, $response->getStatusCode());
        $this->assertSame([
            'message' => 'Verification email could not be sent. Check the server mail configuration and try again later.',
        ], $response->getData(true));
    }

    public function test_verified_user_is_not_sent_another_verification_email(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/auth/email/verification-notification')
            ->assertOk()
            ->assertJson(['message' => 'Email address is already verified.']);

        Notification::assertNothingSent();
    }

    public function test_verification_email_resend_is_rate_limited(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->postJson('/api/auth/email/verification-notification')->assertOk();
        }

        $this->postJson('/api/auth/email/verification-notification')->assertTooManyRequests();
    }

    public function test_signed_verification_link_marks_email_verified_and_redirects_to_frontend(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $this->actingAs($user)->get($url)
            ->assertRedirect('/verify-email?verified=1');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_invalid_verification_signature_redirects_with_recovery_message(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $this->actingAs($user)->get($url.'&signature=invalid')
            ->assertRedirect('/verify-email?error=invalid_or_expired');

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_verification_link_with_an_invalid_email_hash_redirects_with_recovery_message(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1('different@example.com'),
        ]);

        $this->actingAs($user)->get($url)
            ->assertRedirect('/verify-email?error=invalid_or_expired');

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_expired_verification_link_redirects_with_recovery_message(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinute(), [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);
        Carbon::setTestNow(now()->addMinutes(2));

        try {
            $this->actingAs($user)->get($url)
                ->assertRedirect('/verify-email?error=invalid_or_expired');
        } finally {
            Carbon::setTestNow();
        }

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_user_can_login_via_api(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.email', 'login@example.com');
    }

    public function test_authenticated_user_can_logout_via_api(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Logout successful.']);
    }

    public function test_authenticated_user_can_update_profile_via_api(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson('/api/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9999999999',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.email', 'updated@example.com');
    }

    public function test_authenticated_user_cannot_update_profile_with_missing_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson('/api/profile', [
            'name' => null,
            'email' => null,
            'phone' => '9999999999',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_authenticated_user_can_update_password_via_api(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/profile/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertOk()
            ->assertJson(['message' => 'Password updated successfully.']);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_authenticated_user_cannot_update_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['current_password']);
    }
}
