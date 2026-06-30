@extends('admin.layouts.app')

@section('title', 'Add New Menu')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.menus.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Menu</h1>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{
            name: '{{ old('name') }}',
            get slug() { return this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''); }
        }">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Menu Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Menu Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Main Navigation"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug"
                           :value="'{{ old('slug') }}' || slug"
                           :placeholder="slug || 'auto-generated'"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono">
                    <p class="text-xs text-gray-400 mt-1">Auto-suggested from name. Used to reference this menu in templates.</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Create Menu
            </button>
            <a href="{{ route('admin.menus.index') }}"
               class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
