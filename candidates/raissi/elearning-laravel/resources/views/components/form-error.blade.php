@props(['field'])

@error($field)
    <p {{ $attributes->class(['field-error']) }}>{{ $message }}</p>
@enderror
