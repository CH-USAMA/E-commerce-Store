<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Calling all generated seeders
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            StoreSeeder::class,
            ServiceSeeder::class,
            BlogCategorySeeder::class,
            BlogPostSeeder::class,
            GalleryItemSeeder::class,
            BannerSeeder::class,
            TeamMemberSeeder::class,
        ]);
    }
}
