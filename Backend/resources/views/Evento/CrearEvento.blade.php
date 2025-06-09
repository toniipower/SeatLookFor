<x-layout.nav>

<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Crear Evento</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('eventos.guardar') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-6 py-6 space-y-5">
        @csrf
        <input type="hidden" name="_method" value="POST">

        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                <input type="time" name="hora" id="hora" value="{{ old('hora') }}" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
        </div>

        <div>
            <label for="establecimiento_id" class="block text-sm font-medium text-gray-700">Establecimiento</label>
            <select name="establecimiento_id" id="establecimiento_id" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Seleccione un establecimiento</option>
                @foreach ($establecimientos as $est)
                    <option value="{{ $est->idEst }}" {{ old('establecimiento_id') == $est->idEst ? 'selected' : '' }}>
                        {{ $est->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- ZONAS Y PRECIOS -->
        <div id="zonas-content" class="mt-6 space-y-4">
            <!-- Aquí se cargarán las zonas dinámicamente -->
        </div>

        <!-- MAPA DE ASIENTOS -->
        <div id="mapa-asientos-container" class="mt-6 hidden">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Mapa de Asientos</h2>
            <div class="overflow-x-auto">
                <div id="mapa-asientos" class="relative bg-white border rounded-xl shadow p-4 mx-auto" style="width: 1000px; height: 600px;">
                    <!-- Asientos se insertan aquí -->
                </div>
            </div>
        </div>

        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
            <select name="estado" id="estado" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
            </select>
        </div>

        <div>
            <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen del evento</label>
            <input type="file" name="imagen" id="imagen" class="mt-1 w-full" accept="image/*">
            <p class="mt-1 text-sm text-gray-500">Formatos permitidos: JPEG, PNG, JPG, GIF. Máximo 2MB.</p>
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                Guardar Evento
            </button>
        </div>
    </form>
</div>

<!-- SCRIPT PARA ZONAS Y MAPA DE ASIENTOS -->
<script>
    document.getElementById('establecimiento_id').addEventListener('change', function () {
        const idEst = this.value;
        const zonasContent = document.getElementById('zonas-content');
        const mapaContainer = document.getElementById('mapa-asientos-container');
        const mapaAsientos = document.getElementById('mapa-asientos');

        zonasContent.innerHTML = '<p class="text-sm text-gray-500">Cargando zonas...</p>';
        mapaAsientos.innerHTML = '';
        mapaContainer.classList.add('hidden');

        if (!idEst) return;

        fetch(`/zonas-por-establecimiento/${idEst}`)
            .then(response => response.json())
            .then(zonas => {
                zonasContent.innerHTML = '';
                mapaAsientos.innerHTML = '';
                mapaContainer.classList.remove('hidden');

                if (zonas.length === 0) {
                    zonasContent.innerHTML = '<p class="text-red-500 text-sm">Este establecimiento no tiene zonas registradas.</p>';
                    return;
                }

                zonas.forEach((zona) => {
                    // Precio por zona
                    zonasContent.innerHTML += `
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">${zona.nombre} - Precio</label>
                            <input type="hidden" name="zonas[]" value="${zona.idZona}">
                            <input type="number" name="precios[]" step="0.01" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Precio para ${zona.nombre}" required>
                        </div>
                    `;

                    // Render asientos en el mapa
                    zona.asientos.forEach(asiento => {
                        const div = document.createElement('div');
                        div.className = "absolute text-[11px] font-semibold text-white flex items-center justify-center rounded-md shadow-sm";
                        div.style.width = "40px";
                        div.style.height = "40px";
                        div.style.left = `${asiento.ejeX * 50 + 5}px`;
                        div.style.top = `${asiento.ejeY * 50 + 5}px`;
                        div.style.backgroundColor = asiento.estado === 'ocupado' ? '#9ca3af' : '#22c55e';
                        div.title = `Zona: ${asiento.zona} | Precio: €${Number(asiento.precio).toFixed(2)}`;
                        div.innerText = asiento.zona;

                        mapaAsientos.appendChild(div);
                    });
                });
            })
            .catch(() => {
                zonasContent.innerHTML = '<p class="text-red-500 text-sm">Error al cargar las zonas.</p>';
                mapaAsientos.innerHTML = '';
                mapaContainer.classList.add('hidden');
            });
    });
</script>

</x-layout.nav>
