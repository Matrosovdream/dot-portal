<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\SiteSettings;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (SiteSettings::getSettingsList() as $setting) {
            $rules[$setting['key']] = ['sometimes', 'nullable', 'string'];
        }

        return $rules;
    }
}
