<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Encuesta Enviada! - Presupuesto Participativo 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 text-center">
        <!-- Icono de éxito -->
        <div class="mb-6">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            ¡Encuesta enviada correctamente!
        </h1>

        <p class="text-gray-600 mb-8">
            ¡GRACIAS POR AYUDARNOS A CONTRUIR EL PRESUPUESTO 2026!
            Tu opinión es muy importante para nosotros y será considerada.
        </p>

        <!-- Botones de acción -->
        <div class="space-y-4">
            <a href="{{ route('home') }}"
               class="block w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                Volver al Inicio
            </a>

            <a href="{{ route('encuesta.create') }}"
               class="block w-full bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors">
                Responder Otra Encuesta
            </a>
        </div>

        <!-- Slogan -->
        <div class="mt-8">
            <img src="{{ asset('images/slogan color.png') }}" alt="Presupuesto Participativo 2026" class="w-full max-w-sm mx-auto">
        </div>
    </div>
</body>
</html>
