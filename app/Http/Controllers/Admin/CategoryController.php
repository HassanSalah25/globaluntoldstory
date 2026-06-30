<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:blog,portfolio,service',
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'nullable|string|max:255',
            'label_en'   => 'nullable|string|max:255',
            'label_ar'   => 'nullable|string|max:255',
        ]);

        $category = Category::create([
            'slug'       => Str::slug($request->name_en),
            'type'       => $request->type,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name'  => $request->input("name_{$locale}"),
                    'label' => $request->input("label_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Category created successfully.');

        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        $category->load('translations');

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'type'       => 'required|in:blog,portfolio,service',
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'nullable|string|max:255',
            'label_en'   => 'nullable|string|max:255',
            'label_ar'   => 'nullable|string|max:255',
        ]);

        $category->update([
            'slug'       => Str::slug($request->name_en),
            'type'       => $request->type,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name'  => $request->input("name_{$locale}"),
                    'label' => $request->input("label_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Category updated successfully.');

        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        session()->flash('success', 'Category deleted successfully.');

        return redirect()->route('admin.categories.index');
    }
}
