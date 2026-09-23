<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Http\Middleware\SecureHeaders;
use App\Models\ContactMessage;
use App\Support\Captcha;
use Illuminate\Support\Facades\Mail;

class ContactCaptchaTest extends TestCase
{
    const CODE = 'ABCDE';

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.site_access_password' => null]);

        // SecureHeaders calls header_remove(), which raises "headers already sent" once PHPUnit
        // has printed anything — unrelated to the captcha, so keep it out of the way.
        $this->withoutMiddleware(SecureHeaders::class);

        Mail::fake();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Contact Tester',
            'email' => 'contact.tester@example.com',
            'subject' => 'Hello',
            'message' => 'This is a test message.',
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

    private function assertNoMessage(): void
    {
        $this->assertDatabaseMissing('contact_messages', ['email' => 'contact.tester@example.com']);
    }

    public function contactRoutes(): array
    {
        return [
            'default (en)' => ['app.contact', 'contact.store'],
            'es' => ['app.contact.es', 'contact.store.es'],
            'fr' => ['app.contact.fr', 'contact.store.fr'],
        ];
    }

    /**
     * @dataProvider contactRoutes
     */
    public function testContactPageShowsTheImageCaptchaAndNoGoogleWidget(string $pageRoute, string $storeRoute)
    {
        $this->get(route($pageRoute))
            ->assertOk()
            ->assertSee('id="contactCaptchaImage"', false)
            ->assertSee('name="captcha"', false)
            ->assertSee(route('captcha.image', [], false), false)
            ->assertDontSee('g-recaptcha', false)
            ->assertDontSee('recaptcha/api.js', false);
    }

    public function testHomePageEmbeddedContactSectionShowsTheImageCaptchaToo()
    {
        $this->get(route('app.home'))
            ->assertOk()
            ->assertSee('id="contactCaptchaImage"', false)
            ->assertSee('name="captcha"', false);
    }

    /**
     * @dataProvider contactRoutes
     */
    public function testSubmissionIsRejectedWithoutACode(string $pageRoute, string $storeRoute)
    {
        $this->issueCode();

        $this->from(route($pageRoute))->post(route($storeRoute), $this->payload(['captcha' => '']))
            ->assertRedirect(route($pageRoute))
            ->assertSessionHasErrors('captcha');

        $this->assertNoMessage();
        Mail::assertNothingSent();
    }

    /**
     * @dataProvider contactRoutes
     */
    public function testSubmissionIsRejectedWithAWrongCodeAndUsesUpTheCode(string $pageRoute, string $storeRoute)
    {
        $this->issueCode();

        $this->from(route($pageRoute))->post(route($storeRoute), $this->payload(['captcha' => 'WRONG']))
            ->assertRedirect(route($pageRoute))
            ->assertSessionHasErrors('captcha')
            ->assertSessionMissing(Captcha::SESSION_KEY);

        $this->assertNoMessage();
        Mail::assertNothingSent();
    }

    public function testSubmissionIsRejectedWhenNoCodeWasEverShown()
    {
        $this->from(route('app.contact'))->post(route('contact.store'), $this->payload())
            ->assertSessionHasErrors('captcha');

        $this->assertNoMessage();
    }

    public function testSubmissionIsRejectedWhenTheCodeHasExpired()
    {
        $this->issueCode(now()->timestamp - 1);

        $this->from(route('app.contact'))->post(route('contact.store'), $this->payload())
            ->assertSessionHasErrors('captcha');

        $this->assertNoMessage();
    }

    /**
     * @dataProvider contactRoutes
     */
    public function testSubmissionSucceedsWithTheRightCodeAndStillSendsTheMessage(string $pageRoute, string $storeRoute)
    {
        $this->issueCode();

        $this->from(route($pageRoute))->post(route($storeRoute), $this->payload(['captcha' => ' ab cde ']))
            ->assertRedirect(route($pageRoute))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('contact.success');

        // email_sent flips to true only after Mail::raw() returns without throwing (ContactController@store),
        // so this is the real signal that sending succeeded — Mail::assertSent() doesn't apply here: it
        // tracks Mailable instances, and Mail::raw() never constructs one.
        $this->assertDatabaseHas('contact_messages', ['email' => 'contact.tester@example.com', 'email_sent' => true]);
    }

    public function testACorrectCodeCannotBeReplayedAfterTheFormFailedForAnotherReason()
    {
        $this->issueCode();

        $this->post(route('contact.store'), $this->payload(['email' => 'not-an-email']))
            ->assertSessionHasErrors('email');

        $this->post(route('contact.store'), $this->payload())
            ->assertSessionHasErrors('captcha');

        $this->assertNoMessage();
    }

    public function testSubmissionIsThrottledPerIpAcrossAllThreeLocaleRoutes()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('contact.store'), $this->payload(['captcha' => '']));
        }

        $this->post(route('contact.store.es'), $this->payload(['captcha' => '']))->assertStatus(429);
    }
}
