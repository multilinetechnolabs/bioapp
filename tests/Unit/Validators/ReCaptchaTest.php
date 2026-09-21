<?php

namespace Tests\Unit\Validators;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Tests\CreatesApplication;

// Extends the framework TestCase directly instead of Tests\TestCase: this rule never
// touches the database, and Tests\TestCase refreshes it and runs passport:install per test.
class ReCaptchaTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Log::spy();
        config(['services.recaptcha.secret_key' => 'test-secret']);
    }

    private function passes($token = 'token'): bool
    {
        return Validator::make(
            ['g-recaptcha-response' => $token],
            ['g-recaptcha-response' => 'required|recaptcha']
        )->passes();
    }

    public function testPassesWhenGoogleConfirmsTheToken()
    {
        Http::fake(['www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true])]);

        $this->assertTrue($this->passes());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://www.google.com/recaptcha/api/siteverify'
                && $request['secret'] === 'test-secret'
                && $request['response'] === 'token';
        });
        Log::shouldNotHaveReceived('warning');
    }

    public function testFailsWhenGoogleRejectsTheTokenAndLogsGooglesReason()
    {
        Http::fake(['*' => Http::response(['success' => false, 'error-codes' => ['invalid-input-secret']])]);

        $this->assertFalse($this->passes());

        Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
            return $context['error_codes'] === ['invalid-input-secret'] && $context['http_status'] === 200;
        })->once();
    }

    public function testFailsInsteadOfThrowingWhenGoogleIsUnreachable()
    {
        Http::fake(function () {
            throw new ConnectionException('cURL error 28: Operation timed out');
        });

        $this->assertFalse($this->passes());

        Log::shouldHaveReceived('warning')->once();
    }

    public function testFailsOnAServerErrorReply()
    {
        Http::fake(['*' => Http::response('Bad Gateway', 502)]);

        $this->assertFalse($this->passes());

        Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
            return $context['http_status'] === 502;
        })->once();
    }

    public function testFailsOnAReplyThatIsNotJson()
    {
        Http::fake(['*' => Http::response('<html>oops</html>', 200)]);

        $this->assertFalse($this->passes());

        Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
            return $context['error_codes'] === null;
        })->once();
    }

    public function testFailsWithoutCallingGoogleWhenTheSecretIsNotConfigured()
    {
        config(['services.recaptcha.secret_key' => null]);
        Http::fake();

        $this->assertFalse($this->passes());

        Http::assertNothingSent();
        Log::shouldHaveReceived('error')->once();
    }

    public function testFailsWithoutCallingGoogleWhenTheTokenIsMissing()
    {
        Http::fake();

        $this->assertFalse($this->passes(''));
        $this->assertFalse($this->passes(null));

        Http::assertNothingSent();
    }
}
