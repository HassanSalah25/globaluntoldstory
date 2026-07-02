<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Support\AdminLocales;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $awards = Award::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.awards.index', compact('awards'));
    }

    public function create()
    {
        return view('admin.awards.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'title'        => 'string|max:255',
            'organization' => 'nullable|string|max:255',
            'year_label'   => 'nullable|string|max:100',
        ], requiredFields: ['title'])));

        $award = Award::create([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($award, $request, ['title', 'organization', 'year_label']);

        session()->flash('success', 'Award created successfully.');

        return redirect()->route('admin.awards.index');
    }

    public function edit(Award $award)
    {
        $award->load('translations');

        return view('admin.awards.edit', compact('award'));
    }

    public function update(Request $request, Award $award)
    {
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'title'        => 'string|max:255',
            'organization' => 'nullable|string|max:255',
            'year_label'   => 'nullable|string|max:100',
        ], requiredFields: ['title'])));

        $award->update([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($award, $request, ['title', 'organization', 'year_label']);

        session()->flash('success', 'Award updated successfully.');

        return redirect()->route('admin.awards.index');
    }

    public function destroy(Award $award)
    {
        $award->delete();

        session()->flash('success', 'Award deleted successfully.');

        return redirect()->route('admin.awards.index');
    }
}
