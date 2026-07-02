<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Support\AdminLocales;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->paginate(15);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
        ]);

        Menu::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        session()->flash('success', 'Menu created successfully.');

        return redirect()->route('admin.menus.index');
    }

    public function edit(Menu $menu)
    {
        $menu->load(['items' => function ($query) {
            $query->orderBy('sort_order')->with('translations');
        }]);

        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
        ]);

        $menu->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        session()->flash('success', 'Menu updated successfully.');

        return redirect()->route('admin.menus.index');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        session()->flash('success', 'Menu deleted successfully.');

        return redirect()->route('admin.menus.index');
    }

    public function storeItem(Request $request, Menu $menu)
    {
        $request->validate(array_merge([
            'parent_id'  => 'nullable|exists:menu_items,id',
            'url'        => 'required|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ], AdminLocales::fieldRules([
            'label' => 'string|max:255',
        ], requiredFields: ['label'])));

        $item = $menu->items()->create([
            'parent_id'  => $request->parent_id,
            'url'        => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        AdminLocales::syncTranslations($item, $request, ['label']);

        session()->flash('success', 'Menu item created successfully.');

        return redirect()->route('admin.menus.edit', $menu);
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $request->validate(array_merge([
            'parent_id'  => 'nullable|exists:menu_items,id',
            'url'        => 'required|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ], AdminLocales::fieldRules([
            'label' => 'string|max:255',
        ], requiredFields: ['label'])));

        $item->update([
            'parent_id'  => $request->parent_id,
            'url'        => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        AdminLocales::syncTranslations($item, $request, ['label']);

        session()->flash('success', 'Menu item updated successfully.');

        return redirect()->route('admin.menus.edit', $menu);
    }

    public function destroyItem(Menu $menu, MenuItem $item)
    {
        $item->delete();

        session()->flash('success', 'Menu item deleted successfully.');

        return redirect()->route('admin.menus.edit', $menu);
    }
}
