<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property  Collection $users
 */
class Game extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_user');
    }
}
