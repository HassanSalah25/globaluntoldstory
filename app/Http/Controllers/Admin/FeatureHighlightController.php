<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureHighlight;
use App\Support\AdminLocales;
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
        $request->validate(array_merge([
            'context'    => 'required|in:services,about,why-us',
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $featureHighlight = FeatureHighlight::create([
            'context'    => $request->context,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($featureHighlight, $request, ['title', 'description']);

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
        $request->validate(array_merge([
            'context'    => 'required|in:services,about,why-us',
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $featureHighlight->update([
            'context'    => $request->context,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($featureHighlight, $request, ['title', 'description']);

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
