<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class BusinessSettingsController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();
        return view('admin.settings.general', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'membership_fee' => 'required|numeric|min:0',
        ]);

        $setting = Setting::first();
        $setting->update($validated);

        return redirect()->route('settings.edit')->with('success', 'Taxa de adesão atualizada com sucesso.');
    }
}
