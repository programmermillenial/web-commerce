<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuImage;
use App\Models\MenuPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    function index()
    {
        return view('admin.menu.index');
    }

    function list()
    {
        $menus = Menu::with([
            'category',
            'prices' => function ($q) {
                $q->where('is_active', 1)
                    ->latest();
            }
        ])->get();

        return response()->json([
            'data' => $menus
        ]);
    }

    function create()
    {
        $categories = MenuCategory::orderBy('name')->get();
        return view('admin.menu.create', compact('categories'));
    }

    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|integer|exists:menu_categories,id',
            'normal_price' => 'required',
            'promo_price' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $menu = Menu::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->slug ?: $request->name),
                'description' => $request->description,
                'is_featured' => $request->is_featured,
                'stock' => 1,
                'is_active' => $request->is_active,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $uploadPath = base_path('../images/menu');

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $filename = time()
                        . '_' . uniqid()
                        . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);

                    $path = 'images/menu/' . $filename;

                    MenuImage::create([
                        'menu_id' => $menu->id,
                        'image_path' => $path,
                        'is_primary' => $index == 0 ? 1 : 0,
                        'sort_order' => $index + 1,
                    ]);

                    if ($index == 0) {
                        $menu->update(['thumbnail' => $path]);
                    }
                }
            }

            $normalPrice = str_replace('.', '', $request->normal_price);
            $promoPrice = !empty($request->promo_price) ? str_replace('.', '', $request->promo_price) : null;
            MenuPrice::create([
                'menu_id' => $menu->id,
                'normal_price' => $normalPrice,
                'promo_price' => $promoPrice,
                'promo_start' => $request->promo_start ?: null,
                'promo_end' => $request->promo_end ?: null,
                'is_active' => 1
            ]);

            DB::commit();

            return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function edit(string $slug)
    {
        $menu = Menu::with(['images', 'prices'])
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = MenuCategory::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    function update(Request $request, string $slug)
    {
        $menu = Menu::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:menus,slug,' . $menu->id,
            'category_id' => 'required|integer|exists:menu_categories,id',
            'normal_price' => 'required',
            'promo_price' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $menu->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->slug ?: $request->name),
                'description' => $request->description,
                'is_featured' => $request->is_featured,
                'is_active' => $request->is_active,
            ]);

            if ($request->hasFile('images')) {
                // HAPUS IMAGE FILE
                $images = MenuImage::where('menu_id', $menu->id)->get();
                foreach ($images as $image) {
                    // hapus file fisik
                    $filePath = realpath($image->image_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                // HAPUS RELASI
                MenuImage::where('menu_id', $menu->id)->delete();

                foreach ($request->file('images') as $index => $image) {
                    $uploadPath = base_path('../images/menu');

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $filename = time()
                        . '_' . uniqid()
                        . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);

                    $path = 'images/menu/' . $filename;

                    MenuImage::create([
                        'menu_id' => $menu->id,
                        'image_path' => $path,
                        'is_primary' => $index == 0 ? 1 : 0,
                        'sort_order' => $index + 1,
                    ]);

                    if ($index == 0) {
                        $menu->update(['thumbnail' => $path]);
                    }
                }
            }

            $normalPrice = str_replace('.', '', $request->normal_price);
            $promoPrice = !empty($request->promo_price) ? str_replace('.', '', $request->promo_price) : null;
            MenuPrice::create([
                'menu_id' => $menu->id,
                'normal_price' => $normalPrice,
                'promo_price' => $promoPrice,
                'promo_start' => $request->promo_start ?: null,
                'promo_end' => $request->promo_end ?: null,
                'is_active' => 1
            ]);

            DB::commit();

            return redirect()->route('menu.index');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function destroy(Menu $menu)
    {
        DB::beginTransaction();

        try {
            // HAPUS IMAGE FILE
            $images = MenuImage::where('menu_id', $menu->id)->get();
            foreach ($images as $image) {
                // hapus file fisik
                $filePath = realpath($image->image_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // HAPUS RELASI
            MenuImage::where('menu_id', $menu->id)->delete();
            MenuPrice::where('menu_id', $menu->id)->delete();

            // HAPUS MENU
            $menu->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Menu berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
