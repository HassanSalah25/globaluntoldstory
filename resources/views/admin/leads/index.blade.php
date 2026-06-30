@extends('admin.layouts.app')
@section('title', 'Leads')

@section('content')
<div x-data="{ deleteId: null, deleteUrl: '' }">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Leads</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $leads->total() }} total leads</p>
        </div>
    </div>

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-2 mb-5">
        <a href="{{ route('admin.leads.index') }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All</a>
        @foreach(['new' => 'New', 'contacted' => 'Contacted', 'proposal' => 'Proposal', 'won' => 'Won', 'lost' => 'Lost', 'archived' => 'Archived'] as $val => $label)
        <a href="{{ route('admin.leads.index', ['status' => $val]) }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('status') === $val ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $label }}</a>
        @endforeach
    </div>

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
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leads as $lead)
                @php
                    $statusColors = [
                        'new'       => 'bg-blue-100 text-blue-800',
                        'contacted' => 'bg-yellow-100 text-yellow-800',
                        'proposal'  => 'bg-purple-100 text-purple-800',
                        'won'       => 'bg-green-100 text-green-800',
                        'lost'      => 'bg-red-100 text-red-800',
                        'archived'  => 'bg-gray-100 text-gray-600',
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $lead->reference_id }}</span>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $lead->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $lead->email }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $lead->service?->translations?->where('locale','en')->first()?->title ?? $lead->service_text ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $lead->budget ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $lead->assignee?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $lead->created_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.leads.show', $lead) }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium">View</a>
                            <button @click="deleteId = {{ $lead->id }}; deleteUrl = '{{ route('admin.leads.destroy', $lead) }}'"
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-400">No leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
    <div class="mt-4">{{ $leads->links() }}</div>
    @endif

    {{-- Delete Confirm Modal --}}
    <div x-show="deleteId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this lead? This cannot be undone.</p>
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
