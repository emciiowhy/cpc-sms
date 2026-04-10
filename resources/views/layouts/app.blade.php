<!DOCTYPE html>
<html lang="en"
      x-data="{ mobileMenuOpen: false }"
      x-init="$watch('mobileMenuOpen', open => { document.documentElement.classList.toggle('overflow-hidden', open); })"
      @keydown.escape.window="mobileMenuOpen = false">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#312e81">
    <title>{{ config('app.name') }} - Cordova Public College</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-dvh min-h-0 overflow-hidden">

    {{-- MOBILE SIDEBAR OVERLAY --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden overscroll-none touch-manipulation"
         @click="mobileMenuOpen = false"
         x-cloak></div>

    {{-- SIDEBAR --}}
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-[min(18rem,88vw)] max-w-[288px] bg-indigo-900 text-white flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 pt-safe pb-safe shadow-xl lg:shadow-none">

        {{-- Logo Section --}}
        <div class="flex items-center justify-between gap-2 px-4 py-4 border-b border-indigo-800">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/cpc-logo.png') }}" alt="CPC Logo" class="w-10 h-10 rounded-full flex-shrink-0" width="40" height="40" loading="eager" decoding="async">
                <div class="min-w-0">
                    <p class="font-semibold text-xs leading-tight truncate">Cordova Public College</p>
                    <p class="text-indigo-400 text-xs uppercase tracking-tighter">Management System</p>
                </div>
            </div>
            <button type="button" @click="mobileMenuOpen = false" class="touch-target shrink-0 lg:hidden text-indigo-200 rounded-lg hover:bg-indigo-800/50" aria-label="Close menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- User Info (desktop) --}}
        <div class="px-6 py-4 border-b border-indigo-800 hidden lg:block">
            <p class="text-xs text-indigo-300 uppercase tracking-widest mb-1">Logged in as</p>
            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
            <span class="inline-block mt-1 text-xs bg-indigo-700 text-indigo-200 px-2 py-0.5 rounded-full capitalize">
                {{ auth()->user()->role }}
            </span>
        </div>

        {{-- User strip (mobile) --}}
        <div class="px-4 py-3 border-b border-indigo-800 lg:hidden">
            <p class="text-xs text-indigo-300 uppercase tracking-widest mb-0.5">Signed in</p>
            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
            <span class="inline-block mt-1 text-xs bg-indigo-700 text-indigo-200 px-2 py-0.5 rounded-full capitalize">{{ auth()->user()->role }}</span>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto scroll-touch min-h-0 overscroll-y-contain">
            <a href="{{ route('dashboard') }}"
               @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            @if(in_array(auth()->user()->role, ['admin', 'sao']))
                <p class="px-3 pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-widest">SAO</p>
                <a href="{{ route('events.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('events.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Events
                </a>
                <a href="{{ route('organizations.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('organizations.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Organizations
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'registrar']))
                <p class="px-3 pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-widest">Registrar</p>
                <a href="{{ route('enrollment.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('enrollment.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Enrollments
                </a>
                <a href="{{ route('grades.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('grades.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Grades
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'guidance']))
                <p class="px-3 pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-widest">Guidance</p>
                <a href="{{ route('appointments.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('appointments.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Appointments
                </a>
                <a href="{{ route('counseling.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('counseling.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Counseling
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'clinic']))
                <p class="px-3 pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-widest">Clinic</p>
                <a href="{{ route('clinic.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('clinic.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Clinic Visits
                </a>
                <a href="{{ route('medical-records.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('medical-records.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Medical Records
                </a>
            @endif

            <p class="px-3 pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-widest">General</p>
            <a href="{{ route('announcements.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('announcements.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V9.24a1 1 0 001.447.894l4.553-2.276a1 1 0 01.894 1.79l-4.553 2.277a1 1 0 00-.553.894V17a1 1 0 01-1 1H6a1 1 0 01-1-1v-4a1 1 0 00-.553-.894L.553 9.83a1 1 0 01.894-1.79l4.553 2.276A1 1 0 005 9.24V5.883A1 1 0 015.447 5h9.106a1 1 0 01.447.894z"/></svg>
                Announcements
            </a>
            <a href="{{ route('profile.edit') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition touch-manipulation {{ request()->routeIs('profile.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-indigo-800 pb-safe">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 px-3 py-3 rounded-lg text-sm text-indigo-200 hover:bg-indigo-800 transition touch-manipulation">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

        <header class="bg-white border-b border-gray-200 px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 flex items-center justify-between gap-2 shadow-sm pt-safe">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0 flex-1">
                <button type="button" @click="mobileMenuOpen = true" class="touch-target shrink-0 text-gray-500 hover:text-indigo-600 rounded-lg lg:hidden" aria-label="Open menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-gray-800 truncate min-w-0">{{ $header ?? 'Dashboard' }}</h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <span class="hidden sm:inline text-xs text-gray-500 font-medium tabular-nums">{{ now()->format('M j, Y') }}</span>
                <a href="{{ route('profile.edit') }}" class="hidden sm:flex items-center justify-center w-9 h-9 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold capitalize hover:bg-indigo-200 transition" title="Profile">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </a>
                <a href="{{ route('profile.edit') }}" class="sm:hidden touch-target text-indigo-600 rounded-lg" aria-label="Profile">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
            </div>
        </header>

        <div class="px-3 sm:px-4 lg:px-6 pt-3 sm:pt-4">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-r-lg text-sm mb-4 flex items-start justify-between gap-2 shadow-sm">
                    <span class="min-w-0">{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-green-600 font-bold shrink-0 touch-target rounded" aria-label="Dismiss">×</button>
                </div>
            @endif
        </div>

        <main class="flex-1 overflow-y-auto scroll-touch min-h-0 px-3 sm:px-4 lg:px-6 py-3 sm:py-4 pb-safe bg-gray-50 overscroll-y-contain">
            <div class="max-w-7xl mx-auto w-full min-w-0">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

</body>
</html>
