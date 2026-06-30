<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
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
        $locales = ['en', 'ar'];

        return view('admin.faqs.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sort_order'   => 'required|integer',
            'question_en'  => 'required|string|max:255',
            'question_ar'  => 'required|string|max:255',
            'answer_en'    => 'nullable|string',
            'answer_ar'    => 'nullable|string',
        ]);

        $faq = Faq::create([
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $faq->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'question' => $request->input("question_{$locale}"),
                    'answer'   => $request->input("answer_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $locales = ['en', 'ar'];
        $translations = $faq->translations->keyBy('locale');

        return view('admin.faqs.edit', compact('faq', 'locales', 'translations'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'sort_order'   => 'required|integer',
            'question_en'  => 'required|string|max:255',
            'question_ar'  => 'required|string|max:255',
            'answer_en'    => 'nullable|string',
            'answer_ar'    => 'nullable|string',
        ]);

        $faq->update([
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $faq->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'question' => $request->input("question_{$locale}"),
                    'answer'   => $request->input("answer_{$locale}"),
                ]
            );
        }

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
