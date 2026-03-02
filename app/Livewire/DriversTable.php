<?php

namespace App\Livewire;

use App\Models\Drivers;

class DriversTable extends CrudTable {
    protected function model(): object
    {
        return new Drivers;
    }

    public function columns(): array
    {
        return [
            ['key' => 'name',                  'label' => 'ПІБ'],
            ['key' => 'driver_license_number', 'label' => 'Номер посвідчення водія'],
        ];
    }

    public function fields(): array
    {
        return [
            ['key' => 'name',                  'label' => 'ПІБ',                     'type' => 'text'],
            ['key' => 'driver_license_number', 'label' => 'Номер посвідчення водія', 'type' => 'text'],
        ];
    }

}
