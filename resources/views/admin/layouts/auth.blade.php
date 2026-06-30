<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Login') — The Untold Story</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex min-h-full flex-col bg-gray-950">

    {{-- Background decoration --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 -right-32 h-96 w-96 rounded-full bg-red-600/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-red-600/5 blur-3xl"></div>
    </div>

    <main class="relative flex flex-1 flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">

        {{-- Logo / Brand --}}
        <div class="mb-8 flex flex-col items-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-600 shadow-lg shadow-red-600/30">
                <span class="text-xl font-bold text-white">US</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">The Untold Story</h1>
            <p class="mt-1 text-sm text-gray-500">Admin Panel</p>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-md rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">
            @yield('content')
        </div>

    </main>

    {{-- Footer --}}
    <footer class="relative py-6 text-center">
        <p class="text-xs text-gray-600">
            &copy; {{ date('Y') }} The Untold Story. All rights reserved.
        </p>
    </footer>

</body>
</html>
