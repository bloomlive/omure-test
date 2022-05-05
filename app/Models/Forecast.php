<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forecast extends Model
{
    use HasFactory;

    /**
     * Please note, that all models have been unguarded on purpose.
     */

    protected $casts = [
        'start_time'  => 'immutable_datetime',
        'description' => 'array',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
