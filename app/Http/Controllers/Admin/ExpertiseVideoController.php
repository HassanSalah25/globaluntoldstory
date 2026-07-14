<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpertiseVideo;
use App\Support\AdminLocales;
use App\Support\ExpertiseCategories;
use Illuminate\Http\Request;

class ExpertiseVideoController extends Controller
{
    public function index()
    {
        $videos = ExpertiseVideo::with('translations')->orderBy('sort_order')->paginate(15);

        return view('admin.expertise-videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.expertise-videos.create', [
            'categories' => ExpertiseCategories::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'video_url'  => 'required|string|max:2048',
            'poster_url' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'tag'   => 'string|max:100',
            'title' => 'nullable|string|max:255',
        ], requiredFields: ['tag'])));

        $video = ExpertiseVideo::create([
            'video_url'  => $request->video_url,
            'poster_url' => $request->poster_url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        AdminLocales::syncTranslations($video, $request, ['tag', 'title']);

        return redirect()
            ->route('admin.expertise-videos.index')
            ->with('success', 'Expertise video created successfully.');
    }

    public function edit(ExpertiseVideo $expertise_video)
    {
        $expertise_video->load('translations');
        $translations = $expertise_video->translations->keyBy('locale');

        return view('admin.expertise-videos.edit', [
            'video' => $expertise_video,
            'translations' => $translations,
            'categories' => ExpertiseCategories::all(),
        ]);
    }

    public function update(Request $request, ExpertiseVideo $expertise_video)
    {
        $request->validate(array_merge([
            'video_url'  => 'required|string|max:2048',
            'poster_url' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ], AdminLocales::fieldRules([
            'tag'   => 'string|max:100',
            'title' => 'nullable|string|max:255',
        ], requiredFields: ['tag'])));

        $expertise_video->update([
            'video_url'  => $request->video_url,
            'poster_url' => $request->poster_url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($expertise_video, $request, ['tag', 'title']);

        return redirect()
            ->route('admin.expertise-videos.index')
            ->with('success', 'Expertise video updated successfully.');
    }

    public function destroy(ExpertiseVideo $expertise_video)
    {
        $expertise_video->delete();

        return redirect()
            ->route('admin.expertise-videos.index')
            ->with('success', 'Expertise video deleted successfully.');
    }

    public function toggle(ExpertiseVideo $expertise_video)
    {
        $expertise_video->update(['is_active' => ! $expertise_video->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Expertise video status updated.');
    }
}
