@php
    $translations = $translations ?? collect();
@endphp

<div x-data="{ tab: 'en' }">
    <div class="flex border-b border-gray-200 mb-6">
        <button type="button"
                @click="tab = 'en'"
                :class="tab === 'en' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 font-medium text-sm -mb-px">English</button>
        <button type="button"
                @click="tab = 'ar'"
                :class="tab === 'ar' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 font-medium text-sm -mb-px">عربي</button>
    </div>

    <div x-show="tab === 'en'" class="space-y-4">
        {{ $english }}
    </div>

    <div x-show="tab === 'ar'" dir="rtl" class="space-y-4">
        {{ $arabic }}
    </div>
</div>
