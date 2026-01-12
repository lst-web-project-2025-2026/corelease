@props(['label' => null, 'name' => null, 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false, 'error' => null])

<div class="form-group {{ $attributes->get('group-class') }}">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-error">*</span>@endif</label>
    @endif
    
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="form-input @if($error) is-invalid @endif {{ $attributes->get('class') }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->except(['label', 'name', 'type', 'placeholder', 'value', 'required', 'error', 'group-class', 'class']) }}
    >

    @if($error)
        <span class="form-error">{{ $error }}</span>
    @endif
</div>
