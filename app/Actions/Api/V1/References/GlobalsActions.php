<?php

namespace App\Actions\Api\V1\References;

use App\Services\SiteSettingsService;
use Illuminate\Support\Facades\Cache;

class GlobalsActions
{
    public function show(): array
    {
        $settings = Cache::remember('globals:settings', 600, function () {
            try {
                return SiteSettingsService::getAllSettings();
            } catch (\Throwable $e) {
                return [];
            }
        });

        return [
            'site_name' => $settings['site_name'] ?? config('app.name'),
            'currency'  => $settings['currency'] ?? 'USD',
            'settings'  => $settings,
            'feature_flags' => [
                'clearing_house' => true,
                'saferweb'       => true,
            ],
            'locale' => app()->getLocale(),
        ];
    }
}
