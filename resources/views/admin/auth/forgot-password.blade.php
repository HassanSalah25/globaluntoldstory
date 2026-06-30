@extends('admin.layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="mb-6 text-center">
    <h2 class="text-xl font-bold text-white">Reset your password</h2>
    <p class="mt-1 text-sm text-gray-500">
        Enter your email and we'll send you a reset link.
    </p>
</div>

@if(session('status'))
<div class="mb-5 rounded-lg border border-green-800 bg-green-950/60 p-4">
    <div class="flex gap-2">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-green-400">{{ session('status') }}</p>
    </div>
</div>
@endif

@if($errors->any())
<div class="mb-5 rounded-lg border border-red-800 bg-red-950/60 p-4">
    <ul class="space-y-1 text-sm text-red-400">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.password.email') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">
            Email address
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               autocomplete="email"
               class="w-full rounded-xl border bg-gray-800 border-gray-700 px-4 py-3 text-sm text-white placeholder-gray-500
                      focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors
                      @error('email') border-red-500 @enderror"
               placeholder="admin@example.com">
        @error('email')
        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20
                   transition-all hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900
                   active:scale-[0.98]">
        Send Reset Link
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-500">
    <a href="{{ route('admin.login') }}" class="text-red-500 hover:text-red-400 transition-colors font-medium">
        &larr; Back to Sign In
    </a>
</p>
@endsection
