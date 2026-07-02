<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\AdminLocales;
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
        $request->validate(array_merge([
            'slug'      => 'required|string|max:255|unique:pages,slug',
            'is_active' => 'boolean',
        ], AdminLocales::fieldRules([
            'title'    => 'string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge'    => 'nullable|string|max:100',
        ], requiredFields: ['title'])));

        $page = Page::create([
            'slug'      => $request->slug,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AdminLocales::syncTranslations($page, $request, ['title', 'subtitle', 'badge']);

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
        $request->validate(array_merge([
            'slug'      => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'is_active' => 'boolean',
        ], AdminLocales::fieldRules([
            'title'    => 'string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge'    => 'nullable|string|max:100',
        ], requiredFields: ['title'])));

        $page->update([
            'slug'      => $request->slug,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AdminLocales::syncTranslations($page, $request, ['title', 'subtitle', 'badge']);

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
