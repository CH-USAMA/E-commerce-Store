<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /**
     * `blog_post_{slug}` is keyed per row, so it cannot be a static list.
     *
     * When the slug changes, the entry under the PREVIOUS slug also has to go —
     * `getOriginal()` still holds it inside the `saved` event, and without this the
     * old URL would keep serving the pre-edit copy for the rest of the hour.
     */
    protected function contentCacheKeys(): array
    {
        return array_unique(array_filter([
            'blog_post_' . $this->slug,
            'blog_post_' . $this->getOriginal('slug'),
        ]));
    }

    protected $fillable = [
        'blog_category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'feature_image',
        'is_published',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
