@extends('admin.layouts.app')
@section('title', 'Newsletter Subscriptions')

@section('content')
<div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Newsletter Subscriptions</h2>
            <p class="text-sm text-gray-500 mt-1">Manage email subscribers</p>
        </div>
        <a href="{{ route('admin.newsletter.export') }}"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export CSV
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Subscribers</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalCount) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active Subscribers</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($activeCount) }}</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-2 mb-5">
        <a href="{{ route('admin.newsletter.index') }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ !request('filter') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All</a>
        <a href="{{ route('admin.newsletter.index', ['filter' => 'active']) }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('filter') === 'active' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Active</a>
        <a href="{{ route('admin.newsletter.index', ['filter' => 'inactive']) }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('filter') === 'inactive' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Inactive</a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Locale</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subscribed</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unsubscribed</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $sub->email }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ strtoupper($sub->locale ?? '—') }}</td>
                    <td class="px-4 py-3">
                        @if($sub->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $sub->subscribed_at ? $sub->subscribed_at->format('M j, Y') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $sub->unsubscribed_at ? $sub->unsubscribed_at->format('M j, Y') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('admin.newsletter.toggle', $sub) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="{{ $sub->is_active ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-3 py-1.5 rounded-lg text-xs font-medium">
                                {{ $sub->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">No subscriptions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subscriptions->hasPages())
    <div class="mt-4">{{ $subscriptions->links() }}</div>
    @endif

</div>
@endsection
