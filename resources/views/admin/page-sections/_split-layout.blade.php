@php
    $imageValue = $imageValue ?? '';
    $imageLabel = $imageLabel ?? 'Section Image';
    $imageHelp = $imageHelp ?? 'Shown beside the text content. Recommended: 1200×900 or larger.';
    $imageAspect = $imageAspect ?? 'aspect-[4/5]';
@endphp

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Content</h3>
        {{ $content }}
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 xl:sticky xl:top-6 xl:self-start"
         x-data="{ imageUrl: @js($imageValue), imagePreviewOk: true }">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Image</h3>

        @include('admin.components.image-picker', [
            'name' => 'image',
            'label' => $imageLabel,
            'value' => $imageValue,
            'required' => true,
            'parentModel' => 'imageUrl',
            'showPreview' => false,
            'help' => $imageHelp,
        ])

        <div x-show="imageUrl" x-cloak class="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
            <div class="relative {{ $imageAspect }} bg-gray-100">
                <img :src="imageUrl"
                     alt=""
                     class="h-full w-full object-cover"
                     x-on:load="imagePreviewOk = true"
                     x-on:error="imagePreviewOk = false">
                <div x-show="!imagePreviewOk" class="absolute inset-0 flex items-center justify-center bg-gray-100 px-4 text-center text-sm text-gray-500">
                    Image preview could not load.
                </div>
            </div>
        </div>

        @isset($imageExtra)
            <div class="mt-4">
                {{ $imageExtra }}
            </div>
        @endisset
    </div>
</div>
