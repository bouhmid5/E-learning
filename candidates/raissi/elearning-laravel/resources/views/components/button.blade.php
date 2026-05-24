@props(['variant' => 'primary', 'type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->class(['btn', 'btn-secondary' => $variant === 'secondary']) }}>
    {{ $slot }}
</button>
