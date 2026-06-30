@extends('admin.layouts.app')
@section('title', 'Edit Team Member')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.team.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Team Member</h2>
            <p class="text-sm text-gray-500">{{ $member->slug }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.team.update', $member) }}">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Profile Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $member->slug ?? '') }}" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="text" name="image_url" value="{{ old('image_url', $member->image_url ?? '') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    @if($member->image_url)
                    <div class="mt-2">
                        <img src="{{ $member->image_url }}" alt="" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ tab: 'en' }">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Translations</h3>
            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" @click="tab = 'en'" :class="tab === 'en' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm -mb-px">English</button>
                <button type="button" @click="tab = 'ar'" :class="tab === 'ar' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm -mb-px">عربي</button>
            </div>

            <div x-show="tab === 'en'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en', $translations['en']->name ?? '') }}" required class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <input type="text" name="role_en" value="{{ old('role_en', $translations['en']->role ?? '') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea name="bio_en" rows="4" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('bio_en', $translations['en']->bio ?? '') }}</textarea>
                </div>
            </div>

            <div x-show="tab === 'ar'" dir="rtl" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الاسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $translations['ar']->name ?? '') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">المسمى الوظيفي</label>
                    <input type="text" name="role_ar" value="{{ old('role_ar', $translations['ar']->role ?? '') }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">السيرة الذاتية</label>
                    <textarea name="bio_ar" rows="4" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">{{ old('bio_ar', $translations['ar']->bio ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium">Save Changes</button>
            <a href="{{ route('admin.team.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
