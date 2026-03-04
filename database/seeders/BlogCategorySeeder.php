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
            BlogCategory::updateOrCreate(['name' => $item['name'] ?? ($item['title'] ?? '')], $item);
        }
    }
}
