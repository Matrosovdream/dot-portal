<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\SiteSettings;
use App\Models\User;
use App\Repositories\Settings\SettingsRepo;

class SettingsActions
{
    public function __construct(
        private SettingsRepo $settingsRepo,
    ) {
    }

    /**
     * Return the current site settings as a flat associative map keyed by the
     * canonical setting keys. Missing rows resolve to '' (empty string) to
     * mirror the legacy SiteSettings::get() fallback.
     *
     * @return array<string, string>
     */
    public function index(?User $user = null): array
    {
        return $this->resolveSettingsMap();
    }

    /**
     * Upsert the provided settings over the key/value schema. Only the canonical
     * keys from SiteSettings::getSettingsList() are persisted; any unknown keys
     * in the payload are ignored. Returns the refreshed flat settings map.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, string>
     */
    public function upsert(array $validated, ?User $user = null): array
    {
        foreach ($this->canonicalKeys() as $key) {
            if (! array_key_exists($key, $validated)) {
                continue;
            }

            $this->settingsRepo->getModel()::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $validated[$key]],
            );
        }

        return $this->resolveSettingsMap();
    }

    /**
     * @return array<string, string>
     */
    private function resolveSettingsMap(): array
    {
        $map = [];

        foreach ($this->canonicalKeys() as $key) {
            $map[$key] = (string) (SiteSettings::get($key) ?? '');
        }

        return $map;
    }

    /**
     * @return array<int, string>
     */
    private function canonicalKeys(): array
    {
        return array_map(
            static fn (array $setting): string => $setting['key'],
            SiteSettings::getSettingsList(),
        );
    }
}
