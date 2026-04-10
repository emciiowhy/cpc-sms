<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — SchoolMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-900 min-h-dvh flex items-center justify-center p-4 pb-safe">

<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-white rounded-2xl shadow-lg mb-4">
            <span class="text-indigo-700 font-bold text-2xl">S</span>
        </div>
        <h1 class="text-white text-2xl font-bold">SchoolMS</h1>
        <p class="text-indigo-300 text-sm mt-1">School Management System</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-gray-800 text-xl font-semibold mb-1">Create Account</h2>
        <p class="text-gray-400 text-sm mb-6">Register a new system user</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition
                           @error('name') border-red-300 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition
                           @error('email') border-red-300 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Role</label>
                <select name="role" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
                    <option value="admin">Admin</option>
                    <option value="registrar">Registrar</option>
                    <option value="guidance">Guidance</option>
                    <option value="clinic">Clinic</option>
                    <option value="sao">SAO</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition
                           @error('password') border-red-300 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-xl text-sm transition">
                Create Account
            </button>

            <p class="text-center text-sm text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Sign in</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>