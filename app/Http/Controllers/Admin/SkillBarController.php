<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillBar;
use Illuminate\Http\Request;

class SkillBarController extends Controller
{
    public function index()
    {
        $skillBars = SkillBar::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.skill-bars.index', compact('skillBars'));
    }

    public function create()
    {
        return view('admin.skill-bars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'percent'    => 'required|integer|min:0|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'label_en'   => 'required|string|max:255',
            'label_ar'   => 'nullable|string|max:255',
        ]);

        $skillBar = SkillBar::create([
            'percent'    => $request->percent,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $skillBar->translations()->updateOrCreate(
                ['locale' => $locale],
                ['label' => $request->input("label_{$locale}")]
            );
        }

        session()->flash('success', 'Skill bar created successfully.');

        return redirect()->route('admin.skill-bars.index');
    }

    public function edit(SkillBar $skillBar)
    {
        $skillBar->load('translations');

        return view('admin.skill-bars.edit', compact('skillBar'));
    }

    public function update(Request $request, SkillBar $skillBar)
    {
        $request->validate([
            'percent'    => 'required|integer|min:0|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'label_en'   => 'required|string|max:255',
            'label_ar'   => 'nullable|string|max:255',
        ]);

        $skillBar->update([
            'percent'    => $request->percent,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $skillBar->translations()->updateOrCreate(
                ['locale' => $locale],
                ['label' => $request->input("label_{$locale}")]
            );
        }

        session()->flash('success', 'Skill bar updated successfully.');

        return redirect()->route('admin.skill-bars.index');
    }

    public function destroy(SkillBar $skillBar)
    {
        $skillBar->delete();

        session()->flash('success', 'Skill bar deleted successfully.');

        return redirect()->route('admin.skill-bars.index');
    }
}
