<?php

namespace App\Support;

class EmailContentFormatter
{
    public static function toHtml($content)
    {
        $content = self::normalize($content);

        if ($content === '') {
            return '';
        }

        if (self::containsHtml($content)) {
            return $content;
        }

        $paragraphs = preg_split("/\n{2,}/", $content);
        $formatted = array_map(function ($paragraph) {
            return '<p>'.nl2br(e(trim($paragraph))).'</p>';
        }, array_filter($paragraphs, function ($paragraph) {
            return trim($paragraph) !== '';
        }));

        return implode("\n", $formatted);
    }

    public static function toText($content)
    {
        $content = self::normalize($content);

        if ($content === '') {
            return '';
        }

        if (self::containsHtml($content)) {
            $content = preg_replace('/<(\/p|\/div|\/h[1-6])\s*>|<br\s*\/?>/i', "\n", $content);
            $content = preg_replace('/<li[^>]*>/i', '- ', $content);
            $content = preg_replace('/<\/li>/i', "\n", $content);
            $content = strip_tags($content);
            $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
        }

        $content = preg_replace("/[ \t]+\n/", "\n", $content);
        $content = preg_replace("/\n{3,}/", "\n\n", trim($content));

        return $content;
    }

    protected static function normalize($content)
    {
        return trim(str_replace(["\r\n", "\r"], "\n", (string) $content));
    }

    protected static function containsHtml($content)
    {
        return $content !== strip_tags($content);
    }
}
