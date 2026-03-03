<?php

namespace App\Livewire;

use App\Models\Assets;
use App\Models\FuelVehicle;

class FuelVehicleTable extends CrudTable
{
    public $selectedAssetId  = null;
    public $selectedMonth    = null;
    public $selectedYear     = null;
    public bool $showRecords = false;

    public float $prevFuelRemaining = 0;
    public ?string $warning         = null;

    protected function model(): object
    {
        return new FuelVehicle;
    }

    public function columns(): array
    {
        return [
            ['key' => 'date',                     'label' => 'Дата'],
            ['key' => 'route',                    'label' => 'Маршрут'],
            ['key' => 'mileage',                  'label' => 'Пробіг, км'],
            ['key' => 'fuel_consumption_by_norm', 'label' => 'Витрата по нормі, л'],
            ['key' => 'fuel_filling',             'label' => 'Заправка, л'],
            ['key' => 'fuel_remaining',           'label' => 'Залишок, л'],
        ];
    }

    public function fields(): array
    {
        return [
            ['key' => 'date',                     'label' => 'Дата',                'type' => 'date',   'width' => 50, 'live' => true],
            ['key' => 'route',                    'label' => 'Маршрут',             'type' => 'text',   'width' => 50],
            ['key' => 'mileage',                  'label' => 'Пробіг, км',          'type' => 'number', 'width' => 50, 'live' => true],
            ['key' => 'fuel_consumption_by_norm', 'label' => 'Витрата по нормі, л', 'type' => 'number', 'width' => 50],
            ['key' => 'fuel_filling',             'label' => 'Заправка, л',         'type' => 'number', 'width' => 50, 'required' => false, 'live' => true],
            ['key' => 'fuel_remaining',           'label' => 'Залишок, л',          'type' => 'number', 'width' => 50],
            [
                'key'     => 'fuel_source',
                'label'   => 'Джерело пального',
                'type'    => 'select',
                'width'   => 50,
                'options' => [
                    ['value' => 'Автотранс', 'label' => 'Автотранс'],
                    ['value' => 'Склад',     'label' => 'Склад'],
                ],
            ],
        ];
    }

    public function loadRecords(): void
    {
        $this->showRecords = true;
    }

    public function openCreate(): void
    {
        parent::openCreate();
        $this->form['asset_id'] = $this->selectedAssetId;
        $this->warning = null;

        $last = FuelVehicle::where('asset_id', $this->selectedAssetId)
            ->orderByDesc('date')->orderByDesc('id')->first();

        $this->prevFuelRemaining = $last ? (float) $last->fuel_remaining : 0;
        $this->recalculateFuelRemaining();
    }

    public function openEdit(int $id): void
    {
        parent::openEdit($id);
        $this->warning = null;

        $record = FuelVehicle::find($id);

        $prev = FuelVehicle::where('asset_id', $this->selectedAssetId)
            ->where(function ($q) use ($record) {
                $q->where('date', '<', $record->date)
                  ->orWhere(fn($q) => $q->where('date', $record->date)->where('id', '<', $record->id));
            })
            ->orderByDesc('date')->orderByDesc('id')->first();

        $this->prevFuelRemaining = $prev ? (float) $prev->fuel_remaining : 0;
    }

    public function updatedForm($value, $key): void
    {
        match ($key) {
            'mileage'      => $this->onMileageUpdated($value),
            'date'         => $this->onDateUpdated($value),
            'fuel_filling' => $this->recalculateFuelRemaining(),
            default        => null,
        };
    }

    private function onMileageUpdated($value): void
    {
        $asset = Assets::find($this->selectedAssetId);

        $this->form['fuel_consumption_by_norm'] = ($asset && $value)
            ? round((float) $value * $asset->asset_fuel_consumption_rate / 100, 2)
            : null;

        $this->recalculateFuelRemaining();
    }

    private function onDateUpdated($value): void
    {
        if (!$value) return;

        $prev = FuelVehicle::where('asset_id', $this->selectedAssetId)
            ->where('date', '<', $value)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->orderByDesc('date')->orderByDesc('id')->first();

        $this->prevFuelRemaining = $prev ? (float) $prev->fuel_remaining : 0;
        $this->recalculateFuelRemaining();
    }

    private function recalculateFuelRemaining(): void
    {
        $consumption = (float) ($this->form['fuel_consumption_by_norm'] ?? 0);
        $filling     = (float) ($this->form['fuel_filling'] ?? 0);

        $this->form['fuel_remaining'] = round($this->prevFuelRemaining - $consumption + $filling, 2);
    }

    public function save(): void
    {
        $this->validate($this->rules());

        $data = collect($this->visibleFields())
            ->mapWithKeys(fn($f) => [$f['key'] => $this->form[$f['key']] ?? null])
            ->toArray();

        $data['asset_id'] = $this->selectedAssetId;

        $savedRecord = $this->editingId
            ? tap($this->model()::findOrFail($this->editingId))->update($data)
            : $this->model()::create($data);

        $this->cascadeRecalculate($savedRecord);

        $asset = Assets::find($this->selectedAssetId);
        $this->warning = ($asset?->vehicle_tank_volume && $savedRecord->fuel_remaining > $asset->vehicle_tank_volume)
            ? "Залишок пального ({$savedRecord->fuel_remaining} л) перевищує об'єм баку ({$asset->vehicle_tank_volume} л)."
            : null;

        $this->reset('form', 'editingId', 'showModal');
    }

    private function cascadeRecalculate(FuelVehicle $fromRecord): void
    {
        $subsequent = FuelVehicle::where('asset_id', $fromRecord->asset_id)
            ->where(function ($q) use ($fromRecord) {
                $q->where('date', '>', $fromRecord->date)
                  ->orWhere(fn($q) => $q->where('date', $fromRecord->date)->where('id', '>', $fromRecord->id));
            })
            ->orderBy('date')->orderBy('id')
            ->get();

        $prevRemaining = (float) $fromRecord->fuel_remaining;

        foreach ($subsequent as $record) {
            $newRemaining = round($prevRemaining - $record->fuel_consumption_by_norm + $record->fuel_filling, 2);
            $record->update(['fuel_remaining' => $newRemaining]);
            $prevRemaining = $newRemaining;
        }
    }

    public function render()
    {
        $vehicles = Assets::where('asset_type', 'Транспортні засоби')
            ->orderBy('asset_name')
            ->get();

        $months = [
            1  => 'Січень',   2  => 'Лютий',    3  => 'Березень',
            4  => 'Квітень',  5  => 'Травень',   6  => 'Червень',
            7  => 'Липень',   8  => 'Серпень',   9  => 'Вересень',
            10 => 'Жовтень',  11 => 'Листопад',  12 => 'Грудень',
        ];

        $currentYear = (int) now()->year;
        $years = range($currentYear, $currentYear - 10);

        $records = collect();
        if ($this->showRecords && $this->selectedAssetId) {
            $records = FuelVehicle::where('asset_id', $this->selectedAssetId)
                ->when($this->selectedMonth, fn($q) => $q->whereMonth('date', $this->selectedMonth))
                ->when($this->selectedYear,  fn($q) => $q->whereYear('date',  $this->selectedYear))
                ->orderBy('date')
                ->get();
        }

        return view('livewire.fuel-vehicle-table', [
            'vehicles' => $vehicles,
            'months'   => $months,
            'years'    => $years,
            'records'  => $records,
            'columns'  => $this->columns(),
            'fields'   => $this->fields(),
        ]);
    }
}
