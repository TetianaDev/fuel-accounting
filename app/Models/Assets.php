<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
