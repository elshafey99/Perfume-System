<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function genralSetting()
    {
        $settings = Setting::first();
        return view('dashboard.settings.index', compact('settings'));
    } //End genralSetting method

}
