@extends('admin.layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="mb-6 text-center">
    <h2 class="text-xl font-bold text-white">Set a new password</h2>
    <p class="mt-1 text-sm text-gray-500">Choose a strong password for your account.</p>
</div>

@if($errors->any())
<div class="mb-5 rounded-lg border border-red-800 bg-red-950/60 p-4">
    <ul class="space-y-1 text-sm text-red-400">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email address</label>
        <input type="email"
               id="email"
               name="email"
               value="{{ $email ?? old('email') }}"
               required
               autocomplete="email"
               class="w-full rounded-xl border bg-gray-800 border-gray-700 px-4 py-3 text-sm text-white placeholder-gray-500
                      focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                      @error('email') border-red-500 @enderror"
               placeholder="admin@example.com">
        @error('email')
        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="{ show: false }">
        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">New password</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'"
                   id="password"
                   name="password"
                   required
                   autocomplete="new-password"
                   class="w-full rounded-xl border bg-gray-800 border-gray-700 px-4 py-3 pr-12 text-sm text-white placeholder-gray-500
                          focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                          @error('password') border-red-500 @enderror"
                   placeholder="Min. 8 characters">
            <button type="button" @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
                <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>
        @error('password')
        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">
            Confirm new password
        </label>
        <input type="password"
               id="password_confirmation"
               name="password_confirmation"
               required
               autocomplete="new-password"
               class="w-full rounded-xl border bg-gray-800 border-gray-700 px-4 py-3 text-sm text-white placeholder-gray-500
                      focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors"
               placeholder="Repeat your new password">
    </div>

    <button type="submit"
            class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20
                   transition-all hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900
                   active:scale-[0.98]">
        Reset Password
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-500">
    <a href="{{ route('admin.login') }}" class="text-red-500 hover:text-red-400 transition-colors font-medium">
        &larr; Back to Sign In
    </a>
</p>
@endsection
