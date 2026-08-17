<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** /about shows the first 4, /team shows all — see HomeController. */
    protected static array $contentCacheKeys = ['team_about', 'team_all'];

    protected $fillable = ['name', 'designation', 'location', 'image'];
}
