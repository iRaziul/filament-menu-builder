@props(['item'])

@php
    /** @var \Datlechin\FilamentMenuBuilder\Models\MenuItem $item */

    $hasChildren = $item->children->isNotEmpty();
@endphp

<li
    wire:key="{{ $item->getKey() }}"
    data-sortable-item="{{ $item->getKey() }}"
    x-data="{ open: $persist(true).as('menu-item-' + {{ $item->getKey() }}) }"
>
    <div
        class="flex justify-between rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 px-3 py-2"
    >
        <div class="flex items-center gap-2">
            {{ $this->reorderAction }}

            @if($hasChildren)
                <x-filament::icon-button
                    icon="heroicon-o-chevron-right"
                    x-on:click="open = !open"
                    title="Mở rộng"
                    color="gray"
                    class="transition ease-in-out duration-200"
                    x-bind:class="{ 'rotate-90': open }"
                    size="sm"
                />
            @endif

            <div class="text-sm font-medium leading-6 text-gray-950 dark:text-white whitespace-nowrap">
                {{ $item->title }}
            </div>

            <div class="hidden sm:block text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap text-ellipsis overflow-hidden">
                {{ $item->url }}
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-filament::badge :color="$item->type === 'internal' ? 'primary' : 'gray'" class="hidden sm:block">
                {{ $item->type }}
            </x-filament::badge>
            {{ ($this->editAction)(['id' => $item->getKey(), 'title' => $item->title]) }}
            {{ ($this->deleteAction)(['id' => $item->getKey(), 'title' => $item->title]) }}
        </div>
    </div>

    <ul
        x-collapse
        x-show="open"
        wire:key="{{ $item->getKey() }}.children"
        x-data="menuBuilder({ parentId: {{ $item->getKey()  }} })"
        class="ms-4 space-y-2 mt-2"
    >
        @foreach($item->children as $child)
            <x-filament-menu-builder::menu-item :item="$child" />
        @endforeach
    </ul>
</li>
