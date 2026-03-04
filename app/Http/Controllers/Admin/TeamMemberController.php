<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = \App\Models\TeamMember::latest()->paginate(10);
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        \App\Models\TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    public function edit(\App\Models\TeamMember $team)
    {
        // Note: Route parameter is 'team' because of resource name 'team'
        $member = $team;
        return view('admin.team.edit', compact('member'));
    }

    public function update(Request $request, \App\Models\TeamMember $team)
    {
        $member = $team;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($member->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($member->image);
            }
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        $member->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(\App\Models\TeamMember $team)
    {
        $member = $team;
        if ($member->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($member->image);
        }
        $member->delete();
        return redirect()->route('admin.team.index')->with('success', 'Team member removed successfully.');
    }
}
