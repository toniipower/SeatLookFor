<x-layout.nav>
    
    
    <body class="bg-gray-100">
        <div class="container mx-auto py-10 px-4">
            
            <!-- Contenedor de Tarjetas -->
            <div id="establecimientos-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8"></div>
            
            <!-- Paginación -->
            <div class="mt-10 flex justify-center" id="pagination"></div>
        </div>
        
        <script>
    const establecimientos = @json($establecimientos);
    const itemsPerPage = 6;
    let currentPage = 1;

    function renderEstablecimientos() {
        const container = document.getElementById("establecimientos-container");
        const pagination = document.getElementById("pagination");
        
        // Limpiar el contenedor
        container.innerHTML = "";
        pagination.innerHTML = "";
        
        // Calcular las páginas
        const totalItems = establecimientos.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const currentItems = establecimientos.slice(start, end);
        
        // Renderizar los establecimientos
        currentItems.forEach(est => {
            container.innerHTML += `
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
                <img src="${est.imagen}" alt="${est.nombre}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">${est.nombre}</h2>
                    <p class="text-gray-600">${est.ubicacion}</p>
                    </div>
                    </div>
                    `;
                });

        // Renderizar los botones de paginación
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <button 
                    class="mx-1 px-4 py-2 rounded-full ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-blue-500 text-white hover:bg-blue-600'}" 
                    onclick="goToPage(${i})">
                    ${i}
                    </button>
                    `;
                }
            }
            
            function goToPage(page) {
                currentPage = page;
                renderEstablecimientos();
                window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Renderizar la primera página al cargar
    renderEstablecimientos();
    </script>

</x-layout.nav>