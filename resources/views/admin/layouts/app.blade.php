<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — The Untold Story Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        #app-preloader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            background: #111827;
            transition: opacity .5s ease, visibility .5s ease;
        }
        #app-preloader.is-hidden {
            opacity: 0;
            visibility: hidden;
        }
        .app-preloader__logo {
            display: flex;
            height: 4rem;
            width: 4rem;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: #dc2626;
            box-shadow: 0 10px 25px -5px rgba(220, 38, 38, .5);
            animation: app-preloader-pulse 1.4s ease-in-out infinite;
        }
        .app-preloader__logo span {
            font-size: 1.375rem;
            font-weight: 700;
            color: #fff;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
        .app-preloader__title {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            font-family: ui-sans-serif, system-ui, sans-serif;
            letter-spacing: -.01em;
        }
        .app-preloader__spinner {
            height: 1.75rem;
            width: 1.75rem;
            border-radius: 9999px;
            border: 3px solid rgba(255, 255, 255, .15);
            border-top-color: #dc2626;
            animation: app-preloader-spin .8s linear infinite;
        }
        @keyframes app-preloader-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(1.08); opacity: .85; }
        }
        @keyframes app-preloader-spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="h-full"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          mobileOpen: false
      }"
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    {{-- ── Preloader ───────────────────────────────── --}}
    <div id="app-preloader" role="status" aria-label="Loading">
        <div class="app-preloader__logo">
            <span>US</span>
        </div>
        <p class="app-preloader__title">The Untold Story</p>
        <div class="app-preloader__spinner"></div>
    </div>

    {{-- ── Mobile overlay ─────────────────────────── --}}
    <div x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

    {{-- ── Sidebar ─────────────────────────────────── --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-gray-900 transform transition-transform duration-300 ease-in-out"
           :class="mobileOpen
               ? 'translate-x-0'
               : '-translate-x-full ' + (sidebarOpen ? 'lg:translate-x-0' : 'lg:-translate-x-full')">

        {{-- Logo --}}
        <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-800 px-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-600">
                    <span class="text-sm font-bold text-white">US</span>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-white leading-tight">The Untold Story</p>
                    <p class="text-xs text-red-500">Admin Panel</p>
                </div>
            </a>
            <button @click="mobileOpen = false" class="rounded-md p-1 text-gray-400 hover:text-white lg:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm10 0a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V7zM3 17a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2h-4a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            {{-- Content --}}
            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Content</p>

            @php
            $navContent = [
                ['route' => 'admin.hero-slides.index', 'pattern' => 'admin.hero-slides.*', 'label' => 'Hero Slides', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['route' => 'admin.services.index', 'pattern' => 'admin.services.*', 'label' => 'Services', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['route' => 'admin.portfolio.index', 'pattern' => 'admin.portfolio.*', 'label' => 'Portfolio', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['route' => 'admin.blog.index', 'pattern' => 'admin.blog.*', 'label' => 'Blog Posts', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route' => 'admin.faqs.index', 'pattern' => 'admin.faqs.*', 'label' => 'FAQs', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            $navAbout = [
                ['route' => 'admin.team.index', 'pattern' => 'admin.team.*', 'label' => 'Team', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['route' => 'admin.timeline.index', 'pattern' => 'admin.timeline.*', 'label' => 'Timeline', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['route' => 'admin.skill-bars.index', 'pattern' => 'admin.skill-bars.*', 'label' => 'Skills', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'admin.value-items.index', 'pattern' => 'admin.value-items.*', 'label' => 'Values', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                ['route' => 'admin.expertise-videos.index', 'pattern' => 'admin.expertise-videos.*', 'label' => 'Expertise Videos', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                ['route' => 'admin.feature-highlights.index', 'pattern' => 'admin.feature-highlights.*', 'label' => 'Feature Highlights', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['route' => 'admin.process-steps.index', 'pattern' => 'admin.process-steps.*', 'label' => 'Process Steps', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
            ];
            $navConfig = [
                ['route' => 'admin.stats.index', 'pattern' => 'admin.stats.*', 'label' => 'Stats', 'icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
                ['route' => 'admin.testimonials.index', 'pattern' => 'admin.testimonials.*', 'label' => 'Testimonials', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                ['route' => 'admin.awards.index', 'pattern' => 'admin.awards.*', 'label' => 'Awards', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                ['route' => 'admin.client-logos.index', 'pattern' => 'admin.client-logos.*', 'label' => 'Client Logos', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['route' => 'admin.offices.index', 'pattern' => 'admin.offices.*', 'label' => 'Offices', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ];
            $navNav = [
                ['route' => 'admin.menus.index', 'pattern' => 'admin.menus.*', 'label' => 'Menus', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                ['route' => 'admin.pages.index', 'pattern' => 'admin.pages.*', 'label' => 'Pages', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['route' => 'admin.categories.index', 'pattern' => 'admin.categories.*', 'label' => 'Categories', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
            ];
            $navSettings = [
                ['route' => 'admin.settings.index', 'pattern' => 'admin.settings.*', 'label' => 'Site Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.seo.index', 'pattern' => 'admin.seo.*', 'label' => 'SEO Management', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
            ];
            $navLeads = [
                ['route' => 'admin.contact-requests.index', 'pattern' => 'admin.contact-requests.*', 'label' => 'Contact Requests', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['route' => 'admin.leads.index', 'pattern' => 'admin.leads.*', 'label' => 'Leads', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.newsletter.index', 'pattern' => 'admin.newsletter.*', 'label' => 'Newsletter', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
            ];
            @endphp

            @foreach($navContent as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs($item['pattern']) ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">About</p>
            @foreach($navAbout as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs($item['pattern']) ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Media & Files</p>
            <a href="{{ route('admin.media.index') }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs('admin.media.*') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Media Library
            </a>

            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Site Config</p>
            @foreach($navConfig as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs($item['pattern']) ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Navigation</p>
            @foreach($navNav as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs($item['pattern']) ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Settings</p>
            @foreach($navSettings as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs($item['pattern']) ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <p class="mt-5 mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Leads & Forms</p>
            @foreach($navLeads as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
               {{ request()->routeIs($item['pattern']) ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- User Info --}}
        <div class="shrink-0 border-t border-gray-800 p-3" x-data="{ userOpen: false }">
            <button @click="userOpen = !userOpen"
                    class="flex w-full items-center gap-3 rounded-lg px-2 py-2 text-left transition-colors hover:bg-gray-800">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(strstr(auth()->user()->name ?? ' ', ' '), 1, 1)) ?: '' }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="truncate text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <svg class="h-4 w-4 shrink-0 text-gray-500 transition-transform" :class="userOpen ? '-rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="userOpen" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="mt-1.5 space-y-0.5">
                @if(auth()->user()->roles->first())
                <div class="px-2 py-1">
                    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-red-900/60 text-red-300 ring-1 ring-red-800">
                        {{ ucfirst(auth()->user()->roles->first()->name) }}
                    </span>
                </div>
                @endif
                <a href="{{ route('admin.profile.edit') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-400 transition-colors hover:bg-gray-800 hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    My Profile
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-400 transition-colors hover:bg-gray-800 hover:text-red-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ────────────────────────────── --}}
    <div class="flex min-h-screen flex-col transition-all duration-300 ease-in-out"
         :class="sidebarOpen ? 'lg:pl-64' : ''">

        {{-- Top Bar --}}
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 lg:px-6 shadow-sm">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 lg:hidden">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Desktop toggle --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="hidden rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 lg:flex">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                    <div class="flex items-center gap-1 text-xs text-gray-400">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Home</a>
                        <span>/</span>
                        @yield('breadcrumb')
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden items-center gap-2 sm:flex">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error') || session('warning') || session('status'))
        <div class="px-4 pt-4 lg:px-6 space-y-2">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="flex-1 text-sm font-medium text-green-800">{{ session('success') }}</p>
                <button @click="show = false" class="text-green-400 hover:text-green-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 x-init="setTimeout(() => show = false, 7000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="flex-1 text-sm font-medium text-red-800">{{ session('error') }}</p>
                <button @click="show = false" class="text-red-400 hover:text-red-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
            @if(session('warning'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 x-init="setTimeout(() => show = false, 6000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="flex items-start gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4 shadow-sm">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="flex-1 text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                <button @click="show = false" class="text-yellow-400 hover:text-yellow-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
            @if(session('status'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 shadow-sm">
                <p class="text-sm font-medium text-blue-800">{{ session('status') }}</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 px-4 py-6 lg:px-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="shrink-0 border-t border-gray-200 px-4 py-4 lg:px-6">
            <p class="text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} The Untold Story &mdash; Admin Panel
            </p>
        </footer>
    </div>

    @include('admin.components.media-picker-modal')

    <script>
        (function () {
            var preloader = document.getElementById('app-preloader');
            if (!preloader) return;
            var hide = function () { preloader.classList.add('is-hidden'); };
            if (document.readyState === 'complete') {
                hide();
            } else {
                window.addEventListener('load', hide);
                // Safety fallback so the preloader never gets stuck.
                setTimeout(hide, 4000);
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
