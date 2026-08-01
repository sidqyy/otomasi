<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $promptSetting = Setting::firstOrCreate(
            ['key' => 'ai_system_prompt'],
            ['value' => '', 'description' => 'Instruksi Utama (System Prompt) untuk AI Bot']
        );

        return view('admin.settings.index', compact('promptSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ai_system_prompt' => 'nullable|string'
        ]);

        Setting::updateOrCreate(
            ['key' => 'ai_system_prompt'],
            ['value' => $request->ai_system_prompt]
        );

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
