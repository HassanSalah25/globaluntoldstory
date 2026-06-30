<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Http\Request;

class ClientLogoController extends Controller
{
    public function index()
    {
        $clientLogos = ClientLogo::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.client-logos.index', compact('clientLogos'));
    }

    public function create()
    {
        return view('admin.client-logos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'boolean',
            'display_name_en'  => 'nullable|string|max:255',
            'display_name_ar'  => 'nullable|string|max:255',
        ]);

        $clientLogo = ClientLogo::create([
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $clientLogo->translations()->updateOrCreate(
                ['locale' => $locale],
                ['display_name' => $request->input("display_name_{$locale}")]
            );
        }

        session()->flash('success', 'Client logo created successfully.');

        return redirect()->route('admin.client-logos.index');
    }

    public function edit(ClientLogo $clientLogo)
    {
        $clientLogo->load('translations');

        return view('admin.client-logos.edit', compact('clientLogo'));
    }

    public function update(Request $request, ClientLogo $clientLogo)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'boolean',
            'display_name_en'  => 'nullable|string|max:255',
            'display_name_ar'  => 'nullable|string|max:255',
        ]);

        $clientLogo->update([
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $clientLogo->translations()->updateOrCreate(
                ['locale' => $locale],
                ['display_name' => $request->input("display_name_{$locale}")]
            );
        }

        session()->flash('success', 'Client logo updated successfully.');

        return redirect()->route('admin.client-logos.index');
    }

    public function destroy(ClientLogo $clientLogo)
    {
        $clientLogo->delete();

        session()->flash('success', 'Client logo deleted successfully.');

        return redirect()->route('admin.client-logos.index');
    }

    public function toggle(ClientLogo $clientLogo)
    {
        $clientLogo->update(['is_active' => ! $clientLogo->is_active]);

        session()->flash('success', 'Client logo status toggled.');

        return redirect()->route('admin.client-logos.index');
    }
}
