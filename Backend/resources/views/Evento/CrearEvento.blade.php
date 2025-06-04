<x-layout.nav>

<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Crear Evento</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('eventos.guardar') }}" method="POST" class="bg-white shadow-md rounded px-6 py-6 space-y-5">
        @csrf

        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label for="duracion" class="block text-sm font-medium text-gray-700">Duración (min)</label>
                <input type="number" name="duracion" id="duracion" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
        </div>

        <div>
            <label for="idEst" class="block text-sm font-medium text-gray-700">Establecimiento</label>
            <select name="idEst" id="idEst" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Seleccione un establecimiento</option>
                @foreach ($establecimientos as $est)
                    <option value="{{ $est->idEst }}">{{ $est->idEst }} - {{ $est->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div id="zonas-container">
            <label class="block text-sm font-medium text-gray-700 mb-2">Zonas y precios</label>
            <div id="zonas-content" class="space-y-4">
                <p class="text-sm text-gray-500">Seleccione un establecimiento para mostrar zonas.</p>
            </div>
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                Guardar Evento
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('idEst').addEventListener('change', function () {
        const idEst = this.value;
        const zonasContent = document.getElementById('zonas-content');
        zonasContent.innerHTML = '<p class="text-sm text-gray-500">Cargando zonas...</p>';

        fetch(`/zonas-por-establecimiento/${idEst}`)
            .then(response => response.json())
            .then(zonas => {
                zonasContent.innerHTML = '';

                if (zonas.length === 0) {
                    zonasContent.innerHTML = '<p class="text-red-500 text-sm">Este establecimiento no tiene zonas registradas.</p>';
                    return;
                }

                zonas.forEach((zona, index) => {
                    zonasContent.innerHTML += `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">${zona.nombre}</label>
                            <input type="hidden" name="zonas[]" value="${zona.idZona}">
                            <input type="number" name="precios[]" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Precio para ${zona.nombre}" required>
                        </div>
                    `;
                });
            })
            .catch(() => {
                zonasContent.innerHTML = '<p class="text-red-500 text-sm">Error al cargar las zonas.</p>';
            });
    });
</script>

</x-layout.nav>
