<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Indonesia
            [
                'category' => 'Makanan Indonesia',
                'name' => 'Nasi Goreng Spesial',
                'price' => 35000,
            ],
            [
                'category' => 'Makanan Indonesia',
                'name' => 'Ayam Bakar Madu',
                'price' => 42000,
            ],
            [
                'category' => 'Makanan Indonesia',
                'name' => 'Soto Ayam',
                'price' => 28000,
            ],
            [
                'category' => 'Makanan Indonesia',
                'name' => 'Mie Goreng Jawa',
                'price' => 32000,
            ],

            // Western
            [
                'category' => 'Makanan Western',
                'name' => 'Chicken Steak',
                'price' => 55000,
            ],
            [
                'category' => 'Makanan Western',
                'name' => 'Beef Burger',
                'price' => 50000,
            ],
            [
                'category' => 'Makanan Western',
                'name' => 'Spaghetti Bolognese',
                'price' => 48000,
            ],
            [
                'category' => 'Makanan Western',
                'name' => 'Fish and Chips',
                'price' => 58000,
            ],

            // Minuman
            [
                'category' => 'Minuman',
                'name' => 'Es Teh Manis',
                'price' => 10000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Kopi Susu Gula Aren',
                'price' => 25000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Cappuccino',
                'price' => 28000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Orange Juice',
                'price' => 22000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Espresso',
                'price' => 18000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Americano',
                'price' => 22000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Cafe Latte',
                'price' => 28000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Flat White',
                'price' => 30000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Mocha',
                'price' => 32000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Caramel Macchiato',
                'price' => 35000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Vietnam Drip',
                'price' => 27000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'V60',
                'price' => 30000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Japanese Iced Coffee',
                'price' => 32000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Cold Brew',
                'price' => 33000,
            ],
            [
                'category' => 'Minuman',
                'name' => 'Affogato',
                'price' => 35000,
            ],

            // Dessert
            [
                'category' => 'Dessert',
                'name' => 'Chocolate Lava Cake',
                'price' => 32000,
            ],
            [
                'category' => 'Dessert',
                'name' => 'Cheesecake',
                'price' => 30000,
            ],

            // Snack
            [
                'category' => 'Snack & Appetizer',
                'name' => 'French Fries',
                'price' => 22000,
            ],
            [
                'category' => 'Snack & Appetizer',
                'name' => 'Chicken Wings',
                'price' => 35000,
            ],
        ];

        foreach ($menus as $item) {

            $imagePath = 'images/menu/' . Str::slug($item['name']) . '.jpg';

            $categoryId = DB::table('menu_categories')
                ->where('name', $item['category'])
                ->value('id');

            $menuId = DB::table('menus')->insertGetId([
                'category_id' => $categoryId,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'thumbnail' => $imagePath,
                'description' => $item['name'],
                'stock' => rand(20, 100),
                'is_featured' => rand(0, 1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('menu_prices')->insert([
                'menu_id' => $menuId,
                'normal_price' => $item['price'],
                'promo_price' => null,
                'promo_start' => null,
                'promo_end' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            DB::table('menu_images')->insert([
                'menu_id' => $menuId,
                'image_path' => $imagePath,
                'is_primary' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
