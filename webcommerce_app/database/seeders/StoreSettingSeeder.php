<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StoreSetting;

class StoreSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreSetting::updateOrCreate(
            ['id' => 1],
            [
                'store_name' => 'Web Commerce',
                'hero_title' => 'Selamat Datang di Web Commerce',
                'hero_subtitle' => 'Pesan makanan favoritmu dengan mudah',
                'address' => 'Indonesia',
                'phone' => '021-89876556',
                'whatsapp' => '08123456789',
                'email' => 'admin@webcommerce.com',

                'logo' => 'images/logo/1780019547_6a18f15beb5c6.png',
                'favicon' => 'images/favicon/1780019547_6a18f15bebbed.png',
                'hero_image' => 'images/hero_image/1779954965_6a17f515a927b.jpg',

                'tax_percent' => 0,
                'service_percent' => 0,
                'shipping_cost' => 0,

                'bank_name' => 'BCA',
                'bank_account_name' => 'Web Commerce',
                'bank_account_number' => '1234567890',

                'facebook' => 'https://www.facebook.com',
                'tiktok' => 'https://www.tiktok.com',
                'instagram' => 'https://www.instagram.com',

                'is_open' => 1,
                'timezone' => 'Asia/Jakarta',
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=" style="border:0; width:100%; height:300px;" allowfullscreen loading="eager" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            ]
        );
    }
}
