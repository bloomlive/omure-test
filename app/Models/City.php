<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 */
class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function forecasts(): HasMany
    {
        return $this->hasMany(Forecast::class);
    }
}
