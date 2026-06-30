@extends('admin.layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="mb-6 text-center">
    <h2 class="text-xl font-bold text-white">Welcome back</h2>
    <p class="mt-1 text-sm text-gray-500">Sign in to your admin account</p>
</div>

@if($errors->any())
<div class="mb-5 rounded-lg border border-red-800 bg-red-950/60 p-4">
    <div class="flex gap-2">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="space-y-1 text-sm text-red-400">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
    @csrf

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">
            Email address
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               autocomplete="email"
               autofocus
               class="w-full rounded-xl border px-4 py-3 text-sm text-white placeholder-gray-500 transition-colors
                      bg-gray-800 border-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20
                      @error('email') border-red-500 @enderror"
               placeholder="admin@example.com">
        @error('email')
        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div x-data="{ show: false }">
        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">
            Password
        </label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'"
                   id="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   class="w-full rounded-xl border px-4 py-3 pr-12 text-sm text-white placeholder-gray-500 transition-colors
                          bg-gray-800 border-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20
                          @error('password') border-red-500 @enderror"
                   placeholder="••••••••">
            <button type="button"
                    @click="show = !show"
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
    </div>

    {{-- Remember me + Forgot password --}}
    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox"
                   name="remember"
                   id="remember"
                   class="h-4 w-4 rounded border-gray-600 bg-gray-800 text-red-600 focus:ring-red-500/20">
            <span class="text-sm text-gray-400">Remember me</span>
        </label>
        <a href="{{ route('admin.password.request') }}"
           class="text-sm text-red-500 hover:text-red-400 transition-colors">
            Forgot password?
        </a>
    </div>

    {{-- Submit --}}
    <button type="submit"
            class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20
                   transition-all hover:bg-red-500 hover:shadow-red-600/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900
                   active:scale-[0.98]">
        Sign In
    </button>
</form>
@endsection
