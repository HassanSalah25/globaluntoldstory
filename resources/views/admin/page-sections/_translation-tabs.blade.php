@php
    $translations = $translations ?? collect();
    $defaultTab = $adminLocales[0]['code'] ?? 'en';
@endphp

<div x-data="{ tab: '{{ $defaultTab }}' }">
    @include('admin.components.locale-tab-nav')

    @foreach($adminLocales as $locale)
        <div x-show="tab === '{{ $locale['code'] }}'"
             @if($locale['rtl']) dir="rtl" @endif
             class="space-y-4"
             x-cloak>
            @if($locale['code'] === 'en' && isset($english))
                {{ $english }}
            @elseif($locale['code'] === 'ar' && isset($arabic))
                {{ $arabic }}
            @else
                {{ ${'locale_' . $locale['code']} ?? '' }}
            @endif
        </div>
    @endforeach
</div>
