<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Support\AdminLocales;
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
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'file_url'   => 'nullable|string|max:500',
        ], AdminLocales::fieldRules([
            'title'      => 'string|max:255',
            'type_label' => 'nullable|string|max:100',
        ], requiredFields: ['title'])));

        $resource = Resource::create([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'file_url'   => $request->file_url,
        ]);

        AdminLocales::syncTranslations($resource, $request, ['title', 'type_label']);

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
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'file_url'   => 'nullable|string|max:500',
        ], AdminLocales::fieldRules([
            'title'      => 'string|max:255',
            'type_label' => 'nullable|string|max:100',
        ], requiredFields: ['title'])));

        $resource->update([
            'icon'       => $request->icon,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'file_url'   => $request->file_url,
        ]);

        AdminLocales::syncTranslations($resource, $request, ['title', 'type_label']);

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
