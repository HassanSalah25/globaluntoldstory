@php
    $imageValue = $imageValue ?? '';
    $gradientValue = $gradientValue ?? '';
    $gradientPresets = [
        'Dark overlay' => 'linear-gradient(160deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.55) 50%, rgba(0,0,0,0.35) 100%)',
        'Red spotlight' => 'linear-gradient(135deg, rgba(127,29,29,0.86) 0%, rgba(15,23,42,0.72) 55%, rgba(0,0,0,0.48) 100%)',
        'Soft black' => 'linear-gradient(120deg, rgba(3,7,18,0.76) 0%, rgba(17,24,39,0.52) 55%, rgba(0,0,0,0.28) 100%)',
    ];
@endphp

<div class="md:col-span-2 space-y-5"
     x-data="{
         imageUrl: @js($imageValue),
         gradient: @js($gradientValue),
         imagePreviewOk: true,
         setGradient(value) {
             this.gradient = value;
         }
     }">
    @include('admin.components.image-picker', [
        'name' => 'image_url',
        'label' => 'Hero Image',
        'value' => $imageValue,
        'required' => true,
        'parentModel' => 'imageUrl',
        'showPreview' => false,
        'help' => 'Browse the media library, upload a new image, or keep an external URL.',
    ])

    <div x-show="imageUrl" x-cloak class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
        <div class="relative aspect-[16/7] bg-gray-100">
            <img :src="imageUrl"
                 alt=""
                 class="h-full w-full object-cover"
                 x-on:load="imagePreviewOk = true"
                 x-on:error="imagePreviewOk = false">
            <div x-show="gradient" class="absolute inset-0" :style="`background: ${gradient}`"></div>
            <div x-show="!imagePreviewOk" class="absolute inset-0 flex items-center justify-center bg-gray-100 px-4 text-center text-sm text-gray-500">
                Image preview could not load. Check that the URL is public and points directly to an image.
            </div>
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between gap-3 mb-1">
            <label for="gradient" class="block text-sm font-medium text-gray-700">Gradient Overlay</label>
            <span class="text-xs text-gray-400">Optional</span>
        </div>

        <div class="grid grid-cols-1 gap-2 sm:grid-cols-3 mb-3">
            @foreach($gradientPresets as $label => $value)
                <button type="button"
                        @click="setGradient(@js($value))"
                        :class="gradient === @js($value) ? 'ring-2 ring-red-500 border-red-300' : 'border-gray-200 hover:border-gray-300'"
                        class="rounded-xl border bg-white p-2 text-left transition">
                    <span class="block h-12 rounded-lg border border-white/60 shadow-inner" style="background: {{ $value }}"></span>
                    <span class="mt-2 block text-xs font-medium text-gray-700">{{ $label }}</span>
                </button>
            @endforeach
        </div>

        <textarea id="gradient"
                  name="gradient"
                  rows="2"
                  maxlength="255"
                  x-model="gradient"
                  placeholder="linear-gradient(160deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.55) 50%, rgba(0,0,0,0.35) 100%)"
                  class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono leading-relaxed">{{ $gradientValue }}</textarea>
        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
            <p class="text-xs text-gray-500">Choose a preset or paste custom CSS. It is applied over the image preview.</p>
            <button type="button" @click="gradient = ''" class="text-xs font-medium text-gray-500 hover:text-red-600">Clear gradient</button>
        </div>
    </div>
</div>
