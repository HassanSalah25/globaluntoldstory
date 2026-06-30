<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
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
        $request->validate([
            'icon'           => 'nullable|string|max:100',
            'color'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0',
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'organization_en' => 'nullable|string|max:255',
            'organization_ar' => 'nullable|string|max:255',
            'year_label_en'  => 'nullable|string|max:100',
            'year_label_ar'  => 'nullable|string|max:100',
        ]);

        $award = Award::create([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $award->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'        => $request->input("title_{$locale}"),
                    'organization' => $request->input("organization_{$locale}"),
                    'year_label'   => $request->input("year_label_{$locale}"),
                ]
            );
        }

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
        $request->validate([
            'icon'           => 'nullable|string|max:100',
            'color'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0',
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'organization_en' => 'nullable|string|max:255',
            'organization_ar' => 'nullable|string|max:255',
            'year_label_en'  => 'nullable|string|max:100',
            'year_label_ar'  => 'nullable|string|max:100',
        ]);

        $award->update([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $award->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'        => $request->input("title_{$locale}"),
                    'organization' => $request->input("organization_{$locale}"),
                    'year_label'   => $request->input("year_label_{$locale}"),
                ]
            );
        }

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
