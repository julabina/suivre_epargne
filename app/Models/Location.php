<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Location
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $location
 * @property Carbon $create_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'Locations';

    protected $fillable = [
        'user_id',
        'location',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
