<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SchoolMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-900 min-h-dvh flex items-center justify-center p-4 pb-safe">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <img src="{{ asset('images/cpc-logo.png') }}" alt="CPC Logo" class="w-20 h-20 rounded-full shadow-lg mb-3 mx-auto">
        <h1 class="text-white text-2xl font-bold">Cordova Public College</h1>
        <p class="text-indigo-300 text-sm mt-1">School Management System</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-gray-800 text-xl font-semibold mb-1">Welcome back</h2>
        <p class="text-gray-400 text-sm mb-6">Sign in to your account to continue</p>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition
                           @error('email') border-red-300 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-medium text-gray-500">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-700">Forgot password?</a>
                    @endif
                </div>
                <input type="password" name="password" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition
                           @error('password') border-red-300 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-400">
                <label for="remember" class="text-sm text-gray-500">Remember me</label>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-xl text-sm transition">
                Sign In
            </button>
        </form>
    </div>

    <p class="text-center text-indigo-300 text-xs mt-6">
        &copy; {{ date('Y') }} SchoolMS. All rights reserved.
    </p>
</div>

</body>
</html>