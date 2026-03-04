<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
  0 => 
  [
    'name' => 'General',
    'slug' => 'general',
  ],
];

        foreach ($data as $item) {
            $matchKey = ['name' => $item['name'] ?? ''];
            if (isset($item['slug'])) $matchKey = ['slug' => $item['slug']];
            elseif (isset($item['title'])) $matchKey = ['title' => $item['title']];
            
            BlogCategory::updateOrCreate($matchKey, $item);
        }
    }
}
