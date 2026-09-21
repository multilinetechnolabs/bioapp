<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

use App\Http\Middleware\SecureHeaders;
use App\Support\Captcha;
use Spatie\Permission\Models\Role;

class RegisterCaptchaTest extends TestCase
{
    const CODE = 'ABCDE';

    protected function setUp(): void
    {
        parent::setUp();

        // Keep the developer's .env (staging password gate, verification bypass) out of these tests.
        config([
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
            'captcha' => self::CODE,
        ], $overrides);
    }

    // Plays the part of a visitor whose form currently shows the image for CODE.
    private function issueCode(?int $expiresAt = null): void
    {
        $entry = app(Captcha::class)->entryFor(self::CODE);

        if ($expiresAt !== null) {
            $entry['expires_at'] = $expiresAt;
        }

        $this->withSession([Captcha::SESSION_KEY => $entry]);
    }

    private function register(array $overrides = [])
    {
        return $this->from(route('register'))->post(route('register'), $this->payload($overrides));
    }

    private function assertNoUser(): void
    {
        $this->assertDatabaseMissing('users', ['email' => 'captcha.tester@example.com']);
    }

    public function testRegisterPageShowsTheImageCaptchaAndNoGoogleWidget()
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('id="captchaImage"', false)
            ->assertSee('src="/captcha?', false)
            ->assertSee('name="captcha"', false)
            ->assertDontSee('g-recaptcha', false)
            ->assertDontSee('recaptcha/api.js', false);
    }

    public function testCaptchaImageIsANoStorePngAndStartsACode()
    {
        $response = $this->get(route('captcha.image'))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertSessionHas(Captcha::SESSION_KEY);

        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertSame("\x89PNG", substr($response->getContent(), 0, 4));
    }

    public function testCaptchaImageIsThrottled()
    {
        for ($i = 0; $i < 30; $i++) {
            $this->get(route('captcha.image'))->assertOk();
        }

        $this->get(route('captcha.image'))->assertStatus(429);
    }

    public function testRegistrationIsRejectedWithoutACode()
    {
        $this->issueCode();

        $this->register(['captcha' => ''])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('captcha');

        $this->assertNoUser();
    }

    public function testRegistrationIsRejectedWithAWrongCodeAndUsesUpTheCode()
    {
        $this->issueCode();

        $this->register(['captcha' => 'WRONG'])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('captcha')
            ->assertSessionMissing(Captcha::SESSION_KEY);

        $this->assertNoUser();
    }

    public function testRegistrationIsRejectedWhenNoCodeWasEverShown()
    {
        $this->register()
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('captcha');

        $this->assertNoUser();
    }

    public function testRegistrationIsRejectedWhenTheCodeHasExpired()
    {
        $this->issueCode(now()->timestamp - 1);

        $this->register()
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('captcha');

        $this->assertNoUser();
    }

    public function testRegistrationSucceedsWithTheRightCodeWhateverTheCaseOrSpacing()
    {
        $this->issueCode();

        $this->register(['captcha' => 'ab cde'])
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'captcha.tester@example.com']);
    }

    public function testACorrectCodeCannotBeReplayedAfterTheFormFailedForAnotherReason()
    {
        $this->issueCode();

        $this->register(['username' => 'short'])->assertSessionHasErrors('username');

        $this->register()->assertSessionHasErrors('captcha');

        $this->assertNoUser();
    }

    public function testRegistrationIsThrottledPerIp()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->register(['captcha' => '']);
        }

        $this->register(['captcha' => ''])->assertStatus(429);
    }
}
