<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMember::with('translations')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('name', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(15)->withQueryString();

        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];

        return view('admin.team.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_url'  => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'required|string|max:255',
            'role_en'    => 'nullable|string|max:255',
            'role_ar'    => 'nullable|string|max:255',
            'bio_en'     => 'nullable|string',
            'bio_ar'     => 'nullable|string',
        ]);

        $teamMember = TeamMember::create([
            'slug'       => Str::slug($request->name_en),
            'image_url'  => $request->image_url,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $teamMember->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $request->input("name_{$locale}"),
                    'role' => $request->input("role_{$locale}"),
                    'bio'  => $request->input("bio_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.team.index')->with('success', 'Team member created successfully.');
    }

    public function edit(TeamMember $team)
    {
        $locales = ['en', 'ar'];
        $member = $team;
        $translations = $team->translations->keyBy('locale');

        return view('admin.team.edit', compact('member', 'locales', 'translations'));
    }

    public function update(Request $request, TeamMember $team)
    {
        $request->validate([
            'slug'       => 'required|string|max:255|unique:team_members,slug,' . $team->id,
            'image_url'  => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'required|string|max:255',
            'role_en'    => 'nullable|string|max:255',
            'role_ar'    => 'nullable|string|max:255',
            'bio_en'     => 'nullable|string',
            'bio_ar'     => 'nullable|string',
        ]);

        $team->update([
            'slug'       => $request->slug,
            'image_url'  => $request->image_url,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        foreach (['en', 'ar'] as $locale) {
            $team->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $request->input("name_{$locale}"),
                    'role' => $request->input("role_{$locale}"),
                    'bio'  => $request->input("bio_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $team)
    {
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully.');
    }

    public function toggle(TeamMember $teamMember)
    {
        $teamMember->update(['is_active' => ! $teamMember->is_active]);

        return redirect()->back()->with('success', 'Team member status updated.');
    }
}
