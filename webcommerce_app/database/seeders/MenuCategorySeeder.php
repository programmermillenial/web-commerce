<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan Indonesia',
                'description' => 'Menu khas Indonesia',
                'icon' => 'ri-restaurant-line',
                'sort_order' => 1,
            ],
            [
                'name' => 'Makanan Western',
                'description' => 'Menu western food',
                'icon' => 'ri-cup-line',
                'sort_order' => 2,
            ],
            [
                'name' => 'Minuman',
                'description' => 'Aneka minuman',
                'icon' => 'ri-goblet-line',
                'sort_order' => 3,
            ],
            [
                'name' => 'Dessert',
                'description' => 'Makanan penutup',
                'icon' => 'ri-cake-3-line',
                'sort_order' => 4,
            ],
            [
                'name' => 'Snack & Appetizer',
                'description' => 'Camilan dan pembuka',
                'icon' => 'ri-bowl-line',
                'sort_order' => 5,
            ],
            [
                'name' => 'Coffee',
                'description' => 'Coffee Based Beverage',
                'icon' => 'ri-cup-line',
                'sort_order' => 3,
            ],
            [
                'name' => 'Non Coffee',
                'description' => 'Non Coffee Beverage',
                'icon' => 'ri-goblet-line',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('menu_categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
