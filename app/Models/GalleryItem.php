<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** /gallery listing — see HomeController::gallery(). */
    protected static array $contentCacheKeys = ['gallery_all'];

    protected $fillable = ['title', 'image', 'category'];
}
