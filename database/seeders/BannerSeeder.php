<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'title' => 'Jabulani Group of Companies',
    'subtitle' => 'Hardware stores, Crush & Quarry, Building material, Construction solutions',
    'description' => NULL,
    'image' => 'images/TRUCKS2.webp',
    'link' => '/contact',
  ],
  1 => 
  [
    'title' => 'Jabulani Group of Companies',
    'subtitle' => 'Hardware stores, Crush & Quarry, Building material, Construction solutions',
    'description' => NULL,
    'image' => 'images/JBQ7.webp',
    'link' => '/contact',
  ],
  2 => 
  [
    'title' => 'Jabulani Group of Companies',
    'subtitle' => 'Hardware stores, Crush & Quarry, Building material, Construction solutions',
    'description' => NULL,
    'image' => 'images/QTMACH2.webp',
    'link' => '/contact',
  ],
  3 => 
  [
    'title' => 'Jabulani Group of Companies',
    'subtitle' => 'Hardware stores, Crush & Quarry, Building material, Construction solutions',
    'description' => NULL,
    'image' => 'images/YARD2.webp',
    'link' => '/contact',
  ],
  4 => 
  [
    'title' => 'Jabulani Group of Companies',
    'subtitle' => 'Hardware stores, Crush & Quarry, Building material, Construction solutions',
    'description' => NULL,
    'image' => 'images/JB_BT.webp',
    'link' => '/contact',
  ],
];

        foreach ($data as $item) {
            Banner::updateOrCreate(['name' => $item['name'] ?? ($item['title'] ?? '')], $item);
        }
    }
}
