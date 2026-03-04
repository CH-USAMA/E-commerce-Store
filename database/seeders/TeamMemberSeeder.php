<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'name' => 'Naeem Ahmed',
    'designation' => 'CEO Jabulani Group of Companies',
    'location' => NULL,
    'image' => 'images/CEO2.png',
  ],
  1 => 
  [
    'name' => 'Muhammad Zeeshan',
    'designation' => 'senior operations manager',
    'location' => 'Jabulani Group of Companies',
    'image' => 'zeeshan.webp',
  ],
  2 => 
  [
    'name' => 'Mkukwana A. Zenande',
    'designation' => 'Accounts & Finance manager',
    'location' => 'Jabulani Group of Companies',
    'image' => 'zenande.jpeg',
  ],
  3 => 
  [
    'name' => 'Muhammad Kaleem',
    'designation' => 'Retail Operations Supervisor',
    'location' => 'Jabulani Group of Companies',
    'image' => 'kaleem.webp',
  ],
  4 => 
  [
    'name' => 'Nasir Rehman',
    'designation' => 'General Manager',
    'location' => 'Jabulani Hardware Tsolo',
    'image' => 'nasir.webp',
  ],
  5 => 
  [
    'name' => 'Faizan Muhammad',
    'designation' => 'IT Manager',
    'location' => 'Jabulani Group of Companies',
    'image' => 'Faizan.webp',
  ],
  6 => 
  [
    'name' => 'Ayabulela Madlala',
    'designation' => 'HR Admin',
    'location' => 'Jabulani Group of Companies',
    'image' => 'aya.webp',
  ],
  7 => 
  [
    'name' => 'Muhammad Kashif',
    'designation' => 'Store Manager',
    'location' => 'Jabulani Build & Tiles Mt Frere',
    'image' => 'KASHIF.webp',
  ],
  8 => 
  [
    'name' => 'Muhammad Tayyab',
    'designation' => 'Store Manager',
    'location' => 'Jabulani Build & Tiles Qumbu',
    'image' => 'TAYYAB.webp',
  ],
  9 => 
  [
    'name' => 'Nicholas Mhlathi',
    'designation' => 'Mine Manager',
    'location' => 'Jabulani Quarries',
    'image' => 'nichols.webp',
  ],
  10 => 
  [
    'name' => 'Asif Rehman',
    'designation' => 'Store & Operations Manager',
    'location' => 'Moin Hardware Mt Frere',
    'image' => 'ASIF.webp',
  ],
  11 => 
  [
    'name' => 'Nouman Arif',
    'designation' => 'Store Manager',
    'location' => 'Jabulani hardware Mt Frere',
    'image' => 'noman.webp',
  ],
  12 => 
  [
    'name' => 'Sabboor Mehmood',
    'designation' => 'Sales Manager',
    'location' => 'Jabulani Build & Tile Mt frere',
    'image' => 'saboor.webp',
  ],
  13 => 
  [
    'name' => 'Usman Sadiq',
    'designation' => 'Store Manager',
    'location' => 'Jabulani hardware Shinta',
    'image' => 'usman.webp',
  ],
];

        foreach ($data as $item) {
            $matchKey = ['name' => $item['name'] ?? ''];
            if (isset($item['slug'])) $matchKey = ['slug' => $item['slug']];
            elseif (isset($item['title'])) $matchKey = ['title' => $item['title']];
            
            TeamMember::updateOrCreate($matchKey, $item);
        }
    }
}
