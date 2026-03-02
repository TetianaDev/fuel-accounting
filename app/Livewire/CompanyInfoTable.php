<?php

namespace App\Livewire;

use App\Models\CompanyInfo;

class CompanyInfoTable extends CrudTable
{
    protected function model(): object
    {
        return new CompanyInfo;
    }

    public function columns(): array
    {
        return [
            ['key' => 'name',                  'label' => 'Назва'],
            ['key' => 'edrpou_code',            'label' => 'ЄДРПОУ'],
            ['key' => 'address',                'label' => 'Адреса'],
            ['key' => 'create_report_position', 'label' => 'Посада (складач)'],
            ['key' => 'create_report_name',     'label' => 'ПІБ (складач)'],
            ['key' => 'check_report_position',  'label' => 'Посада (перевірка)'],
            ['key' => 'check_report_name',      'label' => 'ПІБ (перевірка)'],
        ];
    }

    public function fields(): array
    {
        return [
            ['key' => 'name',                  'label' => 'Назва',              'type' => 'text'],
            ['key' => 'edrpou_code',            'label' => 'ЄДРПОУ',            'type' => 'number'],
            ['key' => 'address',                'label' => 'Адреса',             'type' => 'text'],
            ['key' => 'create_report_position', 'label' => 'Посада (складач)',   'type' => 'text'],
            ['key' => 'create_report_name',     'label' => 'ПІБ (складач)',      'type' => 'text'],
            ['key' => 'check_report_position',  'label' => 'Посада (перевірка)', 'type' => 'text'],
            ['key' => 'check_report_name',      'label' => 'ПІБ (перевірка)',    'type' => 'text'],
        ];
    }
}
