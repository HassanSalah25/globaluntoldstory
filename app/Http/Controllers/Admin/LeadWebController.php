<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['service', 'assignee'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $leads = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('admin.leads.index', compact('leads', 'users'));
    }

    public function show(Lead $lead)
    {
        $lead->load(['service', 'assignee']);
        $users = User::orderBy('name')->get();

        return view('admin.leads.show', compact('lead', 'users'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status'      => 'required|in:new,contacted,proposal,won,lost,archived',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead->update([
            'status'      => $request->input('status'),
            'assigned_to' => $request->input('assigned_to'),
        ]);

        return redirect()->back()->with('success', 'Lead updated.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }
}
