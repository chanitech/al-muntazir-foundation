@props(['name', 'label', 'value' => null, 'rows' => 4, 'help' => null, 'required' => false])

@php $id = 'f_'.$name; @endphp
<div class="form-group">
    <label for="{{ $id }}">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" @required($required)
              {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
