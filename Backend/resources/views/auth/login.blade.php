<x-guest-layout>
    <div class="flex flex-col md:flex-row h-screen w-full">
        <!-- Left side with gradient background and PNG image -->
        <div class="flex-1 flex items-center justify-center bg-gradient-to-r from-[#540d6e] via-[#350675] to-[#540d6e] h-full w-full p-8">
            <img src="/path-to-your-image.png" alt="Login Illustration" class="max-w-xs md:max-w-sm" />
        </div>
        <!-- Right side with login form -->
        <div class="flex-1 flex items-center justify-center bg-[#F9E8D9] h-full w-full p-8">
            <div class="w-full max-w-md p-6 shadow-2xl rounded-2xl bg-white">
                <h2 class="text-3xl font-bold mb-6 text-center text-[#540d6e]">Login</h2>
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full p-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#540d6e]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full p-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#540d6e]" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#540d6e] shadow-sm focus:ring-2 focus:ring-[#540d6e]" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-between mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 mb-4 md:mb-0" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-[#540d6e] text-white rounded-xl hover:bg-[#350675]">Login</button>
                    </div>
                </form>
                <p class="text-center text-sm text-gray-600 mt-4">Don't have an account? <a href="#" class="text-[#540d6e] font-semibold">Sign up</a></p>
            </div>
        </div>
    </div>
</x-guest-layout>
