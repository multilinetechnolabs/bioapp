<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

use App\Http\Middleware\SecureHeaders;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;

class RegisterCaptchaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Keep the developer's .env (staging password gate, verification bypass) out of these tests.
        config([
            'services.recaptcha.secret_key' => 'test-secret',
            'app.site_access_password' => null,
            'app.bypass_email_verification' => false,
        ]);

        // SecureHeaders calls header_remove(), which raises "headers already sent" once PHPUnit
        // has printed anything. It has no bearing on the captcha, so keep it out of the way.
        $this->withoutMiddleware(SecureHeaders::class);

        Role::create(['name' => 'student/estudiante']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'student/estudiante',
            'name' => 'Captcha Tester',
            'username' => 'captchatester',
            'email' => 'captcha.tester@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'g-recaptcha-response' => 'token',
        ], $overrides);
    }

    private function register(array $overrides = [])
    {
        return $this->from(route('register'))->post(route('register'), $this->payload($overrides));
    }

    public function testRegisterPageShowsTheCaptchaWidget()
    {
        config(['services.recaptcha.site_key' => 'test-site-key']);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('class="g-recaptcha"', false)
            ->assertSee('data-sitekey="test-site-key"', false)
            ->assertSee('https://www.google.com/recaptcha/api.js', false);
    }

    public function testRegistrationIsRejectedWithoutACaptchaToken()
    {
        Http::fake();

        $this->register(['g-recaptcha-response' => ''])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('g-recaptcha-response');

        $this->assertDatabaseMissing('users', ['email' => 'captcha.tester@example.com']);
        Http::assertNothingSent();
    }

    public function testRegistrationIsRejectedWhenGoogleRejectsTheToken()
    {
        Http::fake(['*' => Http::response(['success' => false])]);

        $this->register()
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('g-recaptcha-response');

        $this->assertDatabaseMissing('users', ['email' => 'captcha.tester@example.com']);
    }

    public function testRegistrationFailsGracefullyWhenGoogleIsUnreachable()
    {
        Http::fake(function () {
            throw new ConnectionException('cURL error 28: Operation timed out');
        });

        $this->register()
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('g-recaptcha-response');

        $this->assertDatabaseMissing('users', ['email' => 'captcha.tester@example.com']);
    }

    public function testRegistrationSucceedsWhenGoogleConfirmsTheToken()
    {
        Http::fake(['*' => Http::response(['success' => true])]);

        $this->register()
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'captcha.tester@example.com']);
    }

    public function testRegistrationIsThrottledPerIp()
    {
        Http::fake();

        for ($i = 0; $i < 10; $i++) {
            $this->register(['g-recaptcha-response' => '']);
        }

        $this->register(['g-recaptcha-response' => ''])->assertStatus(429);
    }
}
