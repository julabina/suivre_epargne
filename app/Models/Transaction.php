<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'amount',
        'comment',
        'type',
    ];

    /**
     * @return BelongsTo<Project, Transaction>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
