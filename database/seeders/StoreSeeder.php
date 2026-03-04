<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
  public function run(): void
  {
    $data = [
      0 =>
        [
          'name' => 'Jabulani Build & Tiles Mount Frere',
          'slug' => 'jabulani-build-tiles-mount-frere',
          'image' => 'images/buildTile.webp',
          'address' => '54 N2, KwaBacha, 5090, Eastern Cape, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-30.90670000',
          'lng' => '28.99210000',
          'contact_details' => 'jabulanigroup79@gmail.com | 0731577357',
        ],
      1 =>
        [
          'name' => 'Jabulani Hardware Mount Frere',
          'slug' => 'jabulani-hardware-mount-frere',
          'image' => 'images/JBshop(small).webp',
          'address' => '219 Main Street, KwaBhaca, 5090, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-30.90660000',
          'lng' => '28.99220000',
          'contact_details' => 'jabulanihardware01@gmail.com | 0793039102',
        ],
      2 =>
        [
          'name' => 'Jabulani Steel Pipe & Hardware Mt Frere',
          'slug' => 'jabulani-steel-pipe-hardware-mt-frere',
          'image' => 'images/JBshop(steel).webp',
          'address' => 'Erf 346 Ludidi street, KwaBhaca, 5090, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-30.90720000',
          'lng' => '28.99360000',
          'contact_details' => 'jabulanisteelpipe2023@gmail.com | 0717791288',
        ],
      3 =>
        [
          'name' => 'Moin Hardware Mount Frere',
          'slug' => 'moin-hardware-mount-frere',
          'image' => 'images/JBshop(moin).webp',
          'address' => 'Shop 218 Main Street 66, N2, KwaBhaca, 5090, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-30.90670000',
          'lng' => '28.99210000',
          'contact_details' => 'moinhardware2@gmail.com | 0744858421',
        ],
      4 =>
        [
          'name' => 'Jabulani Hardware Shinta',
          'slug' => 'jabulani-hardware-shinta',
          'image' => 'images/JBshop(shinta).webp',
          'address' => 'N2 Main Road NGQINIBENI KWA SHINTA, KwaBhaca, 5090, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-30.96140170',
          'lng' => '28.95917280',
          'contact_details' => 'shintajabulani@gmail.com | 0631570514',
        ],
      5 =>
        [
          'name' => 'Jabulani Board & Tiles Umtata',
          'slug' => 'jabulani-board-tiles-umtata',
          'image' => 'images/JBshop(Umtata).webp',
          'address' => '21 Sutherland St, Umtata Centeral, Mthatha, 5100, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-31.58850000',
          'lng' => '28.79440000',
          'contact_details' => 'jabulaniboardandtiles@gmail.com | 0832817127',
        ],
      6 =>
        [
          'name' => 'Jabulani Build & Tiles Qumbu',
          'slug' => 'jabulani-build-tiles-qumbu',
          'image' => 'images/JBshop(Qumbu).webp',
          'address' => 'Shop 258, N2 Main St, Qumbu, 5180, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-31.15970000',
          'lng' => '28.86910000',
          'contact_details' => 'jabulaniqumbu258@gmail.com | 0783116448',
        ],
      7 =>
        [
          'name' => 'Jabulani Hardware Tsolo',
          'slug' => 'jabulani-hardware-tsolo',
          'image' => 'images/JBshop(Tsolo).webp',
          'address' => 'ERF 105 Main Street, Tsolo, 5170, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-31.30910000',
          'lng' => '28.75450000',
          'contact_details' => 'jabulanigroup2002@gmail.com | 0717999497',
        ],
      8 =>
        [
          'name' => 'Jabulani Group BlockYard Tsolo',
          'slug' => 'jabulani-group-blockyard-tsolo',
          'image' => 'images/JBshop(blockyard).webp',

          'address' => 'ERF 105 Main Street, Tsolo, 5170, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-31.31910000',
          'lng' => '28.76260000',
          'contact_details' => 'jabulanigroup2002@gmail.com | 0613303916',
        ],
      9 =>
        [
          'name' => 'Jabulani Quarries Tsolo',
          'slug' => 'jabulani-quarries-tsolo',
          'image' => 'images/quarrypic3.webp',
          'address' => ' Portion of Farm 541 Tiki Tiki Main Street, Tsolo, 5170, South Africa',
          'province' => 'Eastern Cape',
          'lat' => '-31.33799160',
          'lng' => '28.76128650',
          'contact_details' => 'jabulanigroup2002@gmail.com | 0613303916',
        ],
    ];

    foreach ($data as $item) {
      $matchKey = ['name' => $item['name'] ?? ''];
      if (isset($item['slug']))
        $matchKey = ['slug' => $item['slug']];
      elseif (isset($item['title']))
        $matchKey = ['title' => $item['title']];

      Store::updateOrCreate($matchKey, $item);
    }
  }
}
