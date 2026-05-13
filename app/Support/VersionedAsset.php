<?php

namespace App\Support;

class VersionedAsset
{
    public static function url($path)
    {
        $normalizedPath = ltrim($path, '/');
        $assetUrl = asset($normalizedPath);
        $assetPath = public_path($normalizedPath);

        return $assetUrl;
    }
}
