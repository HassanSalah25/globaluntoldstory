<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('name', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->paginate(15)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];

        return view('admin.testimonials.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'avatar_url' => 'nullable|string|max:255',
            'rating'     => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer',
            'type'       => 'required|in:client,award',
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'required|string|max:255',
            'role_en'    => 'nullable|string|max:255',
            'role_ar'    => 'nullable|string|max:255',
            'text_en'    => 'nullable|string',
            'text_ar'    => 'nullable|string',
        ]);

        $testimonial = Testimonial::create([
            'avatar_url' => $request->avatar_url,
            'rating'     => $request->rating,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
            'type'       => $request->type,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $testimonial->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $request->input("name_{$locale}"),
                    'role' => $request->input("role_{$locale}"),
                    'text' => $request->input("text_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        $locales = ['en', 'ar'];
        $translations = $testimonial->translations->keyBy('locale');

        return view('admin.testimonials.edit', compact('testimonial', 'locales', 'translations'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'avatar_url' => 'nullable|string|max:255',
            'rating'     => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer',
            'type'       => 'required|in:client,award',
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'required|string|max:255',
            'role_en'    => 'nullable|string|max:255',
            'role_ar'    => 'nullable|string|max:255',
            'text_en'    => 'nullable|string',
            'text_ar'    => 'nullable|string',
        ]);

        $testimonial->update([
            'avatar_url' => $request->avatar_url,
            'rating'     => $request->rating,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
            'type'       => $request->type,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $testimonial->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $request->input("name_{$locale}"),
                    'role' => $request->input("role_{$locale}"),
                    'text' => $request->input("text_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function toggle(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);

        return redirect()->back()->with('success', 'Testimonial status updated.');
    }
}
