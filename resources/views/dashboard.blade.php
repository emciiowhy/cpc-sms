<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Stats Cards --}}
    {{-- Update: grid-cols-1 (mobile) to grid-cols-2 (tablet) to grid-cols-4 (desktop) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        
        {{-- Total Students --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm transition-all sm:hover:shadow-md sm:hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Students</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</p>
            </div>
        </div>

        {{-- Events --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm transition-all sm:hover:shadow-md sm:hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Events</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['events'] }}</p>
            </div>
        </div>

        {{-- Pending Appointments --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm transition-all sm:hover:shadow-md sm:hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['appointments'] }}</p>
            </div>
        </div>

        {{-- Clinic Visits --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm transition-all sm:hover:shadow-md sm:hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Clinic</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['clinic_today'] }}</p>
            </div>
        </div>
    </div>

    {{-- Welcome Card --}}
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-4 sm:p-6 text-white shadow-lg shadow-indigo-200">
        <h2 class="text-lg sm:text-xl font-bold mb-1 break-words">
            Welcome back, {{ auth()->user()->name }}! 👋
        </h2>
        <p class="text-indigo-100 text-sm opacity-90 break-words">
            You are logged in as <span class="px-2 py-0.5 bg-white/20 rounded-md capitalize font-semibold">{{ auth()->user()->role }}</span>.
        </p>
    </div>

</x-app-layout>