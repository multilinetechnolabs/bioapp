<?php

namespace App\Support;

use Carbon\Carbon;

class DateInputFormatter
{
    public static function toDatabaseDate($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return $value;
        }

        $formats = ['Y-m-d', 'm/d/Y', 'n/j/Y', 'Y/m/d', 'm-d-Y', 'n-j-Y'];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);

                if ($date !== false && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public static function toDisplayDate($value)
    {
        $databaseDate = self::toDatabaseDate($value);

        if ($databaseDate === '') {
            return '';
        }

        try {
            return Carbon::parse($databaseDate)->format('m/d/Y');
        } catch (\Throwable $e) {
            return $value;
        }
    }
}
