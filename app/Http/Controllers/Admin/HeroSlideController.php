<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    public function index(Request $request)
    {
        $query = HeroSlide::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('title', 'like', "%{$search}%");
            });
        }

        $heroSlides = $query->paginate(15)->withQueryString();

        return view('admin.hero-slides.index', compact('heroSlides'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];

        return view('admin.hero-slides.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_url'           => 'required|string|max:255',
            'gradient'            => 'nullable|string|max:255',
            'sort_order'          => 'required|integer',
            'title_en'            => 'required|string|max:255',
            'title_ar'            => 'required|string|max:255',
            'badge_en'            => 'nullable|string|max:255',
            'badge_ar'            => 'nullable|string|max:255',
            'title_highlight_en'  => 'nullable|string|max:255',
            'title_highlight_ar'  => 'nullable|string|max:255',
            'subtitle_en'         => 'nullable|string|max:255',
            'subtitle_ar'         => 'nullable|string|max:255',
            'description_en'      => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'cta_primary_label_en'    => 'nullable|string|max:255',
            'cta_primary_label_ar'    => 'nullable|string|max:255',
            'cta_primary_url_en'      => 'nullable|string|max:255',
            'cta_primary_url_ar'      => 'nullable|string|max:255',
            'cta_secondary_label_en'  => 'nullable|string|max:255',
            'cta_secondary_label_ar'  => 'nullable|string|max:255',
            'cta_secondary_url_en'    => 'nullable|string|max:255',
            'cta_secondary_url_ar'    => 'nullable|string|max:255',
        ]);

        $heroSlide = HeroSlide::create([
            'image_url'  => $request->image_url,
            'gradient'   => $request->gradient,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $heroSlide->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'badge'                => $request->input("badge_{$locale}"),
                    'title'                => $request->input("title_{$locale}"),
                    'title_highlight'      => $request->input("title_highlight_{$locale}"),
                    'subtitle'             => $request->input("subtitle_{$locale}"),
                    'description'          => $request->input("description_{$locale}"),
                    'cta_primary_label'    => $request->input("cta_primary_label_{$locale}"),
                    'cta_primary_url'      => $request->input("cta_primary_url_{$locale}"),
                    'cta_secondary_label'  => $request->input("cta_secondary_label_{$locale}"),
                    'cta_secondary_url'    => $request->input("cta_secondary_url_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        $locales = ['en', 'ar'];
        $translations = $heroSlide->translations->keyBy('locale');

        return view('admin.hero-slides.edit', compact('heroSlide', 'locales', 'translations'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $request->validate([
            'image_url'           => 'required|string|max:255',
            'gradient'            => 'nullable|string|max:255',
            'sort_order'          => 'required|integer',
            'title_en'            => 'required|string|max:255',
            'title_ar'            => 'required|string|max:255',
            'badge_en'            => 'nullable|string|max:255',
            'badge_ar'            => 'nullable|string|max:255',
            'title_highlight_en'  => 'nullable|string|max:255',
            'title_highlight_ar'  => 'nullable|string|max:255',
            'subtitle_en'         => 'nullable|string|max:255',
            'subtitle_ar'         => 'nullable|string|max:255',
            'description_en'      => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'cta_primary_label_en'    => 'nullable|string|max:255',
            'cta_primary_label_ar'    => 'nullable|string|max:255',
            'cta_primary_url_en'      => 'nullable|string|max:255',
            'cta_primary_url_ar'      => 'nullable|string|max:255',
            'cta_secondary_label_en'  => 'nullable|string|max:255',
            'cta_secondary_label_ar'  => 'nullable|string|max:255',
            'cta_secondary_url_en'    => 'nullable|string|max:255',
            'cta_secondary_url_ar'    => 'nullable|string|max:255',
        ]);

        $heroSlide->update([
            'image_url'  => $request->image_url,
            'gradient'   => $request->gradient,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $heroSlide->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'badge'                => $request->input("badge_{$locale}"),
                    'title'                => $request->input("title_{$locale}"),
                    'title_highlight'      => $request->input("title_highlight_{$locale}"),
                    'subtitle'             => $request->input("subtitle_{$locale}"),
                    'description'          => $request->input("description_{$locale}"),
                    'cta_primary_label'    => $request->input("cta_primary_label_{$locale}"),
                    'cta_primary_url'      => $request->input("cta_primary_url_{$locale}"),
                    'cta_secondary_label'  => $request->input("cta_secondary_label_{$locale}"),
                    'cta_secondary_url'    => $request->input("cta_secondary_url_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide deleted successfully.');
    }

    public function toggle(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => ! $heroSlide->is_active]);

        return redirect()->back()->with('success', 'Hero slide status updated.');
    }
}
