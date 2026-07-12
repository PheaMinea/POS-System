<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $validated = $request->validate([
            'shop_name'         => 'required|string|max:255',
            'shop_email'        => 'nullable|email|max:255',
            'shop_phone'        => 'nullable|string|max:50',
            'shop_address'      => 'nullable|string|max:1000',
            'currency_symbol'   => 'required|string|max:10',
            'currency_position' => 'required|in:before,after',
            'vat_percentage'    => 'required|numeric|min:0|max:100',
            'shop_logo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('shop_logo')) {
            if ($setting->shop_logo) {
                Storage::disk('public')->delete($setting->shop_logo);
            }
            $validated['shop_logo'] = $request->file('shop_logo')->store('settings', 'public');
        }

        $setting->fill($validated);
        $setting->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Shop settings updated successfully.');
    }
}
