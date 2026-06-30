@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('breadcrumb')
<span class="text-gray-700 font-medium">My Profile</span>
@endsection

@section('content')
<div class="mx-auto  space-y-6">

    {{-- ── Profile Info ─────────────────────────────── --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm ring-1 ring-gray-950/5">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-sm font-semibold text-gray-900">Profile Information</h2>
            <p class="mt-0.5 text-xs text-gray-500">Update your name and email address.</p>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="px-6 py-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-5 mb-6">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-red-600 text-xl font-bold text-white shadow-md">
                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(strstr($user->name ?? ' ', ' '), 1, 1)) ?: '' }}
                </div>
                <div>
                    <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    @if($user->roles->first())
                    <span class="mt-1 inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700">
                        {{ ucfirst($user->roles->first()->name) }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           autocomplete="name"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                  focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                                  @error('name') border-red-400 ring-1 ring-red-400 @enderror">
                    @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           autocomplete="email"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                  focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                                  @error('email') border-red-400 ring-1 ring-red-400 @enderror">
                    @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                               transition-all hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                               active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- ── Change Password ──────────────────────────── --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm ring-1 ring-gray-950/5">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-sm font-semibold text-gray-900">Change Password</h2>
            <p class="mt-0.5 text-xs text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Current password
                </label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       required
                       autocomplete="current-password"
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900
                              focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                              @error('current_password') border-red-400 ring-1 ring-red-400 @enderror"
                       placeholder="••••••••">
                @error('current_password')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        New password
                    </label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'"
                               id="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 pr-12 text-sm text-gray-900
                                      focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                                      @error('password') border-red-400 ring-1 ring-red-400 @enderror"
                               placeholder="Min. 8 characters">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Confirm new password
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900
                                  focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors"
                           placeholder="Repeat new password">
                </div>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                               transition-all hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2
                               active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
