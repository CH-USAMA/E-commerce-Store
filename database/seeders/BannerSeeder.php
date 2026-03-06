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
          'image' => 'images/TRUCK (2).webp',
          'link' => '/contact',
        ],
      5 =>
        [
          'title' => 'Jabulani Group of Companies',
          'subtitle' => 'Hardware stores, Crush & Quarry, Building material, Construction solutions',
          'description' => NULL,
          'image' => 'images/JB_BT.webp',
          'link' => '/contact',
        ],
    ];

    foreach ($data as $item) {
      $matchKey = ['name' => $item['name'] ?? ''];
      if (isset($item['slug']))
        $matchKey = ['slug' => $item['slug']];
      elseif (isset($item['title']))
        $matchKey = ['title' => $item['title']];

      Banner::updateOrCreate($matchKey, $item);
    }
  }
}
