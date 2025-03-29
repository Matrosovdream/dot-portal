<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use Illuminate\Http\Request;


class DashboardSettingsController extends Controller
{
    
    public function index()
    {

        $data = [
            'title' => 'Settings',
            'page' => 'settings',
            'settings' => SiteSettings::getSettings()
        ];

        //dd($data['settings']);

        return view('dashboard.settings.index', $data);
    }

    public function store(Request $request) {

        $settings = SiteSettings::getSettingsList();

        foreach ($settings as $setting) {
            SiteSettings::set($setting['key'], $request->input($setting['key']));
        }

        return redirect()->route('dashboard.settings.index')->with('success', 'Settings updated successfully');

    }

}
