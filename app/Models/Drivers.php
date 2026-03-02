<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drivers extends Model {
    protected $table = 'drivers';

    protected $fillable = [
        'name',
        'driver_license_number',
    ];
}
