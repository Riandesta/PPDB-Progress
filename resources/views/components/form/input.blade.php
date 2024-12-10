// resources/views/components/form/input.blade.php
@props(['label', 'name', 'type' => 'text', 'value' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}"
           class="form-control @error($name) is-invalid @enderror"
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           {{ $attributes }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
