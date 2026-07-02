<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\AdminLocales;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('title', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate(15)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:255',
            'image_url'  => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'      => 'string|max:255',
            'short_desc' => 'nullable|string',
            'full_desc'  => 'nullable|string',
            'price'      => 'nullable|string|max:255',
        ], requiredFields: ['title'])));

        $service = Service::create([
            'slug'        => Str::slug($request->title_en),
            'icon'        => $request->icon,
            'image_url'   => $request->image_url,
            'sort_order'  => $request->sort_order,
            'is_active'   => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        AdminLocales::syncTranslations($service, $request, [
            'title', 'short_desc', 'full_desc', 'price',
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $translations = $service->translations->keyBy('locale');

        return view('admin.services.edit', compact('service', 'translations'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate(array_merge([
            'slug'       => 'required|string|max:255|unique:services,slug,' . $service->id,
            'icon'       => 'nullable|string|max:255',
            'image_url'  => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'      => 'string|max:255',
            'short_desc' => 'nullable|string',
            'full_desc'  => 'nullable|string',
            'price'      => 'nullable|string|max:255',
        ], requiredFields: ['title'])));

        $service->update([
            'slug'        => $request->slug,
            'icon'        => $request->icon,
            'image_url'   => $request->image_url,
            'sort_order'  => $request->sort_order,
            'is_active'   => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        AdminLocales::syncTranslations($service, $request, [
            'title', 'short_desc', 'full_desc', 'price',
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    public function toggle(Service $service)
    {
        $service->update(['is_active' => ! $service->is_active]);

        return redirect()->back()->with('success', 'Service status updated.');
    }
}
