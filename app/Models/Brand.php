<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** Homepage brand strip — see HomeController::index(). */
    protected static array $contentCacheKeys = ['brands'];

    protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->uuid)) {
                $brand->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = ['name', 'slug', 'logo'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
