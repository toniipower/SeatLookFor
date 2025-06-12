<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-[#111827]">
        <div class="w-full max-w-md p-8 bg-[#1F2937] text-white rounded-2xl shadow-xl border border-indigo-500">
            <h1 class="text-2xl font-bold text-center mb-6 text-indigo-400">Panel de Administración</h1>
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" required autofocus
                        class="w-full mt-1 p-3 rounded-lg bg-gray-800 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        placeholder="admin@ejemplo.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-sm" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium">Contraseña</label>
                    <input id="password" type="password" name="password" required
                        class="w-full mt-1 p-3 rounded-lg bg-gray-800 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        placeholder="Tu contraseña">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-sm" />
                </div>

           

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-300">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

         
        </div>
    </div>
</x-guest-layout>
