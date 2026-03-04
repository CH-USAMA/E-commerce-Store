<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'name' => 'Jabulani
                                            Products',
    'slug' => 'jabulani-products',
    'parent_id' => NULL,
  ],
  1 => 
  [
    'name' => 'Building
                                            Materials',
    'slug' => 'building-materials',
    'parent_id' => NULL,
  ],
  2 => 
  [
    'name' => 'Building
                                            Materails',
    'slug' => 'building-materails',
    'parent_id' => NULL,
  ],
  3 => 
  [
    'name' => 'Roof
                                            & Ceiling',
    'slug' => 'roof-ceiling',
    'parent_id' => NULL,
  ],
  4 => 
  [
    'name' => 'Tiles
                                            & Flooring',
    'slug' => 'tiles-flooring',
    'parent_id' => NULL,
  ],
  5 => 
  [
    'name' => 'Boards
                                            & Timber',
    'slug' => 'boards-timber',
    'parent_id' => NULL,
  ],
  6 => 
  [
    'name' => 'Hardware
                                            & Tools',
    'slug' => 'hardware-tools',
    'parent_id' => NULL,
  ],
  7 => 
  [
    'name' => 'Paint',
    'slug' => 'paint',
    'parent_id' => NULL,
  ],
  8 => 
  [
    'name' => 'Bathroom
                                            & Kitchen',
    'slug' => 'bathroom-kitchen',
    'parent_id' => NULL,
  ],
  9 => 
  [
    'name' => 'Plumbing
                                            Materials',
    'slug' => 'plumbing-materials',
    'parent_id' => NULL,
  ],
  10 => 
  [
    'name' => 'Plumbing
                                            & Electrical',
    'slug' => 'plumbing-electrical',
    'parent_id' => NULL,
  ],
];

        foreach ($data as $item) {
            $matchKey = ['name' => $item['name'] ?? ''];
            if (isset($item['slug'])) $matchKey = ['slug' => $item['slug']];
            elseif (isset($item['title'])) $matchKey = ['title' => $item['title']];
            
            Category::updateOrCreate($matchKey, $item);
        }
    }
}
