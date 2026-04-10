<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Cordova Public College</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-900 min-h-dvh flex items-center justify-center p-4 pb-safe">

<div class="w-full max-w-md">

    {{-- Updated Logo Section --}}
    <div class="text-center mb-8">
        <img src="{{ asset('images/cpc-logo.png') }}" alt="CPC Logo" class="w-20 h-20 rounded-full shadow-lg mb-3 mx-auto">
        <h1 class="text-white text-2xl font-bold">Cordova Public College</h1>
        <p class="text-indigo-300 text-sm mt-1">School Management System</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-gray-800 text-xl font-semibold mb-1">Reset Password</h2>
        <p class="text-gray-400 text-sm mb-6">Enter your email and we'll send you a reset link.</p>

        {{-- Session Status (Success Message) --}}
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4 italic">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition
                           @error('email') border-red-300 @enderror"
                    placeholder="Enter your registered email">
                
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-xl text-sm transition shadow-md active:scale-95">
                Send Reset Link
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition font-medium">
                    &larr; Back to login
                </a>
            </div>
        </form>
    </div>

    <p class="text-center text-indigo-300 text-xs mt-6">
        &copy; {{ date('Y') }} Cordova Public College. All rights reserved.
    </p>
</div>

</body>
</html>