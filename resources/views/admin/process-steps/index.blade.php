@extends('admin.layouts.app')
@section('title', 'Process Steps')

@section('content')
<div x-data="{ deleteId: null, deleteUrl: '' }">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Process Steps</h2>
            <p class="text-sm text-gray-500 mt-1">Manage production process steps</p>
        </div>
        <a href="{{ route('admin.process-steps.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Step
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Step #</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description (EN)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sort</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($processSteps as $step)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700 text-sm font-bold">{{ $step->step_number }}</span>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $step->translations->where('locale','en')->first()?->title ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $step->translations->where('locale','en')->first()?->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $step->sort_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.process-steps.edit', $step) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium">Edit</a>
                            <button @click="deleteId={{ $step->id }}; deleteUrl='{{ route('admin.process-steps.destroy', $step) }}'" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-sm font-medium">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No process steps found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($processSteps->hasPages())
    <div class="mt-4">{{ $processSteps->links() }}</div>
    @endif

    <div x-show="deleteId" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
            <p class="text-gray-600 mb-4">Delete this process step? This cannot be undone.</p>
            <div class="flex gap-3">
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-medium">Delete</button>
                </form>
                <button @click="deleteId=null" class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
