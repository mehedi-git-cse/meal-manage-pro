<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $generalSettings = Setting::where('group', 'general')->get();
        $mealSettings = Setting::where('group', 'meal')->get();
        $emailSettings = Setting::where('group', 'email')->get();
        $systemSettings = Setting::where('group', 'system')->get();

        return view('settings.index', compact('generalSettings', 'mealSettings', 'emailSettings', 'systemSettings'));
    }

    public function update(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings saved successfully.');
    }

    public function clearCache()
    {
        Cache::flush();
        return back()->with('success', 'Cache cleared successfully.');
    }
}
