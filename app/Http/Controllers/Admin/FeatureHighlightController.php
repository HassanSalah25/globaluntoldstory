<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureHighlight;
use Illuminate\Http\Request;

class FeatureHighlightController extends Controller
{
    public function index()
    {
        $featureHighlights = FeatureHighlight::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.feature-highlights.index', compact('featureHighlights'));
    }

    public function create()
    {
        return view('admin.feature-highlights.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'context'        => 'required|in:services,about,why-us',
            'icon'           => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $featureHighlight = FeatureHighlight::create([
            'context'    => $request->context,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $featureHighlight->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Feature highlight created successfully.');

        return redirect()->route('admin.feature-highlights.index');
    }

    public function edit(FeatureHighlight $featureHighlight)
    {
        $featureHighlight->load('translations');

        return view('admin.feature-highlights.edit', compact('featureHighlight'));
    }

    public function update(Request $request, FeatureHighlight $featureHighlight)
    {
        $request->validate([
            'context'        => 'required|in:services,about,why-us',
            'icon'           => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $featureHighlight->update([
            'context'    => $request->context,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $featureHighlight->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Feature highlight updated successfully.');

        return redirect()->route('admin.feature-highlights.index');
    }

    public function destroy(FeatureHighlight $featureHighlight)
    {
        $featureHighlight->delete();

        session()->flash('success', 'Feature highlight deleted successfully.');

        return redirect()->route('admin.feature-highlights.index');
    }
}
