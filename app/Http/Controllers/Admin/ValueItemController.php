<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValueItem;
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
        $request->validate([
            'icon'            => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
            'title_en'        => 'required|string|max:255',
            'title_ar'        => 'nullable|string|max:255',
            'description_en'  => 'nullable|string',
            'description_ar'  => 'nullable|string',
        ]);

        $valueItem = ValueItem::create([
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $valueItem->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

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
        $request->validate([
            'icon'            => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
            'title_en'        => 'required|string|max:255',
            'title_ar'        => 'nullable|string|max:255',
            'description_en'  => 'nullable|string',
            'description_ar'  => 'nullable|string',
        ]);

        $valueItem->update([
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $valueItem->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

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
