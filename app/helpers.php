<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    function formatDate($date): string {
        return Carbon::parse($date)->format('Y-m-d');
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($date): string {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }
}
