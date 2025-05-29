<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateFormat
{
    public static function date($date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function dateTime($date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }
}
