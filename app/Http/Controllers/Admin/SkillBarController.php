<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillBar;
use App\Support\AdminLocales;
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
        $request->validate(array_merge([
            'percent'    => 'required|integer|min:0|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'label' => 'string|max:255',
        ], requiredFields: ['label'])));

        $skillBar = SkillBar::create([
            'percent'    => $request->percent,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($skillBar, $request, ['label']);

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
        $request->validate(array_merge([
            'percent'    => 'required|integer|min:0|max:100',
            'color'      => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'label' => 'string|max:255',
        ], requiredFields: ['label'])));

        $skillBar->update([
            'percent'    => $request->percent,
            'color'      => $request->color,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AdminLocales::syncTranslations($skillBar, $request, ['label']);

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
