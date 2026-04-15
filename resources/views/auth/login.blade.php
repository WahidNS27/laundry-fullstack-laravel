<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Laundry</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-400 to-indigo-600 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-600">LaundryApp</h1>
            <p class="text-gray-500 text-sm">Masuk untuk mengelola laundry Anda</p>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password"
                    class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember -->
            <div class="flex items-center justify-between mt-4">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}"
                   class="text-sm text-indigo-500 hover:underline">
                    Lupa Password?
                </a>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold transition">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} LaundryApp
        </p>

    </div>

</body>
</html>