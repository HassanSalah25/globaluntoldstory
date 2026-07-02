@props([
    'name',
    'label',
    'locale',
    'value' => '',
    'type' => 'text',
    'required' => null,
    'rows' => 3,
    'placeholder' => null,
    'help' => null,
    'inputClass' => 'border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm',
])

@php
    $fieldName = $name . '_' . $locale['code'];
    $isRequired = $required ?? $locale['required'];
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($isRequired)
            <span class="text-red-500">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea name="{{ $fieldName }}"
                  rows="{{ $rows }}"
                  @if($placeholder) placeholder="{{ $placeholder }}" @endif
                  @if($isRequired) required @endif
                  class="{{ $inputClass }}">{{ old($fieldName, $value) }}</textarea>
    @else
        <input type="{{ $type }}"
               name="{{ $fieldName }}"
               value="{{ old($fieldName, $value) }}"
               @if($placeholder) placeholder="{{ $placeholder }}" @endif
               @if($isRequired) required @endif
               class="{{ $inputClass }}">
    @endif

    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
</div>
