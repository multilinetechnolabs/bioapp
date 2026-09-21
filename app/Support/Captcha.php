<?php

namespace App\Support;

// Self-hosted image captcha: draws a short code into a PNG with GD and keeps only a keyed
// hash of it in the session. A code is good for one attempt (right or wrong) and expires.
class Captcha
{
    const SESSION_KEY = 'captcha';
    const TTL_SECONDS = 600;
    const LENGTH = 5;

    // Leaves out 0/O, 1/I/L, 2/Z, 5/S and 8/B, the pairs people mix up most in a distorted image.
    const ALPHABET = 'ACDEFGHJKMNPQRTUVWXY346789';

    const WIDTH = 170;
    const HEIGHT = 56;

    // Starts a new code, remembers it in the session and returns the PNG that shows it.
    public function make(): string
    {
        return $this->render($this->generate());
    }

    public function generate(): string
    {
        $code = '';

        for ($i = 0; $i < self::LENGTH; $i++) {
            $code .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
        }

        session()->put(self::SESSION_KEY, $this->entryFor($code));

        return $code;
    }

    public function entryFor(string $code): array
    {
        return [
            'hash' => $this->hash($code),
            'expires_at' => now()->timestamp + self::TTL_SECONDS,
        ];
    }

    // The stored code is consumed whether or not the guess is right, so every new guess
    // needs a fresh image. Case and stray spaces in the answer don't matter.
    public function check($input): bool
    {
        $entry = session()->pull(self::SESSION_KEY);

        if (!is_array($entry) || !is_string($input) || ($entry['expires_at'] ?? 0) < now()->timestamp) {
            return false;
        }

        $input = strtoupper(preg_replace('/\s+/', '', $input));

        return $input !== '' && hash_equals((string) ($entry['hash'] ?? ''), $this->hash($input));
    }

    public function render(string $code): string
    {
        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagefilledrectangle($canvas, 0, 0, self::WIDTH, self::HEIGHT, imagecolorallocate($canvas, 244, 247, 250));

        $this->scatterDots($canvas, 110);
        $this->strikeLines($canvas, 3, 175, 220);

        $length = strlen($code);
        $slot = (self::WIDTH - 24) / $length;

        for ($i = 0; $i < $length; $i++) {
            $glyph = $this->glyph($code[$i]);
            $x = (int) (12 + $i * $slot + random_int(-2, 3));
            $y = (int) ((self::HEIGHT - imagesy($glyph)) / 2 + random_int(-3, 3));

            imagecopy($canvas, $glyph, $x, $y, 0, 0, imagesx($glyph), imagesy($glyph));
            imagedestroy($glyph);
        }

        $this->strikeLines($canvas, 2, 120, 185);

        ob_start();
        imagepng($canvas);
        $png = ob_get_clean();
        imagedestroy($canvas);

        return $png;
    }

    // One letter, enlarged and tilted. Uses GD's built-in 9x15 font, which every GD build has,
    // so nothing depends on FreeType or a font file being present on the server.
    private function glyph(string $char)
    {
        $small = imagecreatetruecolor(9, 15);
        imagealphablending($small, false);
        imagefill($small, 0, 0, imagecolorallocatealpha($small, 0, 0, 0, 127));
        imagealphablending($small, true);
        imagestring($small, 5, 0, 0, $char, imagecolorallocate($small, random_int(0, 80), random_int(0, 80), random_int(0, 110)));

        $big = imagecreatetruecolor(27, 45);
        imagealphablending($big, false);
        imagesavealpha($big, true);
        imagefill($big, 0, 0, imagecolorallocatealpha($big, 0, 0, 0, 127));
        imagecopyresampled($big, $small, 0, 0, 0, 0, 27, 45, 9, 15);
        imagedestroy($small);

        $tilted = imagerotate($big, random_int(-14, 14), imagecolorallocatealpha($big, 0, 0, 0, 127));
        imagedestroy($big);
        imagealphablending($tilted, false);
        imagesavealpha($tilted, true);

        return $tilted;
    }

    private function scatterDots($canvas, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $shade = random_int(140, 215);

            imagesetpixel(
                $canvas,
                random_int(0, self::WIDTH - 1),
                random_int(0, self::HEIGHT - 1),
                imagecolorallocate($canvas, $shade, $shade, random_int($shade, 235))
            );
        }
    }

    private function strikeLines($canvas, int $count, int $min, int $max): void
    {
        for ($i = 0; $i < $count; $i++) {
            $color = imagecolorallocate($canvas, random_int($min, $max), random_int($min, $max), random_int($min, $max));

            imageline($canvas, random_int(0, 20), random_int(0, self::HEIGHT), random_int(self::WIDTH - 20, self::WIDTH), random_int(0, self::HEIGHT), $color);
        }
    }

    private function hash(string $code): string
    {
        return hash_hmac('sha256', $code, (string) config('app.key'));
    }
}
