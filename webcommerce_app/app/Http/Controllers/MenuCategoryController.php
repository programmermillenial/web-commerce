<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuCategoryController extends Controller
{
    public function index()
    {
        return view('admin.menu-categories.index');
    }

    public function list(Request $request)
    {
        $query = MenuCategory::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data = $query
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin.menu-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:menu_categories,slug',
            'icon' => 'nullable|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        MenuCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'is_active' => $request->is_active ? 1 : 0,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(string $slug)
    {
        $category = MenuCategory::where('slug', $slug)->firstOrFail();
        return view('admin.menu-categories.edit', compact('category'));
    }

    public function update(Request $request,  string $slug)
    {
        $menu_category = MenuCategory::where('slug', $slug)->firstOrFail();
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:menu_categories,slug,' . $menu_category->id,
            'icon' => 'nullable|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $menu_category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug ?: $request->name),
            'description' => $request->description,
            'is_active' => $request->is_active ? 1 : 0,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(string $slug)
    {
        $menu_category = MenuCategory::where('slug', $slug)->firstOrFail();
        $menu_category->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
