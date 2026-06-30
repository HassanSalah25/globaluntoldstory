<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceItemController extends Controller
{
    public function index()
    {
        $resources = Resource::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon'           => 'nullable|string|max:100',
            'color'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0',
            'file_url'       => 'nullable|string|max:500',
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'type_label_en'  => 'nullable|string|max:100',
            'type_label_ar'  => 'nullable|string|max:100',
        ]);

        $resource = Resource::create([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'file_url'   => $request->file_url,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $resource->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'      => $request->input("title_{$locale}"),
                    'type_label' => $request->input("type_label_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Resource created successfully.');

        return redirect()->route('admin.resources.index');
    }

    public function edit(Resource $resource)
    {
        $resource->load('translations');

        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'icon'           => 'nullable|string|max:100',
            'color'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0',
            'file_url'       => 'nullable|string|max:500',
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'type_label_en'  => 'nullable|string|max:100',
            'type_label_ar'  => 'nullable|string|max:100',
        ]);

        $resource->update([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'file_url'   => $request->file_url,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $resource->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'      => $request->input("title_{$locale}"),
                    'type_label' => $request->input("type_label_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Resource updated successfully.');

        return redirect()->route('admin.resources.index');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();

        session()->flash('success', 'Resource deleted successfully.');

        return redirect()->route('admin.resources.index');
    }
}
