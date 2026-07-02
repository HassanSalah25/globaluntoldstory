<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValueItem;
use App\Support\AdminLocales;
use Illuminate\Http\Request;

class ValueItemController extends Controller
{
    public function index()
    {
        $valueItems = ValueItem::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.value-items.index', compact('valueItems'));
    }

    public function create()
    {
        return view('admin.value-items.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $valueItem = ValueItem::create([
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($valueItem, $request, ['title', 'description']);

        session()->flash('success', 'Value item created successfully.');

        return redirect()->route('admin.value-items.index');
    }

    public function edit(ValueItem $valueItem)
    {
        $valueItem->load('translations');

        return view('admin.value-items.edit', compact('valueItem'));
    }

    public function update(Request $request, ValueItem $valueItem)
    {
        $request->validate(array_merge([
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $valueItem->update([
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($valueItem, $request, ['title', 'description']);

        session()->flash('success', 'Value item updated successfully.');

        return redirect()->route('admin.value-items.index');
    }

    public function destroy(ValueItem $valueItem)
    {
        $valueItem->delete();

        session()->flash('success', 'Value item deleted successfully.');

        return redirect()->route('admin.value-items.index');
    }
}
