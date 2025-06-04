<x-layout.nav>
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-10">Nuestros Establecimientos</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($establecimientos as $est)
                <a href="{{ route('establecimientos.mostrar', $est->idEst) }}" class="block group">
                    <div class="bg-white shadow-md hover:shadow-xl transition rounded-xl overflow-hidden">
                        <img src="{{ $est->imagen }}" alt="{{ $est->nombre }}" class="object-cover w-full h-48 group-hover:scale-105 transition-transform">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ $est->nombre }}</h2>
                            <p class="text-sm text-gray-500">{{ $est->ubicacion }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-10 flex justify-center">
            {{ $establecimientos->links() }}
        </div>
    </div>
</x-layout.nav>
