<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 *
 * @param  int  $id
 * @param  int  $project_id
 * @param  float  $amount
 * @param  string|null  $comment
 * @param  int  $type
 * @param  Carbon  $created_at
 * @param  Carbon  $updated_at
 * @param  Carbon  $deleted_at
 */
class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'Transaction';

    protected $fillable = [
        'project_id',
        'amount',
        'comment',
        'type',
    ];

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
