@props([
    'locale',
    'tabVar' => 'tab',
])

<div x-show="{{ $tabVar }} === '{{ $locale['code'] }}'"
     @if($locale['rtl']) dir="rtl" @endif
     class="space-y-4"
     x-cloak>
    {{ $slot }}
</div>
