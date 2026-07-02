<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Support\AdminLocales;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('question', 'like', "%{$search}%");
            });
        }

        $faqs = $query->paginate(15)->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'question' => 'string|max:255',
            'answer'   => 'nullable|string',
        ], requiredFields: ['question'])));

        $faq = Faq::create([
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($faq, $request, ['question', 'answer']);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $translations = $faq->translations->keyBy('locale');

        return view('admin.faqs.edit', compact('faq', 'translations'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate(array_merge([
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'question' => 'string|max:255',
            'answer'   => 'nullable|string',
        ], requiredFields: ['question'])));

        $faq->update([
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($faq, $request, ['question', 'answer']);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    public function toggle(Faq $faq)
    {
        $faq->update(['is_active' => ! $faq->is_active]);

        return redirect()->back()->with('success', 'FAQ status updated.');
    }
}
