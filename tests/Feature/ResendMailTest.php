<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Mail\Transport\ResendTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Resend\Contracts\Client as ResendClientContract;
use Resend\Email as ResendEmail;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Tests\TestCase;
use Throwable;

class ResendMailTest extends TestCase
{
    use RefreshDatabase;

    private FakeResendClient $resendClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);

        config([
            'mail.default' => 'resend',
            'mail.from.address' => 'verified@example.com',
            'mail.from.name' => 'CMS Support',
            'services.resend.key' => 'test-resend-api-key',
        ]);

        $this->resendClient = new FakeResendClient;

        Mail::extend('resend', fn (array $config = []): ResendTransport => new ResendTransport($this->resendClient));
    }

    public function test_resend_transport_sends_expected_payload_through_the_official_laravel_driver(): void
    {
        $this->sendTestEmail();

        $this->assertSame([
            'from' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'cc' => [],
            'bcc' => [],
            'reply_to' => [],
            'headers' => [],
            'subject' => 'Test subject',
            'html' => '<p>Test email body</p>',
            'text' => null,
            'attachments' => [],
        ], $this->resendClient->emailPayloads[0]);
    }

    public function test_smtp_configuration_uses_a_supported_starttls_scheme(): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.scheme' => 'smtp',
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.port' => 587,
            'mail.mailers.smtp.username' => 'gmail@example.com',
            'mail.mailers.smtp.password' => 'google-app-password',
        ]);

        $this->assertInstanceOf(EsmtpTransport::class, Mail::mailer('smtp')->getSymfonyTransport());
    }

    public function test_verification_notification_uses_the_local_smtp_mailer_without_network_delivery(): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'array',
            'mail.from.address' => 'local-gmail@example.com',
            'mail.from.name' => 'Local CMS',
        ]);

        $user = User::factory()->unverified()->create(['email' => 'jane@example.com']);
        $user->sendEmailVerificationNotification();

        $message = Mail::mailer('smtp')->getSymfonyTransport()->messages()->first()->getOriginalMessage();

        $this->assertInstanceOf(Email::class, $message);
        $this->assertSame('local-gmail@example.com', $message->getFrom()[0]->getAddress());
        $this->assertSame('jane@example.com', $message->getTo()[0]->getAddress());
        $this->assertStringContainsString('/email/verify/', (string) $message->getHtmlBody());
    }

    public function test_verification_markdown_dependency_is_autoloadable(): void
    {
        $this->assertTrue(class_exists('Nette\\Schema\\Kind'));
    }

    public function test_resend_transport_wraps_provider_failures(): void
    {
        $this->resendClient->exception = new RuntimeException('Resend rejected the request.');

        $this->expectException(TransportException::class);
        $this->sendTestEmail();
    }

    public function test_registration_sends_verification_email_through_resend(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertCreated()->assertJsonPath('mail_warning', null);

        $this->assertSame('jane@example.com', $this->resendClient->emailPayloads[0]['to'][0]);
        $this->assertStringContainsString('/email/verify/', $this->resendClient->emailPayloads[0]['html']);
    }

    public function test_verification_resend_uses_the_resend_transport(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->postJson('/api/auth/email/verification-notification')
            ->assertOk();

        $this->assertSame($user->email, $this->resendClient->emailPayloads[0]['to'][0]);
        $this->assertStringContainsString('/email/verify/', $this->resendClient->emailPayloads[0]['html']);
    }

    public function test_verification_resend_uses_a_signed_https_production_url(): void
    {
        config(['app.url' => 'https://complaint-system-p1y1.onrender.com']);
        URL::forceRootUrl('https://complaint-system-p1y1.onrender.com');
        URL::forceScheme('https');

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->postJson('/api/auth/email/verification-notification')->assertOk();

        $message = $this->resendClient->emailPayloads[0]['html'];
        preg_match('~https://complaint-system-p1y1\.onrender\.com/email/verify/[^"<>\\s]+~', html_entity_decode($message), $matches);

        $this->assertNotEmpty($matches[0] ?? null);
        $this->assertTrue(URL::hasValidSignature(Request::create($matches[0])));
    }

    public function test_failed_verification_delivery_returns_a_safe_service_unavailable_response(): void
    {
        $user = User::factory()->unverified()->create();
        $this->resendClient->exception = new RuntimeException('Rejected key test-resend-api-key');

        $this->actingAs($user)->postJson('/api/auth/email/verification-notification')
            ->assertStatus(503)
            ->assertJson(['message' => 'Verification email could not be sent. Check the server mail configuration and try again later.'])
            ->assertDontSee('test-resend-api-key')
            ->assertDontSee('TransportException');
    }

    public function test_password_reset_email_can_be_requested_and_used_through_resend(): void
    {
        $user = User::factory()->create(['email' => 'jane@example.com']);

        $this->postJson('/api/auth/forgot-password', ['email' => $user->email])
            ->assertOk();

        preg_match('~/reset-password/([A-Za-z0-9]+)~', html_entity_decode($this->resendClient->emailPayloads[0]['html']), $matches);
        $this->assertNotEmpty($matches[1] ?? null, 'The reset email should contain the existing reset route and token.');

        $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'token' => $matches[1],
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertOk();

        $this->assertTrue(password_verify('new-password', $user->fresh()->password));
    }

    public function test_failed_password_reset_delivery_returns_generic_error(): void
    {
        User::factory()->create(['email' => 'jane@example.com']);
        $this->resendClient->exception = new RuntimeException('Invalid Resend API request.');

        $this->postJson('/api/auth/forgot-password', ['email' => 'jane@example.com'])
            ->assertStatus(503)
            ->assertJson(['message' => 'Password reset email could not be sent. Try again later.'])
            ->assertDontSee('RESEND_API_KEY');
    }

    private function sendTestEmail(): void
    {
        $email = (new Email)
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->subject('Test subject')
            ->html('<p>Test email body</p>');

        Mail::mailer('resend')->getSymfonyTransport()->send($email);
    }
}

class FakeResendClient implements ResendClientContract
{
    /** @var list<array<string, mixed>> */
    public array $emailPayloads = [];

    public ?Throwable $exception = null;

    public function __get(string $name): FakeResendEmailService
    {
        if ($name !== 'emails') {
            throw new RuntimeException('Unknown fake Resend service.');
        }

        return new FakeResendEmailService($this);
    }
}

class FakeResendEmailService
{
    public function __construct(private readonly FakeResendClient $client) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function send(array $payload): object
    {
        $this->client->emailPayloads[] = $payload;

        if ($this->client->exception !== null) {
            throw $this->client->exception;
        }

        return ResendEmail::from(['id' => 'test-resend-email-id']);
    }
}
