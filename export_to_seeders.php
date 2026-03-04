<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [
    'categories' => 'CategorySeeder',
    'brands' => 'BrandSeeder',
    'products' => 'ProductSeeder',
    'stores' => 'StoreSeeder',
    'services' => 'ServiceSeeder',
    'blog_categories' => 'BlogCategorySeeder',
    'blog_posts' => 'BlogPostSeeder',
    'gallery_items' => 'GalleryItemSeeder',
    'banners' => 'BannerSeeder',
    'team_members' => 'TeamMemberSeeder',
];

foreach ($tables as $tableName => $seederName) {
    if (!Schema::hasTable($tableName))
        continue;

    $data = DB::table($tableName)->get();
    if ($data->isEmpty())
        continue;

    $records = [];
    foreach ($data as $row) {
        $cleanRow = [];
        foreach ((array) $row as $key => $value) {
            if (in_array($key, ['id', 'created_at', 'updated_at']))
                continue;
            $cleanRow[$key] = $value;
        }
        $records[] = $cleanRow;
    }

    $export = var_export($records, true);
    // Convert array format to []
    $export = str_replace(['array (', ')'], ['[', ']'], $export);
    $export = str_replace('=> \n', '=> ', $export);

    $modelName = \Illuminate\Support\Str::singular(\Illuminate\Support\Str::studly($tableName));
    if ($tableName === 'blog_categories')
        $modelName = 'BlogCategory';
    if ($tableName === 'blog_posts')
        $modelName = 'BlogPost';
    if ($tableName === 'gallery_items')
        $modelName = 'GalleryItem';
    if ($tableName === 'team_members')
        $modelName = 'TeamMember';

    $template = "<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\\$modelName;

class $seederName extends Seeder
{
    public function run(): void
    {
        \$data = $export;

        foreach (\$data as \$item) {
            \$matchKey = ['name' => \$item['name'] ?? ''];
            if (isset(\$item['slug'])) \$matchKey = ['slug' => \$item['slug']];
            elseif (isset(\$item['title'])) \$matchKey = ['title' => \$item['title']];
            
            $modelName::updateOrCreate(\$matchKey, \$item);
        }
    }
}
";

    file_put_contents("database/seeders/$seederName.php", $template);

    file_put_contents("database/seeders/$seederName.php", $template);
    echo "Generated: $seederName.php\n";
}

echo "All seeders generated successfully!\n";
