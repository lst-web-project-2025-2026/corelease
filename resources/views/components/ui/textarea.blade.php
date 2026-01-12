@props(['label' => null, 'name' => null, 'placeholder' => '', 'required' => false, 'error' => null])

<div class="form-group {{ $attributes->get('group-class') }}">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-error">*</span>@endif</label>
    @endif
    
    <textarea 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="form-textarea @if($error) is-invalid @endif {{ $attributes->get('class') }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->except(['label', 'name', 'placeholder', 'required', 'error', 'group-class', 'class']) }}
    >{{ $slot }}</textarea>

    @if($error)
        <span class="form-error">{{ $error }}</span>
    @endif
</div>
