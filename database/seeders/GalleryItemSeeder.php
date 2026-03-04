<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GalleryItem;

class GalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/fleet.webp',
    'category' => 'General',
  ],
  1 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/quarry_truck.webp',
    'category' => 'General',
  ],
  2 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/meeting.webp',
    'category' => 'General',
  ],
  3 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/JABULANI BLOCKS.webp',
    'category' => 'General',
  ],
  4 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/hiesters.webp',
    'category' => 'General',
  ],
  5 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/jb_collection.webp',
    'category' => 'General',
  ],
  6 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/Bell.webp',
    'category' => 'General',
  ],
  7 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/quarrypic4.webp',
    'category' => 'General',
  ],
  8 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/TRUK.webp',
    'category' => 'General',
  ],
  9 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/blockyardpic1.webp',
    'category' => 'General',
  ],
  10 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/TRUCK (2].webp',
    'category' => 'General',
  ],
  11 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/big_machine1.webp',
    'category' => 'General',
  ],
  12 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/JB_BT.webp',
    'category' => 'General',
  ],
  13 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/MISSION.JPG',
    'category' => 'General',
  ],
  14 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/YARD2.webp',
    'category' => 'General',
  ],
  15 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/jb_tylon.webp',
    'category' => 'General',
  ],
  16 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/GREEN_TRUCK.webp',
    'category' => 'General',
  ],
  17 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/yellow_fleet.webp',
    'category' => 'General',
  ],
  18 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/sand_trucks.webp',
    'category' => 'General',
  ],
  19 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/quarrypic3.webp',
    'category' => 'General',
  ],
  20 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/window_pro.webp',
    'category' => 'General',
  ],
  21 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/quarrypic1.webp',
    'category' => 'General',
  ],
  22 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/jb_steel.webp',
    'category' => 'General',
  ],
  23 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/logistics_truck.webp',
    'category' => 'General',
  ],
  24 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/blockyardpic2.webp',
    'category' => 'General',
  ],
  25 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/big_machine.webp',
    'category' => 'General',
  ],
  26 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/tile_machine.webp',
    'category' => 'General',
  ],
  27 => 
  [
    'title' => 'Gallery Showcase',
    'image' => 'images/LOADER.webp',
    'category' => 'General',
  ],
];

        foreach ($data as $item) {
            GalleryItem::updateOrCreate(['name' => $item['name'] ?? ($item['title'] ?? '')], $item);
        }
    }
}
