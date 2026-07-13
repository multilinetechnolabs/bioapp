<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'cert_eyebrow', 'cert_title', 'cert_intro', 'cert_body', 'cert_disclaimer',
        'issuer_name', 'issuer_email', 'accent_color',
        'badge_enabled', 'badge_label', 'badge_caption', 'badge_subtext',
    ];

    protected $casts = [
        'badge_enabled' => 'boolean',
    ];

    /**
     * The single certificate-template config row, created with sensible defaults
     * (matching the original hard-coded certificate) if it doesn't exist yet.
     */
    public static function current(): self
    {
        $template = static::first();

        if ($template) {
            return $template;
        }

        return static::create(self::defaults());
    }

    public static function defaults(): array
    {
        return [
            'cert_eyebrow' => 'Certificate of Completion',
            'cert_title' => "Anew Avenue Biomagnetism\nCertification Course",
            'cert_intro' => 'This certifies that',
            'cert_body' => 'has successfully completed all {lessons} lessons of the {course}, demonstrating mastery of advanced Chakra Mapping and Biomagnetic Pair protocols.',
            'cert_disclaimer' => 'This certificate confirms completion of the course for personal application and professional practice, but does not convey authorization or licensure to teach or re-brand this curriculum.',
            'issuer_name' => 'Anew Avenue Biomagnetism',
            'issuer_email' => config('mail.from.address', env('MAIL_FROM_ADDRESS')),
            'accent_color' => '#14b8a6',
            'badge_enabled' => true,
            'badge_label' => 'CERTIFIED',
            'badge_caption' => 'Certified in the Chakra Biomagnetism Method',
            'badge_subtext' => 'Verifies completion of this course and app training — not a state-issued medical license.',
        ];
    }

    /**
     * Resolve {name} {course} {lessons} {date} {issuer} placeholders in any field.
     */
    public function render(?string $text, array $data = []): string
    {
        $replacements = [
            '{name}' => $data['name'] ?? '',
            '{course}' => $data['course'] ?? '',
            '{lessons}' => $data['lessons'] ?? '',
            '{date}' => $data['date'] ?? '',
            '{issuer}' => $this->issuer_name ?? '',
        ];

        return strtr((string) $text, $replacements);
    }
}
