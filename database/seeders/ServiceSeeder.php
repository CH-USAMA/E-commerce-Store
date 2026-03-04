<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'title' => 'Deliveries',
    'slug' => 'deliveries',
    'description' => 'Make transportation hassle-free with our fast and reliable delivery service, straight to your site—saving you time and effort.',
    'icon' => 'fa fa-cog',
    'image' => NULL,
  ],
  1 => 
  [
    'title' => 'Custom Aluminium Products',
    'slug' => 'custom-aluminium-products',
    'description' => 'We offer custom-made aluminium doors and windows tailored to your exact measurements and specifications.',
    'icon' => 'fa fa-cog',
    'image' => NULL,
  ],
  2 => 
  [
    'title' => 'Building Plan Assessment',
    'slug' => 'building-plan-assessment',
    'description' => 'Our experienced staff will assist you in accurately determining the materials needed based on your building plans.',
    'icon' => 'fa fa-cog',
    'image' => NULL,
  ],
  3 => 
  [
    'title' => 'Paint Mixing',
    'slug' => 'paint-mixing',
    'description' => 'Whether you\'re looking for a bold statement shade or a subtle tone, we ensure a precise color match to bring your vision to life.',
    'icon' => 'fa fa-cog',
    'image' => NULL,
  ],
  4 => 
  [
    'title' => 'Glass Cutting',
    'slug' => 'glass-cutting',
    'description' => 'Precision-cut glass tailored to perfectly fit any window frame or door, ensuring a seamless and secure fit.',
    'icon' => 'fa fa-cog',
    'image' => NULL,
  ],
  5 => 
  [
    'title' => 'Board Cutting',
    'slug' => 'board-cutting',
    'description' => 'Precision-cut boards & clean edges tailored to fit your project needs.',
    'icon' => 'fa fa-cog',
    'image' => NULL,
  ],
];

        foreach ($data as $item) {
            Service::updateOrCreate(['name' => $item['name'] ?? ($item['title'] ?? '')], $item);
        }
    }
}
