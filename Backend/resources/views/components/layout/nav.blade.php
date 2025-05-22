<!DOCTYPE html>
<html lang="es"
    x-data="{
    mostrarMenu: window.innerWidth > 960,
    abiertoFicheros: false,
    abiertoGastos: false,
    abiertoOperaciones: false,
    abiertoContabilidad: false,
    abiertoClientes: false,
    toggle(que) {
        const estadoActual = this[que];
        this.abiertoFicheros = false;
        this.abiertoGastos = false;
        this.abiertoOperaciones = false;
        this.abiertoContabilidad = false;
        this.abiertoClientes = false;
        this[que] = !estadoActual;
    }
}"

    x-init="window.addEventListener('resize', () => mostrarMenu = window.innerWidth > 960)">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SeatLook</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js para manejar el estado del menú -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>

    <!-- -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
</head>

<body class="flex flex-col h-screen bg-gray-800 text-white">

    <!-- Navbar Superior -->
    <div class="flex justify-between items-center bg-purple-900 p-4">
        <div class="flex items-center space-x-4">
            <!-- Botón para mostrar/ocultar menú lateral -->
            <button @click="mostrarMenu = !mostrarMenu" class="bg-purple-900 px-3 py-1 rounded hover:bg-purple-600">
                ☰
            </button>
            <div class="font-bold text-lg">SeatLook</div>
        </div>
        <div class="flex space-x-4 items-center">
            <a href="" class="hover:underline">Inicio</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:underline text-white bg-transparent border-0 cursor-pointer">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">

        <!-- Navbar Vertical (ocultable) -->
        <div class="w-80 bg-purple-900 p-4 flex flex-col space-y-4 overflow-y-auto"
            x-show="mostrarMenu"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-x-4"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-4">

            <!-- Logo y usuario -->

            <div class="flex flex-col items-center mb-6">
                <div class="w-100 h-50 flex items-center justify-center text-center text-gray-600 overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo empresa" class="w-full h-full " />
                </div>
                <div class="mt-2 font-semibold">{{ Auth::user()->nombre_usuario ?? 'Usuario' }}</div>
            </div>

            <!-- OPCIONES DE MENÚ -->

            <a href="{{ route('establecimiento.listado') }}" class="py-2 px-4 bg-purple-600 rounded hover:bg-purple-900 text-center">Establecimientos</a>
            <a href="" class="py-2 px-4 bg-purple-600 rounded hover:bg-purple-900 text-center">Consultas</a>
            <a href="" class="py-2 px-4 bg-purple-600 rounded hover:bg-purple-900 text-center">Eventos</a>
            <a href="" class="py-2 px-4 bg-purple-600 rounded hover:bg-purple-900 text-center">Copia de seguridad</a>


            <a href="" class="py-2 px-4 bg-purple-600 rounded hover:bg-purple-900 text-center">Usuarios</a>
        </div>
        


  

       

    
        <!-- Contenido Principal -->
        <div class="flex-1 p-6 bg-white text-black overflow-y-auto">
            {{ $slot }}
        </div>

    </div>
</body>

</html>