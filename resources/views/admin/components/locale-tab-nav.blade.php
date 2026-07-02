@props([
    'tabVar' => 'tab',
    'refreshEditors' => false,
])

@php
    $onClickExtra = $refreshEditors
        ? "; \$nextTick(() => { window.initRichTextEditors?.(); window.resizeRichTextEditors?.(); })"
        : '';
@endphp

<div class="flex flex-wrap border-b border-gray-200 mb-6 gap-1">
    @foreach($adminLocales as $locale)
        <button type="button"
                @click="{{ $tabVar }} = '{{ $locale['code'] }}'{{ $onClickExtra }}"
                :class="{{ $tabVar }} === '{{ $locale['code'] }}' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-2 font-medium text-sm -mb-px whitespace-nowrap"
                title="{{ $locale['label'] }}">
            {{ $locale['native'] }}
            @if($locale['required'])
                <span class="text-red-500">*</span>
            @endif
        </button>
    @endforeach
</div>
