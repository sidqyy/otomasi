<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
        <p class="text-sm text-gray-500 mt-1">Please enter your details to sign in.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Username</label>
            <input id="name" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" placeholder="Enter your username" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium hover:text-gray-900 transition-colors">Keep me logged in</span>
            </label>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-emerald-500/30">
                Sign In to Dashboard
            </button>
        </div>
    </form>
</x-guest-layout>
