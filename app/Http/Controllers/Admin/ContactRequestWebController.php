<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestWebController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactRequest::latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%");
            });
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $contactRequests = $query->paginate(20)->withQueryString();
        $unreadCount     = ContactRequest::whereNull('read_at')->count();

        return view('admin.contact-requests.index', compact('contactRequests', 'unreadCount'));
    }

    public function show(ContactRequest $contactRequest)
    {
        if (! $contactRequest->read_at) {
            $contactRequest->read_at = now();
            if ($contactRequest->status === 'new') {
                $contactRequest->status = 'read';
            }
            $contactRequest->save();
        }

        return view('admin.contact-requests.show', compact('contactRequest'));
    }

    public function updateStatus(Request $request, ContactRequest $contactRequest)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied,archived',
        ]);

        $contactRequest->update(['status' => $request->input('status')]);

        return redirect()->back()->with('success', 'Status updated.');
    }

    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();

        return redirect()->route('admin.contact-requests.index')->with('success', 'Contact request deleted.');
    }
}
