@props(['name', 'label', 'checked' => false, 'help' => null])

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" class="custom-control-input" id="f_{{ $name }}" name="{{ $name }}" value="1"
               @checked(old($name, $checked))>
        <label class="custom-control-label" for="f_{{ $name }}">{{ $label }}</label>
    </div>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>
