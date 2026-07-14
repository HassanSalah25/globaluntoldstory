@php
    use App\Support\ExpertiseCategories;

    $categories = ExpertiseCategories::all();
    $selectedEn = old('tag_en', $selectedEn ?? '');
    $selectedAr = old('tag_ar', $selectedAr ?? '');
@endphp

<div x-data="{
    tagEn: @js($selectedEn),
    tagAr: @js($selectedAr),
    categories: @js($categories),
    onCategoryChange() {
        const match = this.categories.find((item) => item.en === this.tagEn);
        if (match) this.tagAr = match.ar;
    }
}">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Specialty <span class="text-red-500">*</span>
    </label>
    <p class="text-xs text-gray-500 mb-2">These match the tags on the About page: Commercials, Documentaries, Branded Films, Live Events, Podcasts, Motion/CGI.</p>

    <select name="tag_en"
            x-model="tagEn"
            @change="onCategoryChange()"
            required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-red-500">
        <option value="">Select a specialty...</option>
        @foreach($categories as $category)
            <option value="{{ $category['en'] }}" @selected($selectedEn === $category['en'])>
                {{ $category['en'] }} — {{ $category['ar'] }}
            </option>
        @endforeach
    </select>

    <input type="hidden" name="tag_ar" x-model="tagAr">
</div>
