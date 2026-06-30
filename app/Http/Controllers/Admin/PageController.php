<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('translations')->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug'       => 'required|string|max:255|unique:pages,slug',
            'is_active'  => 'boolean',
            'title_en'   => 'required|string|max:255',
            'title_ar'   => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_en'   => 'nullable|string|max:100',
            'badge_ar'   => 'nullable|string|max:100',
        ]);

        $page = Page::create([
            'slug'      => $request->slug,
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'    => $request->input("title_{$locale}"),
                    'subtitle' => $request->input("subtitle_{$locale}"),
                    'badge'    => $request->input("badge_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Page created successfully.');

        return redirect()->route('admin.pages.index');
    }

    public function edit(Page $page)
    {
        $page->load(['translations', 'sections' => function ($query) {
            $query->orderBy('sort_order')->with('translations');
        }]);

        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'slug'       => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'is_active'  => 'boolean',
            'title_en'   => 'required|string|max:255',
            'title_ar'   => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_en'   => 'nullable|string|max:100',
            'badge_ar'   => 'nullable|string|max:100',
        ]);

        $page->update([
            'slug'      => $request->slug,
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'    => $request->input("title_{$locale}"),
                    'subtitle' => $request->input("subtitle_{$locale}"),
                    'badge'    => $request->input("badge_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Page updated successfully.');

        return redirect()->route('admin.pages.index');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        session()->flash('success', 'Page deleted successfully.');

        return redirect()->route('admin.pages.index');
    }

    public function toggle(Page $page)
    {
        $page->update(['is_active' => ! $page->is_active]);

        session()->flash('success', 'Page status toggled.');

        return redirect()->route('admin.pages.index');
    }
}
