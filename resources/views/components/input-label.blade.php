@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm text-[#DA5019]']) }}>
    {{ $value ?? $slot }}
</label>
