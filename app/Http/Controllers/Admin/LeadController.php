<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Lead::query()->with('service')->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $items = $query->paginate((int) $request->query('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    public function show(Lead $lead): JsonResponse
    {
        $lead->load('service', 'assignee');

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new,contacted,proposal,won,lost,archived'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $lead->update($validated);

        return response()->json([
            'success' => true,
            'data' => $lead->fresh(['service', 'assignee']),
        ]);
    }
}
