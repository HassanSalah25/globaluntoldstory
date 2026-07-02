<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Support\AdminLocales;
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
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'image_url'  => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'name' => 'string|max:255',
            'role' => 'nullable|string|max:255',
            'bio'  => 'nullable|string',
        ], requiredFields: ['name'])));

        $teamMember = TeamMember::create([
            'slug'       => Str::slug($request->name_en),
            'image_url'  => $request->image_url,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($teamMember, $request, ['name', 'role', 'bio']);

        return redirect()->route('admin.team.index')->with('success', 'Team member created successfully.');
    }

    public function edit(TeamMember $team)
    {
        $member = $team;
        $translations = $team->translations->keyBy('locale');

        return view('admin.team.edit', compact('member', 'translations'));
    }

    public function update(Request $request, TeamMember $team)
    {
        $request->validate(array_merge([
            'slug'       => 'required|string|max:255|unique:team_members,slug,' . $team->id,
            'image_url'  => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ], AdminLocales::fieldRules([
            'name' => 'string|max:255',
            'role' => 'nullable|string|max:255',
            'bio'  => 'nullable|string',
        ], requiredFields: ['name'])));

        $team->update([
            'slug'       => $request->slug,
            'image_url'  => $request->image_url,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        AdminLocales::syncTranslations($team, $request, ['name', 'role', 'bio']);

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
