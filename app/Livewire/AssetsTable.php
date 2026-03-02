<?php

namespace App\Livewire;

use App\Models\Assets;

class AssetsTable extends CrudTable {
    protected function model(): object
    {
        return new Assets();
    }

    public function columns(): array
    {
        return [
            ['key' => 'asset_type', 'label' => 'Тип'],
            ['key' => 'unit', 'label' => 'Підрозділ'],
            ['key' => 'asset_name', 'label' => 'Марка, модель'],
            ['key' => 'vehicle_registration_number', 'label' => 'Реєстраційний номер'],
            ['key' => 'asset_status', 'label' => 'Статус'],
            ['key' => 'vehicle_type', 'label' => 'Тип авто'],
            ['key' => 'asset_fuel_type', 'label' => 'Тип пального'],
        ];
    }

    public function fields(): array
    {
        return [
            [
                'key' => 'asset_type',
                'label' => 'Тип',
                'type' => 'select',
                'placeholder' => 'Виберіть тип ...',
                'options' => [
                    ['value' => 'Транспортні засоби', 'label' => 'Транспортні засоби'],
                    ['value' => 'Спецтехніка та обладнання', 'label' => 'Спецтехніка та обладнання']
                ],
                'width' => 50
            ],
            [
                'key' => 'unit',
                'label' => 'Підрозділ',
                'type' => 'select',
                'placeholder' => 'Виберіть підрозділ ...',
                'options' => [
                    ['value' => 'Полтава', 'label' => 'Полтава'],
                    ['value' => 'Супрунівка', 'label' => 'Супрунівка'],
                    ['value' => 'Решетилівка', 'label' => 'Решетилівка'],
                    ['value' => 'Всі', 'label' => 'Всі'],
                ],
                'width' => 50
            ],
            ['key' => 'asset_name', 'label' => 'Марка, модель', 'type' => 'text', 'width' => 30],
            ['key' => 'asset_details', 'label' => 'Деталі', 'type' => 'text', 'width' => 70],
            ['key' => 'vehicle_registration_number', 'label' => 'Реєстраційний номер', 'type' => 'text', 'required' => false, 'width' => 25],
            [
                'key' => 'asset_status',
                'label' => 'Статус',
                'type' => 'select',
                'placeholder' => 'Виберіть статус ...',
                'options' => [
                    ['value' => 'Власний', 'label' => 'Власний'],
                    ['value' => 'Орендований', 'label' => 'Орендований'],
                    ['value' => 'Позичка', 'label' => 'Позичка'],
                ],
                'width' => 25
            ],
            [
                'key' => 'vehicle_type',
                'label' => 'Тип авто',
                'type' => 'select',
                'placeholder' => 'Виберіть тип ...',
                'options' => [
                    ['value' => 'Легковий', 'label' => 'Легковий'],
                    ['value' => 'Вантажний', 'label' => 'Вантажний'],
                ],
                'showWhen' => ['asset_type' => 'Транспортні засоби'],
                'width' => 25
            ],
            [
                'key' => 'asset_fuel_type',
                'label' => 'Тип пального',
                'type' => 'select',
                'placeholder' => 'Виберіть тип ...',
                'options' => [
                    ['value' => 'D', 'label' => 'D'],
                    ['value' => 'B', 'label' => 'B'],
                    ['value' => 'E', 'label' => 'E'],
                ],
                'width' => 25
            ],
            ['key' => 'asset_fuel_consumption_rate', 'label' => 'Норма витрат палива', 'type' => 'number', 'width' => 25],
            ['key' => 'vehicle_tank_volume', 'label' => "Об'єм баку", 'type' => 'number', 'width' => 25],
            ['key' => 'asset_owner', 'label' => 'Власник за документами', 'type' => 'text', 'width' => 50],
            ['key' => 'contract', 'label' => 'Договір', 'type' => 'text', 'required' => false, 'width' => 80],
            ['key' => 'contract_amount', 'label' => 'Сума договору', 'type' => 'number', 'required' => false, 'width' => 20],
            ['key' => 'intake_date', 'label' => 'Надходження', 'type' => 'date', 'required' => false, 'width' => 50],
            ['key' => 'decommissioning_date', 'label' => 'Вибуття', 'type' => 'date', 'required' => false, 'width' => 50],
        ];
    }
}
