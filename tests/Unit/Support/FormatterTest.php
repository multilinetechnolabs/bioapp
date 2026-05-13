<?php

namespace Tests\Unit\Support;

use App\Support\DateInputFormatter;
use App\Support\EmailContentFormatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testDateInputFormatterNormalizesSupportedFormats()
    {
        $this->assertSame('2026-04-20', DateInputFormatter::toDatabaseDate('04/20/2026'));
        $this->assertSame('2026-04-20', DateInputFormatter::toDatabaseDate('2026-04-20'));
        $this->assertSame('04/20/2026', DateInputFormatter::toDisplayDate('2026-04-20'));
    }

    public function testEmailContentFormatterPreservesParagraphsForHtmlOutput()
    {
        $formatted = EmailContentFormatter::toHtml("First line\nSecond line\n\nThird line");

        $this->assertStringContainsString('<p>First line', $formatted);
        $this->assertStringContainsString("Second line", $formatted);
        $this->assertStringContainsString('<p>Third line</p>', $formatted);
    }

    public function testEmailContentFormatterBuildsReadablePlainText()
    {
        $formatted = EmailContentFormatter::toText('<p>Hello</p><ul><li>One</li><li>Two</li></ul>');

        $this->assertStringContainsString("Hello\n", $formatted);
        $this->assertStringContainsString('- One', $formatted);
        $this->assertStringContainsString('- Two', $formatted);
    }
}
