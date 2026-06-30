@extends('admin.layouts.app')
@section('title', 'Site Settings')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Site Settings</h2>
    <p class="text-sm text-gray-500 mt-1">Manage global site configuration</p>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
@endif

@forelse($settings as $group => $groupSettings)
<div class="mb-8">
    <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-500 mb-3 px-1">{{ ucfirst(str_replace('_', ' ', $group)) }}</h3>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Key</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Value Preview</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($groupSettings as $setting)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <code class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded font-mono">{{ $setting->key }}</code>
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        @php $val = is_array($setting->value) ? json_encode($setting->value) : (string)$setting->value; @endphp
                        <span class="truncate block text-sm">{{ Str::limit($val, 80) ?: '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.settings.edit', $setting) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
    <p class="text-gray-400">No settings found.</p>
    <p class="text-xs text-gray-400 mt-1">Run <code class="bg-gray-100 px-1 rounded">php artisan db:seed --class=ContentSeeder</code> to populate settings.</p>
</div>
@endforelse

@endsection
