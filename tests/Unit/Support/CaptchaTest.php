<?php

namespace Tests\Unit\Support;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Carbon;

use Tests\CreatesApplication;

use App\Support\Captcha;

// Extends the framework TestCase directly instead of Tests\TestCase: the captcha never touches
// the database, and Tests\TestCase refreshes it and runs passport:install for every test.
class CaptchaTest extends TestCase
{
    use CreatesApplication;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function captcha(): Captcha
    {
        return new Captcha();
    }

    public function testGeneratesACodeOfTheRightLengthFromTheAlphabetOnly()
    {
        for ($i = 0; $i < 40; $i++) {
            $code = $this->captcha()->generate();

            $this->assertSame(Captcha::LENGTH, strlen($code));
            $this->assertSame(strlen($code), strspn($code, Captcha::ALPHABET));
        }
    }

    public function testRendersAPngOfTheExpectedSize()
    {
        $png = $this->captcha()->render('A3K9P');

        $this->assertSame("\x89PNG\r\n\x1a\n", substr($png, 0, 8));
        $this->assertSame([Captcha::WIDTH, Captcha::HEIGHT], array_slice(getimagesizefromstring($png), 0, 2));
    }

    public function testMakeReturnsAPngAndStartsACode()
    {
        $png = $this->captcha()->make();

        $this->assertSame("\x89PNG", substr($png, 0, 4));
        $this->assertIsArray(session(Captcha::SESSION_KEY));
    }

    public function testTheSessionHoldsAHashAndNeverTheCodeItself()
    {
        $code = $this->captcha()->generate();
        $entry = session(Captcha::SESSION_KEY);

        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $entry['hash']);
        $this->assertGreaterThan(now()->timestamp, $entry['expires_at']);
        $this->assertStringNotContainsString($code, json_encode(session()->all()));
    }

    public function testAcceptsTheRightCodeIgnoringCaseAndSpaces()
    {
        $code = $this->captcha()->generate();

        $this->assertTrue($this->captcha()->check(' ' . strtolower(substr($code, 0, 2)) . ' ' . substr($code, 2) . ' '));
    }

    public function testRejectsAWrongCode()
    {
        $this->captcha()->generate();

        $this->assertFalse($this->captcha()->check('ZZZZZ'));
    }

    public function testAWrongGuessUsesUpTheCode()
    {
        $code = $this->captcha()->generate();

        $this->assertFalse($this->captcha()->check('ZZZZZ'));
        $this->assertFalse($this->captcha()->check($code));
    }

    public function testACorrectCodeCannotBeUsedTwice()
    {
        $code = $this->captcha()->generate();

        $this->assertTrue($this->captcha()->check($code));
        $this->assertFalse($this->captcha()->check($code));
    }

    public function testRejectsAnExpiredCode()
    {
        $code = $this->captcha()->generate();

        Carbon::setTestNow(now()->addSeconds(Captcha::TTL_SECONDS + 1));

        $this->assertFalse($this->captcha()->check($code));
    }

    public function testStillAcceptsACodeJustBeforeItExpires()
    {
        $code = $this->captcha()->generate();

        Carbon::setTestNow(now()->addSeconds(Captcha::TTL_SECONDS - 1));

        $this->assertTrue($this->captcha()->check($code));
    }

    public function testRejectsWhenNoCodeWasIssued()
    {
        $this->assertFalse($this->captcha()->check('ABCDE'));
    }

    public function testRejectsAnswersThatAreNotAUsableString()
    {
        $code = $this->captcha()->generate();

        $this->assertFalse($this->captcha()->check(null));

        $this->captcha()->generate();
        $this->assertFalse($this->captcha()->check([$code]));

        $this->captcha()->generate();
        $this->assertFalse($this->captcha()->check('   '));
    }
}
