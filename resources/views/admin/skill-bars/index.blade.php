@extends('admin.layouts.app')

@section('title', 'Skill Bars')

@section('content')
<div x-data="{ confirm: false, deleteUrl: '' }">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Skill Bars</h1>
        <a href="{{ route('admin.skill-bars.create') }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            + Add New Skill Bar
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label (EN)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($skillBars as $skillBar)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $skillBar->translations->where('locale','en')->first()->label ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full"
                                             style="width: {{ $skillBar->percent }}%; background-color: {{ $skillBar->color ?? '#DC2626' }};"></div>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $skillBar->percent }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full border border-gray-200"
                                         style="background-color: {{ $skillBar->color ?? '#DC2626' }};"></div>
                                    <span class="text-sm text-gray-600">{{ $skillBar->color ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $skillBar->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.skill-bars.edit', $skillBar) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium mr-4">Edit</a>

                                <form id="del-sb-{{ $skillBar->id }}"
                                      action="{{ route('admin.skill-bars.destroy', $skillBar) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button @click="confirm=true; deleteUrl='del-sb-{{ $skillBar->id }}'"
                                        class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                No skill bars found. <a href="{{ route('admin.skill-bars.create') }}" class="text-red-600 hover:underline">Add one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($skillBars->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $skillBars->links() }}
            </div>
        @endif
    </div>

    {{-- Delete confirm modal --}}
    <div x-show="confirm" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <p class="font-semibold mb-2">Delete this skill bar?</p>
            <p class="text-sm text-gray-500 mb-4">This action cannot be undone.</p>
            <div class="flex gap-2">
                <button @click="document.getElementById(deleteUrl).submit()"
                        class="flex-1 bg-red-600 text-white py-2 rounded-lg text-sm">Yes, Delete</button>
                <button @click="confirm=false"
                        class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg text-sm">Cancel</button>
            </div>
        </div>
    </div>

</div>
@endsection
