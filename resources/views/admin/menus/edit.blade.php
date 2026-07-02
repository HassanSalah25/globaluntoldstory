@extends('admin.layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)

@section('content')
<div x-data="{ confirm: false, deleteUrl: '' }">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.menus.index') }}"
           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
            ← Back to List
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Menu: <span class="text-red-600">{{ $menu->name }}</span></h1>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Menu name/slug form --}}
    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Menu Settings</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Menu Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name', $menu->name) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug"
                           value="{{ old('slug', $menu->slug) }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm font-mono">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Update Menu
                </button>
            </div>
        </div>
    </form>

    {{-- Existing menu items --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800">Menu Items</h2>
            <span class="text-sm text-gray-500">{{ $menu->items->count() }} item(s)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label (EN)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menu->items->sortBy('sort_order') as $item)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                {{ $item->translations->where('locale','en')->first()->label ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600">
                                <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->url }}</code>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $item->sort_order }}</td>
                            <td class="px-6 py-3">
                                @if($item->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">Yes</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm">
                                <form id="del-mi-{{ $item->id }}"
                                      action="{{ route('admin.menus.destroy-item', [$menu, $item]) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button @click="confirm=true; deleteUrl='del-mi-{{ $item->id }}'"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                No items yet. Add one below.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Menu Item form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Add Menu Item</h2>

        <form action="{{ route('admin.menus.store-item', $menu) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL <span class="text-red-500">*</span></label>
                    <input type="text" name="url"
                           placeholder="e.g. /about or https://..."
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0"
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>
                <div class="sm:col-span-2 flex items-center gap-3">
                    <input type="checkbox" id="item_is_active" name="is_active" value="1" checked
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="item_is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>

            @php $defaultTab = $adminLocales[0]['code'] ?? 'en'; @endphp
            <div class="border border-gray-200 rounded-lg p-4 mb-4" x-data="{ tab: '{{ $defaultTab }}' }">
                <p class="text-sm font-medium text-gray-700 mb-3">Item Label (Translations)</p>
                @include('admin.components.locale-tab-nav')
                @foreach($adminLocales as $locale)
                    @component('admin.components.locale-panel', ['locale' => $locale])
                        @include('admin.components.locale-field', [
                            'name' => 'label',
                            'label' => 'Label',
                            'locale' => $locale,
                            'required' => in_array($locale['code'], ['en'], true),
                            'placeholder' => $locale['code'] === 'en' ? 'e.g. About Us' : 'مثال: من نحن',
                        ])
                    @endcomponent
                @endforeach
            </div>

            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + Add Item
            </button>
        </form>
    </div>

    {{-- Delete confirm modal --}}
    <div x-show="confirm" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <p class="font-semibold mb-2">Delete this menu item?</p>
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
