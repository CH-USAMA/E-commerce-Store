<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Define the accurate hierarchy based on the original HTML templates
        $hierarchy = [
            'Jabulani products' => [
                'Bricks and Blocks',
                'Aluminium Windows and Doors',
                'Structural and Concrete',
                'Roofing',
                'Jabulani Tile Adhesive',
                'Crush Stone'
            ],
            'Building Materials' => [
                'Brick and Blocks',
                'Concrete and Structural',
                'Cement and Premixes',
                'Structural Steel',
                'Reinforcing and Underlay'
            ],
            'Roof & Ceiling' => [
                'Roof Tiles',
                'Roof Sheeting',
                'Fascia and Barge',
                'Ceiling Boards and Pine',
                'Cornices'
            ],
            'Tiles & Flooring' => [
                'Tiles',
                'Tile Adhesive and Grout',
                'Flooring',
                'Carpet and Accessories'
            ],
            'Boards & Timber' => [
                'Boards',
                'Decking and Cladding',
                'Mouldings and PAR Timber',
                'Poles and Exotic Timber',
                'Edging'
            ],
            'Hardware & Tools' => [
                'Safety and Protective Wear',
                'Window and Door Accessories',
                'Chain, Clamps and Aluminium Profile',
                'Power and Building Tools',
                'Gardening Tools'
            ],
            'Paint' => [
                'Waterproofing',
                'Enamel and PVA',
                'Roof Paint',
                'Wood Coating',
                'Paint Accessories'
            ],
            'Bathroom & Kitchen' => [
                'Bathroomware',
                'Kitchen'
            ],
            'Plumbing & Electrical' => [
                'Pipes,Waste Traps and Fittings',
                'Geysers and RainWater Goods',
                'Plumbing Accessories',
                'Wiring and Cables',
                'Plugs, Sockets and Bulbs',
                'Electrical Accessories'
            ]
        ];

        // Ensure we don't duplicate existing categories on re-seed
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $mainImages = [
            'Jabulani products' => 'images/JABULANI BLOCKS.webp',
            'Building Materials' => 'images/BRICKS_BLOCKS.webp',
            'Roof & Ceiling' => 'images/ROOF_FLOOR2.webp',
            'Tiles & Flooring' => 'images/Tiles2.webp',
            'Boards & Timber' => 'images/Plywood.webp',
            'Hardware & Tools' => 'images/Power_tools.webp',
            'Paint' => 'images/PVA.webp',
            'Bathroom & Kitchen' => 'images/Shower.webp',
            'Plumbing & Electrical' => 'images/Pipes.webp',
        ];

        foreach ($hierarchy as $mainCategoryName => $subCategories) {
            // Create the main parent category
            $mainCategory = Category::create([
                'name' => $mainCategoryName,
                'slug' => Str::slug($mainCategoryName),
                'image' => $mainImages[$mainCategoryName] ?? null,
                'parent_id' => null,
            ]);

            // Create its subcategories
            foreach ($subCategories as $subCategoryName) {
                Category::create([
                    'name' => $subCategoryName,
                    'slug' => Str::slug($subCategoryName) . '-' . $mainCategory->id, // Ensure unique slug for duplicate names like "Bricks and Blocks"
                    'image' => null,
                    'parent_id' => $mainCategory->id,
                ]);
            }
        }
    }
}
