<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
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
        $locales = ['en', 'ar'];

        return view('admin.services.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon'        => 'nullable|string|max:255',
            'sort_order'  => 'required|integer',
            'title_en'    => 'required|string|max:255',
            'title_ar'    => 'required|string|max:255',
            'short_desc_en' => 'nullable|string',
            'short_desc_ar' => 'nullable|string',
            'full_desc_en'  => 'nullable|string',
            'full_desc_ar'  => 'nullable|string',
            'price_en'    => 'nullable|string|max:255',
            'price_ar'    => 'nullable|string|max:255',
        ]);

        $service = Service::create([
            'slug'        => Str::slug($request->title_en),
            'icon'        => $request->icon,
            'sort_order'  => $request->sort_order,
            'is_active'   => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $service->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'      => $request->input("title_{$locale}"),
                    'short_desc' => $request->input("short_desc_{$locale}"),
                    'full_desc'  => $request->input("full_desc_{$locale}"),
                    'price'      => $request->input("price_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $locales = ['en', 'ar'];
        $translations = $service->translations->keyBy('locale');

        return view('admin.services.edit', compact('service', 'locales', 'translations'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'slug'        => 'required|string|max:255|unique:services,slug,' . $service->id,
            'icon'        => 'nullable|string|max:255',
            'sort_order'  => 'required|integer',
            'title_en'    => 'required|string|max:255',
            'title_ar'    => 'required|string|max:255',
            'short_desc_en' => 'nullable|string',
            'short_desc_ar' => 'nullable|string',
            'full_desc_en'  => 'nullable|string',
            'full_desc_ar'  => 'nullable|string',
            'price_en'    => 'nullable|string|max:255',
            'price_ar'    => 'nullable|string|max:255',
        ]);

        $service->update([
            'slug'        => $request->slug,
            'icon'        => $request->icon,
            'sort_order'  => $request->sort_order,
            'is_active'   => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $service->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'      => $request->input("title_{$locale}"),
                    'short_desc' => $request->input("short_desc_{$locale}"),
                    'full_desc'  => $request->input("full_desc_{$locale}"),
                    'price'      => $request->input("price_{$locale}"),
                ]
            );
        }

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
