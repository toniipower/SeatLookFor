<x-layout.nav>
    <div class="max-w-7xl mx-auto py-10 px-4">
        <!-- ENCABEZADO -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $establecimiento->nombre }}</h1>
            <p class="text-lg text-gray-500">{{ $establecimiento->ubicacion }}</p>
            @if($establecimiento->tipo)
                <p class="text-sm text-gray-400 italic">{{ $establecimiento->tipo }}</p>
            @endif
        </div>

        <!-- IMAGEN -->
        @if($establecimiento->imagen)
            <div class="mb-10 flex justify-center">
                <img src="{{ $establecimiento->imagen }}" alt="Imagen del establecimiento"
                     class="rounded-lg shadow-lg w-full max-w-2xl h-64 object-cover">
            </div>
        @endif

        <!-- LEYENDA -->
        <div class="flex justify-center gap-6 text-sm text-gray-600 mb-6">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded bg-green-500 inline-block"></span> Libre
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded bg-gray-400 inline-block"></span> Ocupado
            </div>
        </div>

        <!-- MAPA DE ASIENTOS -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Mapa de Asientos</h2>

        <div class="overflow-x-auto">
            <div class="relative bg-white border rounded-xl shadow p-4 mx-auto" style="width: 1000px; height: 600px;">
                @foreach($establecimiento->asientos as $asiento)
                    <div
                        class="absolute text-[11px] font-semibold text-white flex items-center justify-center rounded-md shadow-sm"
                        style="
                            width: 40px;
                            height: 40px;
                            left: {{ $asiento->ejeX * 50 + 5 }}px;
                            top: {{ $asiento->ejeY * 50 + 5 }}px;
                            background-color: {{ $asiento->estado === 'ocupado' ? '#9ca3af' : '#22c55e' }};
                        "
                        title="Zona: {{ $asiento->zona }} | Precio: €{{ number_format($asiento->precio, 2) }}"
                    >
                        {{ $asiento->zona }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- VOLVER O ACCIONES -->
        <div class="mt-12 text-center">
            <a href="{{ route('establecimiento.listado') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-layout.nav>
