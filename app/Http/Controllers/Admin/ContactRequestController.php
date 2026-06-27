<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ContactRequest::query()->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $items = $query->paginate((int) $request->query('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    public function show(ContactRequest $contactRequest): JsonResponse
    {
        if (! $contactRequest->read_at) {
            $contactRequest->update([
                'read_at' => now(),
                'status' => $contactRequest->status === 'new' ? 'read' : $contactRequest->status,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $contactRequest->fresh(),
        ]);
    }

    public function update(Request $request, ContactRequest $contactRequest): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new,read,replied,archived'],
        ]);

        $contactRequest->update($validated);

        return response()->json([
            'success' => true,
            'data' => $contactRequest->fresh(),
        ]);
    }
}
