<x-layout.nav>
    <body class="bg-gray-100 min-h-screen">
        <div class="container mx-auto py-10 px-4">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-10">Nuestros Establecimientos</h1>

            <!-- Contenedor de Tarjetas -->
            <div id="establecimientos-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"></div>

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

                container.innerHTML = "";
                pagination.innerHTML = "";

                const totalItems = establecimientos.length;
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const currentItems = establecimientos.slice(start, end);

                currentItems.forEach(est => {
                    container.innerHTML += `
                        <a href="/establecimientos/${est.idEst}" class="block group">
                            <div class="bg-white shadow-md hover:shadow-xl transition rounded-xl overflow-hidden">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="${est.imagen}" alt="${est.nombre}" class="object-cover w-full h-48 group-hover:scale-105 transition-transform">
                                </div>
                                <div class="p-4">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-1">${est.nombre}</h2>
                                    <p class="text-sm text-gray-500">${est.ubicacion}</p>
                                </div>
                            </div>
                        </a>
                    `;
                });

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

            renderEstablecimientos();
        </script>
</x-layout.nav>
