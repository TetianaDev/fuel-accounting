<?php

namespace App\Livewire;

use Livewire\Component;

abstract class CrudTable extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;
    public array $form = [];

    abstract protected function model(): object;
    abstract public function columns(): array;
    abstract public function fields(): array;

    /**
     * Returns only the fields that should be visible given the current form state.
     * Fields with 'showWhen' are hidden unless all their conditions match $this->form.
     */
    public function visibleFields(): array
    {
        return collect($this->fields())
            ->filter(function ($field) {
                if (!isset($field['showWhen'])) {
                    return true;
                }
                foreach ($field['showWhen'] as $key => $value) {
                    if (($this->form[$key] ?? null) !== $value) {
                        return false;
                    }
                }
                return true;
            })
            ->values()
            ->toArray();
    }

    protected function rules(): array
    {
        return collect($this->visibleFields())
            ->mapWithKeys(fn($field) => [
                "form.{$field['key']}" => ($field['required'] ?? true) ? 'required' : 'nullable',
            ])
            ->toArray();
    }

    public function openCreate(): void
    {
        $defaults = collect($this->fields())
            ->filter(fn($f) => $f['type'] === 'select' || isset($f['default']))
            ->mapWithKeys(function ($f) {
                if (isset($f['default'])) {
                    return [$f['key'] => $f['default']];
                }
                return [$f['key'] => $f['options'][0]['value'] ?? null];
            })
            ->filter(fn($v) => $v !== null)
            ->toArray();

        $this->form = $defaults;
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $record = $this->model()::findOrFail($id);
        $this->editingId = $id;
        $this->form = $record->only(array_column($this->fields(), 'key'));
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate($this->rules());

        $data = collect($this->visibleFields())
            ->mapWithKeys(fn($f) => [$f['key'] => $this->form[$f['key']] ?? null])
            ->toArray();

        if ($this->editingId) {
            $this->model()::findOrFail($this->editingId)->update($data);
        } else {
            $this->model()::create($data);
        }

        $this->reset('form', 'editingId', 'showModal');
    }

    public function delete(int $id): void
    {
        $this->model()::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.crud-table', [
            'records' => $this->model()::all(),
            'columns' => $this->columns(),
            'fields'  => $this->fields(),
        ]);
    }
}
