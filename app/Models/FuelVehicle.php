<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelVehicle extends Model {
	protected $table = 'fuel_vehicle';

    protected $fillable = [
        'asset_id',
        'date',
        'route',
        'mileage',
        'fuel_consumption_by_norm',
        'fuel_filling',
        'fuel_remaining',
        'fuel_source',
    ];

    public function asset(): BelongsTo {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
