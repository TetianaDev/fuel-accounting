<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $table = 'company_info';

    protected $fillable = [
        'name',
        'edrpou_code',
        'address',
        'create_report_position',
        'create_report_name',
        'check_report_position',
        'check_report_name',
    ];
}
