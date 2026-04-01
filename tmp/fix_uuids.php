<?php

$models = [
    \App\Models\User::class,
    \App\Models\Order::class,
    \App\Models\Store::class,
    \App\Models\Product::class,
    \App\Models\Category::class,
    \App\Models\Brand::class,
];

foreach ($models as $m) {
    if (!class_exists($m))
        continue;

    $records = $m::whereNull('uuid')->get();
    $count = $records->count();

    foreach ($records as $r) {
        $r->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
    }

    echo "Updated $count records in $m\n";
}

echo "UUID Fix Complete.\n";


// php artisan tinker tmp/fix_uuids.php
// php artisan tinker production_repair.php