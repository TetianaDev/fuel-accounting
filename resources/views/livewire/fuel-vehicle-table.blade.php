<div>
    {{-- Filter --}}
    <div class="mb-6 flex gap-4 items-end">
        <div class="flex-1">
            <flux:select wire:model.live="selectedAssetId" label="Транспортний засіб">
                <flux:select.option value="">Оберіть ТЗ...</flux:select.option>
                @foreach ($vehicles as $vehicle)
                    <flux:select.option value="{{ $vehicle->id }}">
                        {{ $vehicle->asset_name }}{{ $vehicle->vehicle_registration_number ? ' (' . $vehicle->vehicle_registration_number . ')' : '' }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex-1">
            <flux:select wire:model.live="selectedYear" label="Рік">
                <flux:select.option value="">Всі роки</flux:select.option>
                @foreach ($years as $year)
                    <flux:select.option value="{{ $year }}">{{ $year }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex-1">
            <flux:select wire:model.live="selectedMonth" label="Місяць">
                <flux:select.option value="">Всі місяці</flux:select.option>
                @foreach ($months as $num => $name)
                    <flux:select.option value="{{ $num }}">{{ $name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <flux:button wire:click="loadRecords" icon="magnifying-glass">Показати записи</flux:button>
    </div>

    {{-- Tank warning --}}
    @if ($warning)
        <div class="mb-4 flex items-center justify-between gap-3 px-4 py-3 rounded-lg border border-yellow-600 bg-yellow-900/30 text-yellow-400 text-sm">
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-triangle class="size-4 shrink-0" />
                {{ $warning }}
            </div>
            <flux:button size="sm" variant="ghost" wire:click="$set('warning', null)">✕</flux:button>
        </div>
    @endif

    {{-- Table --}}
    @if ($showRecords)
        @if (!$selectedAssetId)
            <p class="text-zinc-500 text-sm">Оберіть транспортний засіб для перегляду записів.</p>
        @else
        <div class="mb-4 flex justify-end">
            <flux:button wire:click="openCreate" icon="plus">Додати</flux:button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-700">
            <table class="w-full text-sm text-left text-zinc-300">
                <thead class="text-xs uppercase bg-zinc-700 text-zinc-400">
                    <tr>
                        @foreach ($columns as $column)
                            <th class="px-4 py-3 font-medium">{{ $column['label'] }}</th>
                        @endforeach
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @forelse ($records as $record)
                        <tr class="bg-zinc-800 hover:bg-zinc-750">
                            @foreach ($columns as $column)
                                @php
                                    $cellValue = $record->{$column['key']};
                                    if (is_string($cellValue) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $cellValue)) {
                                        $cellValue = \Carbon\Carbon::createFromFormat('Y-m-d', $cellValue)->format('d.m.Y');
                                    }
                                @endphp
                                <td class="px-4 py-3">{{ $cellValue }}</td>
                            @endforeach
                            <td class="px-4 py-3">
                                <div class="flex gap-2 justify-end">
                                    <flux:button size="sm" icon="pencil" wire:click="openEdit({{ $record->id }})" />
                                    <flux:button size="sm" icon="trash" variant="danger"
                                        wire:click="delete({{ $record->id }})"
                                        wire:confirm="Ви впевнені, що хочете видалити цей запис?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-zinc-800">
                            <td colspan="{{ count($columns) + 1 }}" class="px-4 py-6 text-center text-zinc-500">
                                Немає записів за обраним фільтром
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    @endif

    {{-- Modal --}}
    @if ($showModal)
        <div class="absolute w-full h-full top-0 left-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="w-[65%] min-w-87.5" wire:click.self="$set('showModal', false)">
                <div class="bg-zinc-800 border border-zinc-700 rounded-lg p-6 w-full space-y-6 shadow-xl">
                    <div class="flex justify-between">
                        <flux:heading size="lg mb-8">{{ $editingId ? 'Редагувати запис' : 'Додати запис' }}</flux:heading>
                        <flux:icon.x-mark wire:click="$set('showModal', false)" />
                    </div>

                    <div class="flex flex-wrap gap-4 items-start">
                        @foreach ($fields as $field)
                            @php
                                $visible = true;
                                if (isset($field['showWhen'])) {
                                    foreach ($field['showWhen'] as $condKey => $condValue) {
                                        if (($form[$condKey] ?? null) !== $condValue) {
                                            $visible = false;
                                            break;
                                        }
                                    }
                                }
                                $width = $field['width'] ?? 100;
                            @endphp

                            @if ($visible)
                                <div style="width: calc({{ $width }}% - 1rem)">
                                    @if ($field['type'] === 'select')
                                        <flux:select
                                            wire:model.live="form.{{ $field['key'] }}"
                                            label="{{ $field['label'] }}"
                                        >
                                            @if (isset($field['placeholder']))
                                                <flux:select.option value="" disabled>{{ $field['placeholder'] }}</flux:select.option>
                                            @endif
                                            @foreach ($field['options'] as $option)
                                                <flux:select.option value="{{ $option['value'] }}">{{ $option['label'] }}</flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    @elseif ($field['live'] ?? false)
                                        <flux:input
                                            wire:model.live="form.{{ $field['key'] }}"
                                            label="{{ $field['label'] }}"
                                            type="{{ $field['type'] }}"
                                        />
                                    @elseif ($field['readonly'] ?? false)
                                        <flux:input
                                            wire:model="form.{{ $field['key'] }}"
                                            label="{{ $field['label'] }}"
                                            type="{{ $field['type'] }}"
                                            readonly
                                        />
                                    @else
                                        <flux:input
                                            wire:model="form.{{ $field['key'] }}"
                                            label="{{ $field['label'] }}"
                                            type="{{ $field['type'] }}"
                                        />
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-2">
                        <flux:button variant="ghost" wire:click="$set('showModal', false)">Скасувати</flux:button>
                        <flux:button wire:click="save">Зберегти</flux:button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
