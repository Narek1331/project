@php
    $min = $getMinValue() ?? 0;
    $max = $getMaxValue() ?? 999999;
@endphp

<style>
    .no-spinner::-webkit-inner-spin-button,
.no-spinner::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
.no-spinner[type=number] {
    -moz-appearance: textfield;
}

</style>

<div
    x-data="{ value: @entangle($getStatePath()), min: {{ $min }}, max: {{ $max }} }"
    class="flex items-center gap-2 w-full"
    style="margin-top: 32px!important"
>
    <x-filament::button
        type="button"
        color="primary"
        size="sm"
        icon="heroicon-m-minus"
        class="rounded-full w-8 h-8 p-0 flex items-center justify-center"
        x-on:click="value = Math.max(min, +value - 1)"
    />

    <x-filament::input
        type="number"
        min="{{ $min }}"
        max="{{ $max }}"
        x-model="value"
        class="no-spinner text-center w-20"
    />

    <x-filament::button
        type="button"
        color="primary"
        size="sm"
        icon="heroicon-m-plus"
        class="rounded-full w-8 h-8 p-0 flex items-center justify-center"
        x-on:click="value = Math.min(max, +value + 1)"
    />
</div>
