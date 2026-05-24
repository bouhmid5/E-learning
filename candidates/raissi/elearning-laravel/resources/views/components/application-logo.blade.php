@props(['compact' => false])

@php
    $logoPath = public_path('assets/brand/formini-logo.png');
    $hasLogo = file_exists($logoPath);
@endphp

<span {{ $attributes->class(['brand-mark']) }}>
    @if ($hasLogo)
        <img src="{{ asset('assets/brand/formini-logo.png') }}" alt="Formini" class="brand-logo">
    @else
        {{-- TODO: place the official Formini logo at public/assets/brand/formini-logo.png --}}
        <span class="brand-fallback" aria-hidden="true">F</span>
    @endif
    @unless ($compact)
        <span class="brand-text">Formini</span>
    @endunless
</span>
