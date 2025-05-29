<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    function dateFormat($date): string {
        return Carbon::parse($date)->format('m/d/Y'); // US format
    }
}

if (!function_exists('formatDateTime')) {
    function dateTimeFormat($date): string {
        return Carbon::parse($date)->format('m/d/Y h:i A'); // US format with AM/PM
    }
}
