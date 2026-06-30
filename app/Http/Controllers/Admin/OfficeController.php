<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.offices.index', compact('offices'));
    }

    public function create()
    {
        return view('admin.offices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'flag'           => 'nullable|string|max:10',
            'city'           => 'required|string|max:100',
            'country'        => 'required|string|max:100',
            'address'        => 'nullable|string|max:500',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'timezone'       => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'is_headquarters' => 'boolean',
            'title_en'       => 'nullable|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'status_en'      => 'nullable|string|max:100',
            'status_ar'      => 'nullable|string|max:100',
        ]);

        $office = Office::create([
            'flag'            => $request->flag,
            'city'            => $request->city,
            'country'         => $request->country,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'timezone'        => $request->timezone,
            'sort_order'      => $request->sort_order ?? 0,
            'is_headquarters' => $request->boolean('is_headquarters', false),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $office->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'  => $request->input("title_{$locale}"),
                    'status' => $request->input("status_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Office created successfully.');

        return redirect()->route('admin.offices.index');
    }

    public function edit(Office $office)
    {
        $office->load('translations');

        return view('admin.offices.edit', compact('office'));
    }

    public function update(Request $request, Office $office)
    {
        $request->validate([
            'flag'           => 'nullable|string|max:10',
            'city'           => 'required|string|max:100',
            'country'        => 'required|string|max:100',
            'address'        => 'nullable|string|max:500',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'timezone'       => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'is_headquarters' => 'boolean',
            'title_en'       => 'nullable|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'status_en'      => 'nullable|string|max:100',
            'status_ar'      => 'nullable|string|max:100',
        ]);

        $office->update([
            'flag'            => $request->flag,
            'city'            => $request->city,
            'country'         => $request->country,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'timezone'        => $request->timezone,
            'sort_order'      => $request->sort_order ?? 0,
            'is_headquarters' => $request->boolean('is_headquarters', false),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $office->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'  => $request->input("title_{$locale}"),
                    'status' => $request->input("status_{$locale}"),
                ]
            );
        }

        session()->flash('success', 'Office updated successfully.');

        return redirect()->route('admin.offices.index');
    }

    public function destroy(Office $office)
    {
        $office->delete();

        session()->flash('success', 'Office deleted successfully.');

        return redirect()->route('admin.offices.index');
    }
}
