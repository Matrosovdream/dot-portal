<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key)
    {
        $setting = SiteSettings::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        } else {
            return '';
        }
    }

    public static function set($key, $value)
    {
        $setting = SiteSettings::where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            SiteSettings::create(['key' => $key, 'value' => $value]);
        }
    }

    public static function remove($key)
    {
        SiteSettings::where('key', $key)->delete();
    }

    public static function getSettings()
    {
        $settings = self::getSettingsList();
        $settings = array_map(function ($setting) {
            $setting['value'] = SiteSettings::get($setting['key']);
            return $setting;
        }, $settings);

        return $settings;
    }

    public static function getSettingsList() {

        return [
            ['key' => 'sitename', 'title' => 'Site name', 'type' => 'text', 'description' => ''],
            ['key' => 'email', 'title' => 'Email', 'type' => 'text', 'description' => ''],
            ['key' => 'phone', 'title' => 'Site phone', 'type' => 'text', 'description' => ''],
            ['key' => 'address', 'title' => 'Site address', 'type' => 'text', 'description' => ''],
            ['key' => 'work_time', 'title' => 'Work time', 'type' => 'textarea', 'description' => ''],
            ['key' => 'copyright_text', 'title' => 'Copyright text', 'type' => 'textarea', 'description' => ''],
        ];

    }


}
