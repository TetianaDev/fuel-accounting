<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assets extends Model {
    protected $table = 'assets';

    protected $fillable = [
        'asset_type',
        'unit',
        'asset_name',
        'asset_details',
        'vehicle_registration_number',
        'asset_status',
        'vehicle_type',
        'asset_fuel_type',
        'asset_fuel_consumption_rate',
        'vehicle_tank_volume',
        'asset_owner',
        'contract',
        'contract_amount',
        'intake_date',
        'decommissioning_date',
    ];

    public function fuelVehicle(): HasMany {
        return $this->hasMany(FuelVehicle::class, 'asset_id');
    }

    public function fuelEquipment(): HasMany {
        return $this->hasMany(FuelEquipment::class, 'asset_id');
    }

}
