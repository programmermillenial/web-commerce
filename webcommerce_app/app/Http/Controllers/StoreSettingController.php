<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\File;

class StoreSettingController extends Controller
{
    function index()
    {
        $setting = StoreSetting::firstOrCreate([]);

        return view('admin.store-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = StoreSetting::firstOrCreate([]);

        $data = $request->validate([
            'store_name' => 'nullable|string|max:100',
            'store_tagline' => 'nullable|string|max:150',
            'store_description' => 'nullable|string',

            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',

            'address' => 'nullable|string',
            'maps_embed' => 'nullable|string',

            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',

            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:100',

            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'is_open' => 'nullable|boolean',

            'tax_percent' => 'nullable|numeric|min:0',
            'service_percent' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',

            'theme_color' => 'nullable|string|max:20',
            'hero_title' => 'nullable|string|max:150',
            'hero_subtitle' => 'nullable|string',

            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'timezone' => 'nullable|string|max:50',
            'maintenance_mode' => 'nullable|boolean',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:1024',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_open'] = $request->has('is_open') ? 1 : 0;
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;

        foreach (['logo', 'favicon', 'hero_image'] as $field) {

            if ($request->hasFile($field)) {

                // hapus file lama
                if ($setting->$field) {
                    $oldPath = base_path('../' . $setting->$field);

                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file = $request->file($field);

                // folder berdasarkan nama field
                $uploadPath = base_path('../images/' . $field);

                // bikin folder kalau belum ada
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // generate nama file
                $filename = time()
                    . '_' . uniqid()
                    . '.' . $file->getClientOriginalExtension();

                // upload file
                $file->move($uploadPath, $filename);

                // simpan path ke DB
                $data[$field] = 'images/' . $field . '/' . $filename;
            }
        }

        $setting->update($data);

        return redirect()
            ->route('store-settings.index')
            ->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}
