@props([
    'tabVar' => 'tab',
    'defaultTab' => null,
    'refreshEditors' => false,
    'heading' => null,
])

@php
    $defaultTab = $defaultTab ?? ($adminLocales[0]['code'] ?? 'en');
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6']) }}
     x-data="{ {{ $tabVar }}: '{{ $defaultTab }}' }">
    @if($heading)
        <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $heading }}</h3>
    @endif

    @include('admin.components.locale-tab-nav', [
        'tabVar' => $tabVar,
        'refreshEditors' => $refreshEditors,
    ])

    {{ $slot }}
</div>
