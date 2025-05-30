<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    function dateFormat( $date=null ): string|null {
        if (is_null($date) || $date === '') {
            return null;
        }
        return Carbon::parse($date)->format('m/d/Y'); // US format
    }
}

if (!function_exists('formatDateTime')) {
    function dateTimeFormat( $date=null ): string|null {
        if (is_null($date) || $date === '') {
            return null;
        }
        return Carbon::parse($date)->format('m/d/Y h:i A'); // US format with AM/PM
    }
}

if (!function_exists('siteSettings')) {
    function siteSettings(): array {
        
        return \App\Services\SiteSettingsService::getAllSettings();

    }
}