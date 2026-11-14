@props(['name', 'label', 'type' => 'text', 'value' => null, 'help' => null, 'required' => false])

@php $id = 'f_'.str_replace(['[', ']', '.'], '_', $name); @endphp
<div class="form-group">
    <label for="{{ $id }}">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
           value="{{ old($name, $value) }}" @required($required)
           {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
