<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use App\Support\AdminLocales;
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
        return view('admin.stats.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'icon'          => 'nullable|string|max:255',
            'numeric_value' => 'nullable|string|max:255',
            'suffix'        => 'nullable|string|max:50',
            'color'         => 'nullable|string|max:50',
            'bg_gradient'   => 'nullable|string|max:255',
            'sort_order'    => 'required|integer',
            'context'       => 'nullable|in:home,about,portfolio',
        ], AdminLocales::fieldRules([
            'label'    => 'string|max:255',
            'sublabel' => 'nullable|string|max:255',
        ], requiredFields: ['label'])));

        $stat = Stat::create([
            'icon'          => $request->icon,
            'numeric_value' => $request->numeric_value,
            'suffix'        => $request->suffix,
            'color'         => $request->color,
            'bg_gradient'   => $request->bg_gradient,
            'sort_order'    => $request->sort_order,
            'context'       => $request->context,
        ]);

        AdminLocales::syncTranslations($stat, $request, ['label', 'sublabel']);

        return redirect()->route('admin.stats.index')->with('success', 'Stat created successfully.');
    }

    public function edit(Stat $stat)
    {
        $translations = $stat->translations->keyBy('locale');

        return view('admin.stats.edit', compact('stat', 'translations'));
    }

    public function update(Request $request, Stat $stat)
    {
        $request->validate(array_merge([
            'icon'          => 'nullable|string|max:255',
            'numeric_value' => 'nullable|string|max:255',
            'suffix'        => 'nullable|string|max:50',
            'color'         => 'nullable|string|max:50',
            'bg_gradient'   => 'nullable|string|max:255',
            'sort_order'    => 'required|integer',
            'context'       => 'nullable|in:home,about,portfolio',
        ], AdminLocales::fieldRules([
            'label'    => 'string|max:255',
            'sublabel' => 'nullable|string|max:255',
        ], requiredFields: ['label'])));

        $stat->update([
            'icon'          => $request->icon,
            'numeric_value' => $request->numeric_value,
            'suffix'        => $request->suffix,
            'color'         => $request->color,
            'bg_gradient'   => $request->bg_gradient,
            'sort_order'    => $request->sort_order,
            'context'       => $request->context,
        ]);

        AdminLocales::syncTranslations($stat, $request, ['label', 'sublabel']);

        return redirect()->route('admin.stats.index')->with('success', 'Stat updated successfully.');
    }

    public function destroy(Stat $stat)
    {
        $stat->delete();

        return redirect()->route('admin.stats.index')->with('success', 'Stat deleted successfully.');
    }
}
