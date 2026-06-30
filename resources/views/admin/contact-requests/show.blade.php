@extends('admin.layouts.app')
@section('title', 'Contact Request: ' . $contactRequest->reference_id)

@section('content')
<div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.contact-requests.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Contact Request</h2>
            <p class="text-sm text-gray-500 font-mono mt-0.5">{{ $contactRequest->reference_id }}</p>
        </div>
        @php
            $statusColors = [
                'new'      => 'bg-blue-100 text-blue-800',
                'read'     => 'bg-gray-100 text-gray-600',
                'replied'  => 'bg-green-100 text-green-800',
                'archived' => 'bg-gray-200 text-gray-700',
            ];
        @endphp
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$contactRequest->status] ?? '' }}">
            {{ ucfirst($contactRequest->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Contact Details --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Contact Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $contactRequest->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900"><a href="mailto:{{ $contactRequest->email }}" class="text-blue-600 hover:underline">{{ $contactRequest->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contactRequest->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Service</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contactRequest->service ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contactRequest->budget ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Locale</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($contactRequest->locale ?? '—') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Message</h3>
                <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed bg-gray-50 rounded-lg p-4">{{ $contactRequest->message ?? 'No message provided.' }}</div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Update Status --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Update Status</h3>
                <form method="POST" action="{{ route('admin.contact-requests.updateStatus', $contactRequest) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent mb-3">
                        <option value="new"      {{ $contactRequest->status === 'new'      ? 'selected' : '' }}>New</option>
                        <option value="read"     {{ $contactRequest->status === 'read'     ? 'selected' : '' }}>Read</option>
                        <option value="replied"  {{ $contactRequest->status === 'replied'  ? 'selected' : '' }}>Replied</option>
                        <option value="archived" {{ $contactRequest->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Save Status</button>
                </form>
            </div>

            {{-- Meta Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Metadata</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $contactRequest->created_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    @if($contactRequest->read_at)
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Read At</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $contactRequest->read_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</dt>
                        <dd class="mt-1 text-sm text-gray-700 font-mono">{{ $contactRequest->ip ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Delete --}}
            <div x-data="{ confirm: false }">
                <button @click="confirm = true" class="w-full bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium">
                    Delete Request
                </button>
                <div x-show="confirm" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
                        <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
                        <p class="text-gray-600 mb-4">Delete this contact request permanently?</p>
                        <div class="flex gap-3">
                            <form action="{{ route('admin.contact-requests.destroy', $contactRequest) }}" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Delete</button>
                            </form>
                            <button @click="confirm = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
