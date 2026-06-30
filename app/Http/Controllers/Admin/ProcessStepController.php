<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    public function index(Request $request)
    {
        $query = ProcessStep::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('title', 'like', "%{$search}%");
            });
        }

        $processSteps = $query->paginate(15)->withQueryString();

        return view('admin.process-steps.index', compact('processSteps'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];

        return view('admin.process-steps.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'step_number'     => 'required|integer',
            'sort_order'      => 'required|integer',
            'title_en'        => 'required|string|max:255',
            'title_ar'        => 'required|string|max:255',
            'description_en'  => 'nullable|string',
            'description_ar'  => 'nullable|string',
        ]);

        $processStep = ProcessStep::create([
            'step_number' => $request->step_number,
            'sort_order'  => $request->sort_order,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $processStep->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step created successfully.');
    }

    public function edit(ProcessStep $processStep)
    {
        $locales = ['en', 'ar'];
        $translations = $processStep->translations->keyBy('locale');

        return view('admin.process-steps.edit', compact('processStep', 'locales', 'translations'));
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $request->validate([
            'step_number'     => 'required|integer',
            'sort_order'      => 'required|integer',
            'title_en'        => 'required|string|max:255',
            'title_ar'        => 'required|string|max:255',
            'description_en'  => 'nullable|string',
            'description_ar'  => 'nullable|string',
        ]);

        $processStep->update([
            'step_number' => $request->step_number,
            'sort_order'  => $request->sort_order,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $processStep->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step updated successfully.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step deleted successfully.');
    }
}
