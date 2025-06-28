<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Apple iPhone 15 Pro',
                'price' => 1199,
                'stock' => 10,
                'category' => 'Electronic Devices',
                'is_offer_pool' => false,
                'reward_points' => null,
                'image' => null,
                'image_url' => 'https://m.media-amazon.com/images/I/714yEDE9yPL.__AC_SX300_SY300_QL70_ML2_.jpg',
                'description' => 'Latest Apple iPhone 15 Pro with A17 chip.'
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'price' => 1099,
                'stock' => 8,
                'category' => 'Electronic Devices',
                'is_offer_pool' => true,
                'reward_points' => 1000,
                'image' => null,
                'image_url' => 'https://images.samsung.com/is/image/samsung/p6pim/levant/galaxy-s24-ultra/gallery/levant-galaxy-s24-ultra-sm-s928bzkgmea-thumb-538684237',
                'description' => 'Flagship Samsung phone with S Pen.'
            ],
            [
                'name' => 'Dyson V15 Detect Vacuum',
                'price' => 699,
                'stock' => 5,
                'category' => 'Home Essentials',
                'is_offer_pool' => false,
                'reward_points' => null,
                'image' => null,
                'image_url' => 'https://dyson-h.assetsadobe2.com/is/image/content/dam/dyson/images/products/primary/447029-01.png',
                'description' => 'Powerful cordless vacuum cleaner.'
            ],
            [
                'name' => 'Nespresso Vertuo Next',
                'price' => 199,
                'stock' => 15,
                'category' => 'Kitchen Devices',
                'is_offer_pool' => true,
                'reward_points' => 300,
                'image' => null,
                'image_url' => 'https://m.media-amazon.com/images/I/61zCDBqIwzL._AC_UL480_FMwebp_QL65_.jpg',
                'description' => 'Coffee machine for home baristas.'
            ],
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'price' => 399,
                'stock' => 12,
                'category' => 'Electronic Devices',
                'is_offer_pool' => false,
                'reward_points' => null,
                'image' => null,
                'image_url' => 'https://m.media-amazon.com/images/I/514bkhGFloL._AC_UL480_FMwebp_QL65_.jpg',
                'description' => 'Industry-leading noise cancelling headphones.'
            ],
    [
        'name' => 'Apple iPhone 15 Pro Max',
        'price' => 1199,
        'stock' => 25,
        'category' => 'Electronic Devices',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-pro-max-finish-select-202309-6-7inch-titanium-blue',
        'description' => 'Apple iPhone 15 Pro Max with A17 Pro chip and 5x Telephoto camera.'
    ],
    [
        'name' => 'Samsung Galaxy S24 Ultra',
        'price' => 1299,
        'stock' => 18,
        'category' => 'Electronic Devices',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/719HV2e6-sL._AC_SL1500_.jpg',
        'description' => 'Samsung Galaxy S24 Ultra with Snapdragon 8 Gen 3 and 200MP camera.'
    ],
    [
        'name' => 'Apple MacBook Air M3 13-inch',
        'price' => 1099,
        'stock' => 9,
        'category' => 'Laptops',
        'is_offer_pool' => true,
        'reward_points' => 300,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/71hcXQNLrHL._AC_UL480_FMwebp_QL65_.jpg',
        'description' => 'Lightweight 13-inch MacBook Air with Apple M3 chip.'
    ],
    [
        'name' => 'Sony PlayStation 5',
        'price' => 499,
        'stock' => 15,
        'category' => 'Gaming',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/619BkvKW35L._SL1500_.jpg',
        'description' => 'Sony PlayStation 5 Console with DualSense controller.'
    ],
    [
        'name' => 'Dell XPS 15 9530',
        'price' => 1899,
        'stock' => 7,
        'category' => 'Laptops',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/71JPy7gSTyL._AC_UL480_FMwebp_QL65_.jpg',
        'description' => 'Dell XPS 15 with Intel Core i9 and 4K OLED display.'
    ],
    [
        'name' => 'Apple Watch Series 9',
        'price' => 399,
        'stock' => 22,
        'category' => 'Wearables',
        'is_offer_pool' => true,
        'reward_points' => 120,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/71aXGgNCE9L._AC_UL480_FMwebp_QL65_.jpg',
        'description' => 'Smartwatch with advanced health features and always-on display.'
    ],
    [
        'name' => 'Nikon Z6 II Mirrorless Camera',
        'price' => 1999,
        'stock' => 6,
        'category' => 'Cameras',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://cdn.mos.cms.futurecdn.net/n4DWbBkjKAtNfQUv6ZWRCD.jpg',
        'description' => 'Full-frame mirrorless camera with dual processors and 273-point AF.'
    ],
    [
        'name' => 'Bose QuietComfort Ultra Headphones',
        'price' => 429,
        'stock' => 11,
        'category' => 'Audio',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://assets.bose.com/content/dam/Bose_DAM/Web/consumer_electronics/global/products/headphones/quietcomfort_ultra_headphones/product_silo_images/QCUH_Black_EC_hero.psd/jcr:content/renditions/cq5dam.web.320.320.png',
        'description' => 'Premium noise-canceling headphones with spatial audio.'
    ],
    [
        'name' => 'Asus ROG Strix Scar 16',
        'price' => 2499,
        'stock' => 5,
        'category' => 'Gaming Laptops',
        'is_offer_pool' => true,
        'reward_points' => 500,
        'image' => null,
        'image_url' => 'https://dlcdnwebimgs.asus.com/gain/591B6F4E-31DE-4DD2-BE30-601B3DB0289C/fwebp',
        'description' => 'High-performance gaming laptop with RTX 4080 and Mini LED display.'
    ],
    [
        'name' => 'Google Pixel 8 Pro',
        'price' => 999,
        'stock' => 14,
        'category' => 'Electronic Devices',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://store.google.com/product/images/google_pixel_8_pro_thumbnail.png',
        'description' => 'Google’s flagship phone with Tensor G3 and advanced AI features.'
    ],
    [
        'name' => 'GoPro HERO12 Black',
        'price' => 399,
        'stock' => 20,
        'category' => 'Cameras',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://gopro.com/content/dam/gopro/us/en/products/cameras/hero12-black/gallery/hero12-black-gallery-1.jpg',
        'description' => 'Rugged action camera with 5.3K video and stabilization.'
    ],
    [
        'name' => 'JBL Flip 6 Bluetooth Speaker',
        'price' => 129,
        'stock' => 35,
        'category' => 'Audio',
        'is_offer_pool' => false,
        'reward_points' => 50,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/71NZxODnaJL._AC_SL1500_.jpg',
        'description' => 'Portable waterproof Bluetooth speaker with deep bass.'
    ],
    [
        'name' => 'Logitech MX Master 3S Mouse',
        'price' => 99,
        'stock' => 40,
        'category' => 'Accessories',
        'is_offer_pool' => false,
        'reward_points' => null,
        'image' => null,
        'image_url' => 'https://resource.logitech.com/content/dam/logitech/en/products/mice/mx-master-3s/gallery/mx-master-3s-gallery-graphite-top.png',
        'description' => 'Advanced ergonomic mouse with silent clicks and MagSpeed scroll.'
    ],
    [
        'name' => 'Kindle Paperwhite (11th Gen)',
        'price' => 149,
        'stock' => 30,
        'category' => 'E-Readers',
        'is_offer_pool' => true,
        'reward_points' => 30,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/61fIhATcKDL._AC_SL1000_.jpg',
        'description' => 'Waterproof e-reader with a 6.8” display and adjustable warm light.'
    ],
    [
        'name' => 'DJI Mini 4 Pro Drone',
        'price' => 759,
        'stock' => 10,
        'category' => 'Drones',
        'is_offer_pool' => false,
        'reward_points' => 150,
        'image' => null,
        'image_url' => 'https://m.media-amazon.com/images/I/61HXuYPZayL._AC_SL1500_.jpg',
        'description' => 'Compact drone with 4K HDR video and omnidirectional obstacle sensing.'
    ],
];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
