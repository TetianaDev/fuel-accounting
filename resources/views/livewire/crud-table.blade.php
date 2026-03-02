<div>
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
                            <td class="px-4 py-3">{{ $record->{$column['key']} }}</td>
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
                            Немає даних
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="absolute w-full h-full top-0 left-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="w-[65%] min-w-87.5" wire:click.self="$set('showModal', false)">
                <div class="bg-zinc-800 border border-zinc-700 rounded-lg p-6 w-full space-y-6 shadow-xl">
                    <div class="flex justify-between">
                        <flux:heading size="lg mb-8">{{ $editingId ? 'Редагувати' : 'Додати' }}</flux:heading>
                        <flux:icon.x-mark  wire:click="$set('showModal', false)"></flux:button>
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
