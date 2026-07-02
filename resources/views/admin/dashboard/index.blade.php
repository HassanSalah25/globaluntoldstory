@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<span class="text-gray-700 font-medium">Dashboard</span>
@endsection

@section('content')

{{-- Multilingual editing --}}
<div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
    <span class="font-semibold">Multilingual CMS:</span>
    Content forms support
    @foreach($adminLocales as $i => $locale)
        <span class="font-medium">{{ $locale['native'] }}</span>@if($locale['required'])<span class="text-red-600">*</span>@endif@if($i < count($adminLocales) - 1), @endif
    @endforeach
    . English is required; all other languages are optional.
</div>

{{-- ── Stat Cards ─────────────────────────────────── --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

    {{-- Services --}}
    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm ring-1 ring-gray-950/5">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['services'] }}</p>
            <p class="text-sm text-gray-500">Services</p>
        </div>
    </div>

    {{-- Portfolio Items --}}
    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm ring-1 ring-gray-950/5">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-100">
            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['portfolio_items'] }}</p>
            <p class="text-sm text-gray-500">Portfolio Items</p>
        </div>
    </div>

    {{-- Blog Posts --}}
    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm ring-1 ring-gray-950/5">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['blog_posts'] }}</p>
            <p class="text-sm text-gray-500">Blog Posts</p>
        </div>
    </div>

    {{-- Team Members --}}
    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm ring-1 ring-gray-950/5">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-100">
            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['team_members'] }}</p>
            <p class="text-sm text-gray-500">Team Members</p>
        </div>
    </div>

    {{-- New Leads --}}
    <div class="flex items-center gap-4 rounded-2xl border border-red-100 bg-white p-5 shadow-sm ring-1 ring-red-600/10">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['new_leads'] }}</p>
            <p class="text-sm text-gray-500">New Leads</p>
        </div>
        @if($stats['new_leads'] > 0)
        <span class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">
            !
        </span>
        @endif
    </div>

    {{-- Unread Contacts --}}
    <div class="flex items-center gap-4 rounded-2xl border border-amber-100 bg-white p-5 shadow-sm ring-1 ring-amber-600/10">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100">
            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['unread_contacts'] }}</p>
            <p class="text-sm text-gray-500">Unread Contacts</p>
        </div>
        @if($stats['unread_contacts'] > 0)
        <span class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-white">
            !
        </span>
        @endif
    </div>

    {{-- Newsletter --}}
    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm ring-1 ring-gray-950/5">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-teal-100">
            <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['newsletter_subs'] }}</p>
            <p class="text-sm text-gray-500">Subscribers</p>
        </div>
    </div>

</div>

{{-- ── Tables Row ──────────────────────────────────── --}}
<div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">

    {{-- Recent Contact Requests --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm ring-1 ring-gray-950/5">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Contact Requests</h3>
                <p class="text-xs text-gray-500 mt-0.5">Latest 5 submissions</p>
            </div>
            <a href="{{ route('admin.contact-requests.index') }}"
               class="text-xs font-medium text-red-600 hover:text-red-500 transition-colors">
                View all &rarr;
            </a>
        </div>

        @if($recentContacts->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-gray-400">No contact requests yet</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Ref / Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentContacts as $contact)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="font-medium text-gray-900">{{ $contact->name }}</div>
                            @if($contact->reference_id)
                            <div class="text-xs text-gray-400 font-mono">{{ $contact->reference_id }}</div>
                            @endif
                            <div class="text-xs text-gray-400">{{ $contact->email }}</div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="text-gray-600 text-xs">{{ $contact->service ?: '—' }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            @php
                            $statusColors = [
                                'new'        => 'bg-blue-100 text-blue-700',
                                'read'       => 'bg-gray-100 text-gray-600',
                                'replied'    => 'bg-green-100 text-green-700',
                                'archived'   => 'bg-yellow-100 text-yellow-700',
                            ];
                            $color = $statusColors[$contact->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $color }}">
                                {{ ucfirst($contact->status ?? 'new') }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                            {{ $contact->created_at?->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Recent Leads --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm ring-1 ring-gray-950/5">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Leads</h3>
                <p class="text-xs text-gray-500 mt-0.5">Latest 5 lead submissions</p>
            </div>
            <a href="{{ route('admin.leads.index') }}"
               class="text-xs font-medium text-red-600 hover:text-red-500 transition-colors">
                View all &rarr;
            </a>
        </div>

        @if($recentLeads->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm text-gray-400">No leads yet</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Ref / Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentLeads as $lead)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                            @if($lead->reference_id)
                            <div class="text-xs text-gray-400 font-mono">{{ $lead->reference_id }}</div>
                            @endif
                            <div class="text-xs text-gray-400">{{ $lead->email }}</div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="text-gray-600 text-xs">
                                {{ $lead->service_text ?: ($lead->service?->slug ?? '—') }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            @php
                            $leadColors = [
                                'new'          => 'bg-blue-100 text-blue-700',
                                'contacted'    => 'bg-indigo-100 text-indigo-700',
                                'qualified'    => 'bg-purple-100 text-purple-700',
                                'proposal'     => 'bg-orange-100 text-orange-700',
                                'won'          => 'bg-green-100 text-green-700',
                                'lost'         => 'bg-red-100 text-red-700',
                                'archived'     => 'bg-gray-100 text-gray-600',
                            ];
                            $lc = $leadColors[$lead->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $lc }}">
                                {{ ucfirst($lead->status ?? 'new') }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                            {{ $lead->created_at?->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

@endsection
