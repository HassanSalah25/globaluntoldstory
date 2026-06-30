@extends('admin.layouts.app')
@section('title', 'Contact Requests')

@section('content')
<div x-data="{ deleteId: null, deleteUrl: '' }">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                Contact Requests
                @if($unreadCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-600 text-white">{{ $unreadCount }} new</span>
                @endif
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ $contactRequests->total() }} total requests</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, service..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
            <a href="{{ route('admin.contact-requests.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Clear</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Budget</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($contactRequests as $req)
                <tr class="hover:bg-gray-50 transition-colors {{ !$req->read_at ? 'bg-blue-50/30' : '' }}">
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $req->reference_id }}</span>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $req->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $req->email }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $req->service ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $req->budget ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'new'      => 'bg-blue-100 text-blue-800',
                                'read'     => 'bg-gray-100 text-gray-600',
                                'replied'  => 'bg-green-100 text-green-800',
                                'archived' => 'bg-gray-200 text-gray-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$req->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $req->created_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.contact-requests.show', $req) }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium">View</a>
                            <button @click="deleteId = {{ $req->id }}; deleteUrl = '{{ route('admin.contact-requests.destroy', $req) }}'"
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">No contact requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contactRequests->hasPages())
    <div class="mt-4">{{ $contactRequests->links() }}</div>
    @endif

    {{-- Delete Confirm Modal --}}
    <div x-show="deleteId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this contact request? This cannot be undone.</p>
            <div class="flex gap-3">
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Delete</button>
                </form>
                <button @click="deleteId = null" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">Cancel</button>
            </div>
        </div>
    </div>

</div>
@endsection
