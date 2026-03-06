<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
  public function run(): void
  {
    $data = [
      [
        'title' => 'Deliveries',
        'slug' => 'deliveries',
        'description' => 'Make transportation hassle-free with our fast and reliable delivery service, straight to your site—saving you time and effort.',
        'icon' => 'fas fa-truck-fast',
        'image' => 'images/Delivery.webp',
      ],
      [
        'title' => 'Custom Aluminium Products',
        'slug' => 'custom-aluminium-products',
        'description' => 'We offer custom-made aluminium doors and windows tailored to your exact measurements and specifications.',
        'icon' => 'fas fa-window-maximize',
        'image' => 'images/custom_aluminium.webp',
      ],
      [
        'title' => 'Building Plan Assessment',
        'slug' => 'building-plan-assessment',
        'description' => 'Our experienced staff will assist you in accurately determining the materials needed based on your building plans.',
        'icon' => 'fas fa-file-invoice',
        'image' => 'images/Building_plans.webp',
      ],
      [
        'title' => 'Paint Mixing',
        'slug' => 'paint-mixing',
        'description' => 'Whether you\'re looking for a bold statement shade or a subtle tone, we ensure a precise color match to bring your vision to life.',
        'icon' => 'fas fa-fill-drip',
        'image' => 'images/paint-mixing.png',
      ],
      [
        'title' => 'Glass Cutting',
        'slug' => 'glass-cutting',
        'description' => 'Precision-cut glass tailored to perfectly fit any window frame or door, ensuring a seamless and secure fit.',
        'icon' => 'fas fa-border-all',
        'image' => 'images/glass_cutting.webp',
      ],
      [
        'title' => 'Board Cutting',
        'slug' => 'board-cutting',
        'description' => 'Precision-cut boards & clean edges tailored to fit your project needs.',
        'icon' => 'fas fa-scissors',
        'image' => 'images/board_cutting.webp',
      ],
    ];


    foreach ($data as $item) {
      $matchKey = ['name' => $item['name'] ?? ''];
      if (isset($item['slug']))
        $matchKey = ['slug' => $item['slug']];
      elseif (isset($item['title']))
        $matchKey = ['title' => $item['title']];

      Service::updateOrCreate($matchKey, $item);
    }
  }
}
