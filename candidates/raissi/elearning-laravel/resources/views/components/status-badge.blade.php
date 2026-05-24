@props(['status' => null])

@php
    $label = $status instanceof \BackedEnum ? $status->value : (string) ($status ?? trim((string) $slot));
@endphp

@if ($label !== '')
    <span {{ $attributes->class(['status-badge']) }}>{{ $label }}</span>
@endif
