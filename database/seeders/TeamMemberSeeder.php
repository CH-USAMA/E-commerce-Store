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
      [
        'name' => 'Muhammad Zeeshan',
        'designation' => 'senior operations manager',
        'location' => 'Jabulani Group of Companies',
        'image' => 'images/zeeshan.webp',
      ],
      [
        'name' => 'Mkukwana A. Zenande',
        'designation' => 'Accounts & Finance manager',
        'location' => 'Jabulani Group of Companies',
        'image' => 'images/zenande.jpeg',
      ],
      [
        'name' => 'Muhammad Kaleem',
        'designation' => 'Retail Operations Supervisor',
        'location' => 'Jabulani Group of Companies',
        'image' => 'images/kaleem.webp',
      ],
      [
        'name' => 'Nasir Rehman',
        'designation' => 'General Manager',
        'location' => 'Jabulani Hardware Tsolo',
        'image' => 'images/nasir.webp',
      ],
      [
        'name' => 'Faizan Muhammad',
        'designation' => 'IT Manager',
        'location' => 'Jabulani Group of Companies',
        'image' => 'images/Faizan.webp',
      ],
      [
        'name' => 'Ayabulela Madlala',
        'designation' => 'HR Admin',
        'location' => 'Jabulani Group of Companies',
        'image' => 'images/aya.webp',
      ],
      [
        'name' => 'Muhammad Kashif',
        'designation' => 'Store Manager',
        'location' => 'Jabulani Build & Tiles Mt Frere',
        'image' => 'images/KASHIF.webp',
      ],
      [
        'name' => 'Muhammad Tayyab',
        'designation' => 'Store Manager',
        'location' => 'Jabulani Build & Tiles Qumbu',
        'image' => 'images/TAYYAB.webp',
      ],
      [
        'name' => 'Nicholas Mhlathi',
        'designation' => 'Mine Manager',
        'location' => 'Jabulani Quarries',
        'image' => 'images/nichols.webp',
      ],
      [
        'name' => 'Asif Rehman',
        'designation' => 'Store & Operations Manager',
        'location' => 'Moin Hardware Mt Frere',
        'image' => 'images/ASIF.webp',
      ],
      [
        'name' => 'Nouman Arif',
        'designation' => 'Store Manager',
        'location' => 'Jabulani hardware Mt Frere',
        'image' => 'images/noman.webp',
      ],
      [
        'name' => 'Sabboor Mehmood',
        'designation' => 'Sales Manager',
        'location' => 'Jabulani Build & Tile Mt frere',
        'image' => 'images/saboor.webp',
      ],
      [
        'name' => 'Usman Sadiq',
        'designation' => 'Store Manager',
        'location' => 'Jabulani hardware Shinta',
        'image' => 'images/usman.webp',
      ],
    ];

    foreach ($data as $item) {
      $matchKey = ['name' => $item['name'] ?? ''];
      if (isset($item['slug']))
        $matchKey = ['slug' => $item['slug']];
      elseif (isset($item['title']))
        $matchKey = ['title' => $item['title']];

      TeamMember::updateOrCreate($matchKey, $item);
    }
  }
}
