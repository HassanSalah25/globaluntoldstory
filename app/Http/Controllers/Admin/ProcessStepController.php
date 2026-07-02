<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use App\Support\AdminLocales;
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
        return view('admin.process-steps.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'step_number' => 'required|integer',
            'sort_order'  => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $processStep = ProcessStep::create([
            'step_number' => $request->step_number,
            'sort_order'  => $request->sort_order,
        ]);

        AdminLocales::syncTranslations($processStep, $request, ['title', 'description']);

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step created successfully.');
    }

    public function edit(ProcessStep $processStep)
    {
        $translations = $processStep->translations->keyBy('locale');

        return view('admin.process-steps.edit', compact('processStep', 'translations'));
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $request->validate(array_merge([
            'step_number' => 'required|integer',
            'sort_order'  => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $processStep->update([
            'step_number' => $request->step_number,
            'sort_order'  => $request->sort_order,
        ]);

        AdminLocales::syncTranslations($processStep, $request, ['title', 'description']);

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step updated successfully.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step deleted successfully.');
    }
}
