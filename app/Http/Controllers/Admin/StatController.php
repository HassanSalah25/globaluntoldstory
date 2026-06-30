<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use Illuminate\Http\Request;

class StatController extends Controller
{
    public function index(Request $request)
    {
        $query = Stat::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('label', 'like', "%{$search}%");
            });
        }

        $stats = $query->paginate(15)->withQueryString();

        return view('admin.stats.index', compact('stats'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];

        return view('admin.stats.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon'          => 'nullable|string|max:255',
            'numeric_value' => 'nullable|string|max:255',
            'suffix'        => 'nullable|string|max:50',
            'color'         => 'nullable|string|max:50',
            'bg_gradient'   => 'nullable|string|max:255',
            'sort_order'    => 'required|integer',
            'context'       => 'nullable|in:home,about,portfolio',
            'label_en'      => 'required|string|max:255',
            'label_ar'      => 'required|string|max:255',
            'sublabel_en'   => 'nullable|string|max:255',
            'sublabel_ar'   => 'nullable|string|max:255',
        ]);

        $stat = Stat::create([
            'icon'          => $request->icon,
            'numeric_value' => $request->numeric_value,
            'suffix'        => $request->suffix,
            'color'         => $request->color,
            'bg_gradient'   => $request->bg_gradient,
            'sort_order'    => $request->sort_order,
            'context'       => $request->context,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $stat->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'label'    => $request->input("label_{$locale}"),
                    'sublabel' => $request->input("sublabel_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.stats.index')->with('success', 'Stat created successfully.');
    }

    public function edit(Stat $stat)
    {
        $locales = ['en', 'ar'];
        $translations = $stat->translations->keyBy('locale');

        return view('admin.stats.edit', compact('stat', 'locales', 'translations'));
    }

    public function update(Request $request, Stat $stat)
    {
        $request->validate([
            'icon'          => 'nullable|string|max:255',
            'numeric_value' => 'nullable|string|max:255',
            'suffix'        => 'nullable|string|max:50',
            'color'         => 'nullable|string|max:50',
            'bg_gradient'   => 'nullable|string|max:255',
            'sort_order'    => 'required|integer',
            'context'       => 'nullable|in:home,about,portfolio',
            'label_en'      => 'required|string|max:255',
            'label_ar'      => 'required|string|max:255',
            'sublabel_en'   => 'nullable|string|max:255',
            'sublabel_ar'   => 'nullable|string|max:255',
        ]);

        $stat->update([
            'icon'          => $request->icon,
            'numeric_value' => $request->numeric_value,
            'suffix'        => $request->suffix,
            'color'         => $request->color,
            'bg_gradient'   => $request->bg_gradient,
            'sort_order'    => $request->sort_order,
            'context'       => $request->context,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $stat->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'label'    => $request->input("label_{$locale}"),
                    'sublabel' => $request->input("sublabel_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.stats.index')->with('success', 'Stat updated successfully.');
    }

    public function destroy(Stat $stat)
    {
        $stat->delete();

        return redirect()->route('admin.stats.index')->with('success', 'Stat deleted successfully.');
    }
}
