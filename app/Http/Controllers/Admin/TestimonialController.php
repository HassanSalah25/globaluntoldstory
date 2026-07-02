<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\AdminLocales;
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
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'avatar_url' => 'nullable|string|max:255',
            'rating'     => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer',
            'type'       => 'required|in:client,award',
        ], AdminLocales::fieldRules([
            'name' => 'string|max:255',
            'role' => 'nullable|string|max:255',
            'text' => 'nullable|string',
        ], requiredFields: ['name', 'text'])));

        $testimonial = Testimonial::create([
            'avatar_url' => $request->avatar_url,
            'rating'     => $request->rating,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
            'type'       => $request->type,
        ]);

        AdminLocales::syncTranslations($testimonial, $request, ['name', 'role', 'text'], ['name', 'text']);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        $translations = $testimonial->translations->keyBy('locale');

        return view('admin.testimonials.edit', compact('testimonial', 'translations'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate(array_merge([
            'avatar_url' => 'nullable|string|max:255',
            'rating'     => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer',
            'type'       => 'required|in:client,award',
        ], AdminLocales::fieldRules([
            'name' => 'string|max:255',
            'role' => 'nullable|string|max:255',
            'text' => 'nullable|string',
        ], requiredFields: ['name', 'text'])));

        $testimonial->update([
            'avatar_url' => $request->avatar_url,
            'rating'     => $request->rating,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
            'type'       => $request->type,
        ]);

        AdminLocales::syncTranslations($testimonial, $request, ['name', 'role', 'text'], ['name', 'text']);

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
