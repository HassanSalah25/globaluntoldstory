<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineEvent;
use App\Support\AdminLocales;
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

        $events = $query->paginate(15)->withQueryString();

        return view('admin.timeline.index', compact('events'));
    }

    public function create()
    {
        return view('admin.timeline.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'year'       => 'required|string|max:20',
            'icon'       => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $timelineEvent = TimelineEvent::create([
            'year'       => $request->year,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order,
        ]);

        AdminLocales::syncTranslations($timelineEvent, $request, ['title', 'description']);

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline event created successfully.');
    }

    public function edit(TimelineEvent $timeline)
    {
        $event = $timeline;
        $translations = $timeline->translations->keyBy('locale');

        return view('admin.timeline.edit', compact('event', 'translations'));
    }

    public function update(Request $request, TimelineEvent $timeline)
    {
        $request->validate(array_merge([
            'year'       => 'required|string|max:20',
            'icon'       => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
        ], requiredFields: ['title'])));

        $timeline->update([
            'year'       => $request->year,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order,
        ]);

        AdminLocales::syncTranslations($timeline, $request, ['title', 'description']);

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline event updated successfully.');
    }

    public function destroy(TimelineEvent $timeline)
    {
        $timeline->delete();

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline event deleted successfully.');
    }
}
