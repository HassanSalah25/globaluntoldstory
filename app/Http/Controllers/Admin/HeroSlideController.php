<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Support\AdminLocales;
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
        return view('admin.hero-slides.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'image_url'  => 'required|string|max:255',
            'gradient'   => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'               => 'string|max:255',
            'badge'               => 'nullable|string|max:255',
            'title_highlight'     => 'nullable|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'description'         => 'nullable|string',
            'cta_primary_label'   => 'nullable|string|max:255',
            'cta_primary_url'     => 'nullable|string|max:255',
            'cta_secondary_label' => 'nullable|string|max:255',
            'cta_secondary_url'   => 'nullable|string|max:255',
        ], requiredFields: ['title'])));

        $heroSlide = HeroSlide::create([
            'image_url'  => $request->image_url,
            'gradient'   => $request->gradient,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($heroSlide, $request, [
            'badge',
            'title',
            'title_highlight',
            'subtitle',
            'description',
            'cta_primary_label',
            'cta_primary_url',
            'cta_secondary_label',
            'cta_secondary_url',
        ]);

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        $translations = $heroSlide->translations->keyBy('locale');

        return view('admin.hero-slides.edit', compact('heroSlide', 'translations'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $request->validate(array_merge([
            'image_url'  => 'required|string|max:255',
            'gradient'   => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'               => 'string|max:255',
            'badge'               => 'nullable|string|max:255',
            'title_highlight'     => 'nullable|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'description'         => 'nullable|string',
            'cta_primary_label'   => 'nullable|string|max:255',
            'cta_primary_url'     => 'nullable|string|max:255',
            'cta_secondary_label' => 'nullable|string|max:255',
            'cta_secondary_url'   => 'nullable|string|max:255',
        ], requiredFields: ['title'])));

        $heroSlide->update([
            'image_url'  => $request->image_url,
            'gradient'   => $request->gradient,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($heroSlide, $request, [
            'badge',
            'title',
            'title_highlight',
            'subtitle',
            'description',
            'cta_primary_label',
            'cta_primary_url',
            'cta_secondary_label',
            'cta_secondary_url',
        ]);

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
