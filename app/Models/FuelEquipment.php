<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelEquipment extends Model {
    protected $table = 'fuel_equipment';

    protected $fillable = [
        'asset_id',
        'date',
        'time_worked',
        'fuel_used',
        'fuel_remaining_start',
        'fuel_filling',
        'fuel_remaining_end',
        'fuel_source',
    ];

    public function asset(): BelongsTo {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
