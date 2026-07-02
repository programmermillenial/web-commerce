<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DownloadMenuImages extends Command
{
    protected $signature = 'menu:download-images';

    protected $description = 'Download menu images from Pexels';

    public function handle(): int
    {
        $apiKey = env('PEXELS_API_KEY');

        if (!$apiKey) {
            $this->error('PEXELS_API_KEY not found in .env');
            return self::FAILURE;
        }

        $menus = [
            // Coffee
            'Espresso',
            'Americano',
            'Cafe Latte',
            'Cappuccino',
            'Flat White',
            'Mocha',
            'Caramel Macchiato',
            'Vanilla Latte',
            'Hazelnut Latte',
            'Kopi Susu Gula Aren',
            'V60 Coffee',
            'Japanese Iced Coffee',
            'Cold Brew Coffee',
            'Affogato',

            // Non Coffee
            'Matcha Latte',
            'Chocolate Latte',
            'Red Velvet Latte',
            'Taro Latte',
            'Thai Tea',
            'Lemon Tea',
            'Lychee Tea',
            'Orange Juice',
            'Avocado Juice',
            'Mineral Water',

            // Indonesian Food
            'Nasi Goreng Spesial',
            'Ayam Bakar Madu',
            'Ayam Geprek',
            'Soto Ayam',
            'Mie Goreng Jawa',
            'Nasi Timbel',
            'Nasi Liwet',
            'Iga Bakar',

            // Western Food
            'Chicken Steak',
            'Beef Steak',
            'Beef Burger',
            'Double Cheeseburger',
            'Fish And Chips',
            'Spaghetti Bolognese',
            'Carbonara Pasta',
            'Chicken Cordon Bleu',

            // Dessert
            'Cheesecake',
            'Chocolate Lava Cake',
            'Tiramisu',
            'Brownies Ice Cream',
            'Pancake Maple Syrup',

            // Snack
            'French Fries',
            'Chicken Wings',
            'Onion Rings',
            'Chicken Popcorn',
            'Mix Platter',
        ];

        // $destination = public_path('uploads/menus');
        $destination = base_path('../images/menu');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        foreach ($menus as $menu) {

            try {

                $filename = Str::slug($menu) . '.jpg';
                $filepath = $destination . '/' . $filename;

                if (File::exists($filepath)) {
                    $this->warn("SKIP : {$filename}");
                    continue;
                }

                $this->info("Searching : {$menu}");

                $response = Http::withHeaders([
                    'Authorization' => $apiKey,
                ])->get('https://api.pexels.com/v1/search', [
                    'query' => $menu,
                    'per_page' => 1,
                ]);

                if (!$response->successful()) {
                    $this->error("Failed search: {$menu}");
                    continue;
                }

                $photo = data_get(
                    $response->json(),
                    'photos.0.src.large'
                );

                if (!$photo) {
                    $this->warn("No image found: {$menu}");
                    continue;
                }

                $image = Http::timeout(60)->get($photo);

                if (!$image->successful()) {
                    $this->error("Failed download: {$menu}");
                    continue;
                }

                File::put($filepath, $image->body());

                $this->info("Downloaded : {$filename}");

                sleep(1);
            } catch (\Throwable $e) {

                $this->error(
                    "{$menu} => " . $e->getMessage()
                );
            }
        }

        $this->newLine();
        $this->info('All images downloaded.');

        return self::SUCCESS;
    }
}
