<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineEvent;
use Illuminate\Http\Request;

class TimelineEventController extends Controller
{
    public function index(Request $request)
    {
        $query = TimelineEvent::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('title', 'like', "%{$search}%");
            });
        }

        $timelineEvents = $query->paginate(15)->withQueryString();

        return view('admin.timeline.index', compact('timelineEvents'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];

        return view('admin.timeline.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year'            => 'required|string|max:20',
            'icon'            => 'nullable|string|max:255',
            'sort_order'      => 'required|integer',
            'title_en'        => 'required|string|max:255',
            'title_ar'        => 'nullable|string|max:255',
            'description_en'  => 'nullable|string',
            'description_ar'  => 'nullable|string',
        ]);

        $timelineEvent = TimelineEvent::create([
            'year'       => $request->year,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $timelineEvent->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline event created successfully.');
    }

    public function edit(TimelineEvent $timelineEvent)
    {
        $locales = ['en', 'ar'];
        $translations = $timelineEvent->translations->keyBy('locale');

        return view('admin.timeline.edit', compact('timelineEvent', 'locales', 'translations'));
    }

    public function update(Request $request, TimelineEvent $timelineEvent)
    {
        $request->validate([
            'year'            => 'required|string|max:20',
            'icon'            => 'nullable|string|max:255',
            'sort_order'      => 'required|integer',
            'title_en'        => 'required|string|max:255',
            'title_ar'        => 'nullable|string|max:255',
            'description_en'  => 'nullable|string',
            'description_ar'  => 'nullable|string',
        ]);

        $timelineEvent->update([
            'year'       => $request->year,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $timelineEvent->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $request->input("title_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline event updated successfully.');
    }

    public function destroy(TimelineEvent $timelineEvent)
    {
        $timelineEvent->delete();

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline event deleted successfully.');
    }
}
