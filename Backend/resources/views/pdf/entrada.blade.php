<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas - {{ $usuario->nombre }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { print-color-adjust: exact; }
            .no-print { display: none; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Header -->
        <div class="text-center mb-8 border-b-2 border-gray-200 pb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                🎫 ENTRADAS CONFIRMADAS
            </h1>
            <div class="text-lg text-gray-600">
                <p><strong>Cliente:</strong> {{ $usuario->nombre }}</p>
                <p class="text-sm mt-1">Fecha de generación: {{ date('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Entradas Grid -->
        <div class="space-y-6">
            @foreach($reservas as $index => $reserva)
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 {{ $index > 0 ? 'page-break' : '' }}">
                <!-- Ticket Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800 mb-1">ENTRADA #{{ str_pad($reserva->idEve, 4, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-sm text-gray-600">Evento ID: {{ $reserva->idEve }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-black text-white px-3 py-1 rounded text-sm font-mono">
                            ASIENTO {{ $reserva->idAsi }}
                        </div>
                    </div>
                </div>

                <!-- Ticket Body -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <!-- Información del Evento -->
                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wide">Detalles del Evento</h3>
                        <div class="text-sm space-y-1">
                            <p><span class="font-medium">Evento:</span> #{{ $reserva->idEve }}</p>
                            <p><span class="font-medium">Asiento:</span> {{ $reserva->idAsi }}</p>
                        </div>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wide">Fecha y Hora</h3>
                        <div class="text-sm">
                            <p class="font-mono text-lg">{{ $reserva->fechaReserva }}</p>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wide">Precio</h3>
                        <div class="text-2xl font-bold text-green-600">
                            €{{ number_format($reserva->precio, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Código de Barras Simulado -->
                <div class="border-t-2 border-dashed border-gray-300 pt-4">
                    <div class="flex justify-between items-center">
                        <div class="font-mono text-xs bg-white border px-2 py-1 rounded">
                            {{ strtoupper(md5($reserva->idEve . $reserva->idAsi)) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Válido para una sola entrada
                        </div>
                    </div>
                    <!-- Barcode visual -->
                    <div class="mt-2 flex space-x-1">
                        @for($i = 0; $i < 40; $i++)
                            <div class="w-1 bg-black" style="height: {{ rand(10, 30) }}px;"></div>
                        @endfor
                    </div>
                </div>

                <!-- Instrucciones -->
                <div class="mt-4 text-xs text-gray-500 bg-yellow-50 p-3 rounded border border-yellow-200">
                    <p><strong>Instrucciones importantes:</strong></p>
                    <ul class="mt-1 list-disc list-inside space-y-1">
                        <li>Presente esta entrada en formato digital o impreso en la entrada del evento</li>
                        <li>Llegue al menos 30 minutos antes del inicio del evento</li>
                        <li>Esta entrada no es reembolsable ni transferible</li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Resumen -->
        <div class="mt-8 border-t-2 border-gray-200 pt-6">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Resumen de la Compra</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p><strong>Total de entradas:</strong> {{ count($reservas) }}</p>
                        <p><strong>Cliente:</strong> {{ $usuario->nombre }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-green-600">
                            <strong>Total: €{{ number_format(array_sum(array_column($reservas, 'precio')), 2) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
            <p>Este documento es válido como comprobante de compra de entradas.</p>
            <p>Para consultas, contacte con nuestro servicio de atención al cliente.</p>
            <p class="mt-2">Generado el {{ date('d/m/Y') }} a las {{ date('H:i') }}</p>
        </div>
    </div>
</body>
</html>