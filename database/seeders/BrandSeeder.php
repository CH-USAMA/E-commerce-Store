<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'name' => 'Jab Logo',
    'slug' => 'jab-logo',
    'logo' => 'images/jab_logo.png',
  ],
  1 => 
  [
    'name' => 'Ingco Logo',
    'slug' => 'ingco-logo',
    'logo' => 'images/Ingco_Logo.png',
  ],
  2 => 
  [
    'name' => 'Hendok',
    'slug' => 'hendok',
    'logo' => 'images/hendok.png',
  ],
  3 => 
  [
    'name' => 'Harvey',
    'slug' => 'harvey',
    'logo' => 'images/harvey.png',
  ],
  4 => 
  [
    'name' => 'Makita',
    'slug' => 'makita',
    'logo' => 'images/makita.png',
  ],
  5 => 
  [
    'name' => 'Duramlogo',
    'slug' => 'duramlogo',
    'logo' => 'images/Duramlogo.png',
  ],
  6 => 
  [
    'name' => 'Afrisam',
    'slug' => 'afrisam',
    'logo' => 'images/Afrisam.png',
  ],
  7 => 
  [
    'name' => 'PGBISON',
    'slug' => 'pgbison',
    'logo' => 'images/PGBISON.png',
  ],
  8 => 
  [
    'name' => 'Lasher',
    'slug' => 'lasher',
    'logo' => 'images/Lasher.png',
  ],
  9 => 
  [
    'name' => 'Roofco',
    'slug' => 'roofco',
    'logo' => 'images/Roofco.png',
  ],
  10 => 
  [
    'name' => 'Geo',
    'slug' => 'geo',
    'logo' => 'images/geo.png',
  ],
  11 => 
  [
    'name' => 'Jojotanks',
    'slug' => 'jojotanks',
    'logo' => 'images/jojotanks.png',
  ],
  12 => 
  [
    'name' => 'Eureka',
    'slug' => 'eureka',
    'logo' => 'images/Eureka.png',
  ],
  13 => 
  [
    'name' => 'Flash',
    'slug' => 'flash',
    'logo' => 'images/Flash.png',
  ],
  14 => 
  [
    'name' => 'Corobrik',
    'slug' => 'corobrik',
    'logo' => 'images/Corobrik.png',
  ],
];

        foreach ($data as $item) {
            Brand::updateOrCreate(['name' => $item['name'] ?? ($item['title'] ?? '')], $item);
        }
    }
}
