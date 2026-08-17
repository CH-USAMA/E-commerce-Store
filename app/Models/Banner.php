<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** Homepage hero slider — see HomeController::index(). */
    protected static array $contentCacheKeys = ['banners'];

    protected $fillable = ['title', 'subtitle', 'description', 'image', 'image_mobile', 'link'];
}
