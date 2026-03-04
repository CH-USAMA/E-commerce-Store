<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'Muhammad Zeeshan',
                'designation' => 'senior operations manager',
                'location' => 'Jabulani Group of Companies',
                'image' => 'zeeshan.webp',
            ],
            [
                'name' => 'Mkukwana A. Zenande',
                'designation' => 'Accounts & Finance manager',
                'location' => 'Jabulani Group of Companies',
                'image' => 'zenande.jpeg',
            ],
            [
                'name' => 'Muhammad Kaleem',
                'designation' => 'Retail Operations Supervisor',
                'location' => 'Jabulani Group of Companies',
                'image' => 'kaleem.webp',
            ],
            [
                'name' => 'Nasir Rehman',
                'designation' => 'General Manager',
                'location' => 'Jabulani Hardware Tsolo',
                'image' => 'nasir.webp',
            ],
            [
                'name' => 'Faizan Muhammad',
                'designation' => 'IT Manager',
                'location' => 'Jabulani Group of Companies',
                'image' => 'Faizan.webp',
            ],
            [
                'name' => 'Ayabulela Madlala',
                'designation' => 'HR Admin',
                'location' => 'Jabulani Group of Companies',
                'image' => 'aya.webp',
            ],
            [
                'name' => 'Muhammad Kashif',
                'designation' => 'Store Manager',
                'location' => 'Jabulani Build & Tiles Mt Frere',
                'image' => 'KASHIF.webp',
            ],
            [
                'name' => 'Muhammad Tayyab',
                'designation' => 'Store Manager',
                'location' => 'Jabulani Build & Tiles Qumbu',
                'image' => 'TAYYAB.webp',
            ],
            [
                'name' => 'Nicholas Mhlathi',
                'designation' => 'Mine Manager',
                'location' => 'Jabulani Quarries',
                'image' => 'nichols.webp',
            ],
            [
                'name' => 'Asif Rehman',
                'designation' => 'Store & Operations Manager',
                'location' => 'Moin Hardware Mt Frere',
                'image' => 'ASIF.webp',
            ],
            [
                'name' => 'Nouman Arif',
                'designation' => 'Store Manager',
                'location' => 'Jabulani hardware Mt Frere',
                'image' => 'noman.webp',
            ],
            [
                'name' => 'Sabboor Mehmood',
                'designation' => 'Sales Manager',
                'location' => 'Jabulani Build & Tile Mt frere',
                'image' => 'saboor.webp',
            ],
            [
                'name' => 'Usman Sadiq',
                'designation' => 'Store Manager',
                'location' => 'Jabulani hardware Shinta',
                'image' => 'usman.webp',
            ],
        ];

        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }
    }
}
