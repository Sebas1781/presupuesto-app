<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Presupuesto Participativo 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-g_SwMSxKdoeYDhbXPdgC6VFBSnf3yJo&libraries=places&callback=initMap" defer></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #fce4e6 100%);
            min-height: 100vh;
        }

        /* Animaciones de entrada */
        .slide-in-up {
            transform: translateY(30px);
            opacity: 0;
            animation: slideInUp 0.6s ease-out forwards;
        }

        .slide-in-up-delay-1 { animation-delay: 0.1s; }
        .slide-in-up-delay-2 { animation-delay: 0.2s; }
        .slide-in-up-delay-3 { animation-delay: 0.3s; }
        .slide-in-up-delay-4 { animation-delay: 0.4s; }

        @keyframes slideInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Efectos de formulario */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 16px;
        }

        .form-input:focus {
            border-color: #8B1538;
            box-shadow: 0 0 0 4px rgba(139, 21, 56, 0.1);
            outline: none;
            transform: translateY(-2px);
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            transition: color 0.3s ease;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Progress bar */
        .progress-container {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #8B1538 0%, #C8102E 100%);
            border-radius: 4px;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Botones mejorados con colores Pantone */
        .btn-primary {
            background: linear-gradient(135deg, #8B1538 0%, #C8102E 100%);
            color: white;
            padding: 16px 32px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(139, 21, 56, 0.4);
        }

        /* Cards para propuestas */
        .propuesta-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 1.5rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
        }

        .propuesta-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #8B1538 0%, #C8102E 100%);
            border-radius: 16px 16px 0 0;
        }

        .propuesta-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-color: #8B1538;
        }

        /* Rating stars */
        /* Estilos para mapas de Google */
        .map-container {
            width: 100%;
            height: 256px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #f87171;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .map-container > div {
            width: 100% !important;
            height: 100% !important;
        }

        /* Asegurar que los mapas de Google se muestren correctamente */
        [id*="map-"] {
            width: 100%;
            height: 256px;
            background-color: #f3f4f6;
        }

        [id*="map-"]:empty::before {
            content: "Cargando mapa...";
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6b7280;
            font-size: 14px;
        }

        /* Forzar visibilidad de botones rojos */
        .bg-red-600 {
            background-color: #dc2626 !important;
        }

        .bg-red-700 {
            background-color: #b91c1c !important;
        }

        .hover\:bg-red-700:hover {
            background-color: #b91c1c !important;
        }

        .hover\:bg-red-800:hover {
            background-color: #991b1b !important;
        }

        button[class*="bg-red"] {
            color: white !important;
            font-weight: bold !important;
        }

        .rating-star {
            font-size: 2rem;
            color: #d1d5db;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-star.active {
            color: #fbbf24;
            transform: scale(1.1);
        }

        .rating-star:hover {
            color: #fbbf24;
            transform: scale(1.2);
        }

        /* Header mejorado con colores Pantone */
        .header-gradient {
            background: linear-gradient(135deg, #8B1538 0%, #C8102E 100%);
            position: relative;
            overflow: hidden;
        }

        .header-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }

        /* Efectos de hover para inputs */
        .form-input:hover {
            border-color: #C8102E;
        }

        /* Animación de carga */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body x-data="encuestaForm()" class="antialiased">
    <!-- Header mejorado -->
    <header class="header-gradient text-white shadow-xl">
        <div class="max-w-6xl mx-auto px-6 py-6 relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 slide-in-up">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-poll text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Encuesta Participativa 2026</h1>
                        <p class="text-white/80 text-sm">Tu voz construye el futuro</p>
                    </div>
                </div>
                <a href="{{ route('home') }}"
                   class="flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-full hover:bg-white/20 transition-all slide-in-up slide-in-up-delay-1">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al Inicio</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-600">Progreso del formulario</span>
                <span class="text-sm font-medium text-gray-600" x-text="Math.round(progress) + '%'">0%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" :style="`width: ${progress}%`"></div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-8">
        @if ($errors->any())
            <div class="form-section slide-in-up mb-6 border-l-4 border-red-500 bg-red-50">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-4"></i>
                    <div>
                        <h4 class="text-red-800 font-semibold mb-2">Hay algunos errores en el formulario:</h4>
                        <ul class="text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center">
                                    <i class="fas fa-times-circle mr-2 text-sm"></i>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('encuesta.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Sección 1: Datos Sociodemográficos -->
            <div class="form-section slide-in-up" x-intersect="updateProgress(25)">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-800 to-red-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Datos Sociodemográficos</h3>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Colonia -->
                    <div class="form-group slide-in-up slide-in-up-delay-1">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt mr-2 text-red-800"></i>
                            Colonia / Comunidad *
                        </label>
                        <select name="colonia_id" class="form-input" x-on:change="loadObras()" required>
                            <option value="">Selecciona tu colonia...</option>
                            @foreach($colonias as $colonia)
                                <option value="{{ $colonia->id }}" {{ old('colonia_id') == $colonia->id ? 'selected' : '' }}>
                                    {{ $colonia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Género -->
                    <div class="form-group slide-in-up slide-in-up-delay-2">
                        <label class="form-label">
                            <i class="fas fa-venus-mars mr-2 text-red-800"></i>
                            Género *
                        </label>
                        <select name="genero" class="form-input" required>
                            <option value="">Selecciona...</option>
                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <!-- Edad -->
                    <div class="form-group slide-in-up slide-in-up-delay-3">
                        <label class="form-label">
                            <i class="fas fa-birthday-cake mr-2 text-red-800"></i>
                            Edad *
                        </label>
                        <input type="number" name="edad" class="form-input" min="18" max="100"
                               value="{{ old('edad') }}" placeholder="Ej: 30" required>
                    </div>

                    <!-- Nivel Educativo -->
                    <div class="form-group slide-in-up slide-in-up-delay-4">
                        <label class="form-label">
                            <i class="fas fa-graduation-cap mr-2 text-red-800"></i>
                            Nivel Educativo *
                        </label>
                        <select name="nivel_educativo" class="form-input" required>
                            <option value="">Selecciona...</option>
                            <option value="Sin estudios" {{ old('nivel_educativo') == 'Sin estudios' ? 'selected' : '' }}>Sin estudios</option>
                            <option value="Primaria" {{ old('nivel_educativo') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                            <option value="Secundaria" {{ old('nivel_educativo') == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                            <option value="Preparatoria" {{ old('nivel_educativo') == 'Preparatoria' ? 'selected' : '' }}>Preparatoria</option>
                            <option value="Universidad" {{ old('nivel_educativo') == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                            <option value="Posgrado" {{ old('nivel_educativo') == 'Posgrado' ? 'selected' : '' }}>Posgrado</option>
                        </select>
                    </div>

                    <!-- Estado Civil -->
                    <div class="form-group slide-in-up slide-in-up-delay-1">
                        <label class="form-label">
                            <i class="fas fa-heart mr-2 text-red-800"></i>
                            Estado Civil *
                        </label>
                        <select name="estado_civil" class="form-input" required>
                            <option value="">Selecciona...</option>
                            <option value="Soltero(a)" {{ old('estado_civil') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                            <option value="Casado(a)" {{ old('estado_civil') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                            <option value="Divorciado(a)" {{ old('estado_civil') == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                            <option value="Viudo(a)" {{ old('estado_civil') == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
                            <option value="Unión libre" {{ old('estado_civil') == 'Unión libre' ? 'selected' : '' }}>Unión libre</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Calificación de Obras (Solo si hay obras disponibles) -->
            <div class="form-section slide-in-up" x-intersect="updateProgress(50)" x-show="selectedColonia && obras.length > 0">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-700 to-red-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Califica las Obras Públicas</h3>
                </div>

                <p class="text-gray-600 mb-6 bg-red-50 p-4 rounded-lg border-l-4 border-red-400">
                    <i class="fas fa-info-circle mr-2 text-red-500"></i>
                    Califica del 1 al 5 el estado actual de estas obras en tu colonia (1 = Muy malo, 5 = Excelente)
                </p>

                <div class="grid md:grid-cols-2 gap-4">
                    <template x-for="obra in obras" :key="obra.id">
                        <div class="propuesta-card slide-in-up">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-semibold text-gray-800" x-text="obra.nombre"></span>
                                <i class="fas fa-hammer text-red-500"></i>
                            </div>

                            <div class="flex space-x-2">
                                <template x-for="i in 5" :key="i">
                                    <button type="button"
                                            class="rating-star"
                                            :class="{ 'active': getObrasCalificadas()[obra.id] >= i }"
                                            @click="setObraCalificacion(obra.id, i)">
                                        ★
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" :name="'obras_calificadas[' + obra.id + ']'" :value="getObrasCalificadas()[obra.id] || ''">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Mensaje para colonias sin obras disponibles -->
            <div class="form-section slide-in-up" x-show="selectedColonia && obras.length === 0" x-intersect="updateProgress(50)">
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Tu colonia está en desarrollo</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Actualmente no hay obras públicas disponibles para evaluar en <span x-text="selectedColonia ? selectedColonia.nombre : ''"></span>.
                        ¡Pero puedes seguir participando con tus propuestas!
                    </p>
                </div>
            </div>

            <!-- Sección 3: Propuestas -->
            <div class="form-section slide-in-up" x-intersect="updateProgress(75)">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-yellow-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-lightbulb text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Tus Propuestas de Mejora</h3>
                </div>

                <p class="text-gray-600 mb-6 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                    Comparte hasta 3 propuestas para mejorar tu comunidad. ¡Tus ideas pueden convertirse en realidad!
                </p>

                <div x-data="{ propuestas: [{}], maxPropuestas: 3 }">
                    <template x-for="(propuesta, index) in propuestas" :key="index">
                        <div class="propuesta-card mb-6 slide-in-up" :style="`animation-delay: ${index * 0.1}s`">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-800" x-text="`Propuesta ${index + 1}`"></h4>
                                <button type="button"
                                        x-show="propuestas.length > 1"
                                        @click="propuestas.splice(index, 1)"
                                        class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label">Tipo de Propuesta *</label>
                                    <select :name="`propuestas[${index}][tipo_propuesta]`" class="form-input" required>
                                        <option value="">Selecciona el tipo...</option>
                                        <option value="Infraestructura">Infraestructura</option>
                                        <option value="Servicios">Servicios Públicos</option>
                                        <option value="Seguridad">Seguridad</option>
                                        <option value="Medio Ambiente">Medio Ambiente</option>
                                        <option value="Cultura y Deporte">Cultura y Deporte</option>
                                        <option value="Educación">Educación</option>
                                        <option value="Salud">Salud</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nivel de Prioridad *</label>
                                    <select :name="`propuestas[${index}][nivel_prioridad]`" class="form-input" required>
                                        <option value="">Selecciona prioridad...</option>
                                        <option value="Urgente">Urgente</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt mr-2 text-red-800"></i>
                                        Ubicación Específica
                                    </label>
                                    <input type="text" :name="`propuestas[${index}][ubicacion]`"
                                           class="form-input location-input"
                                           :id="`location-input-${index}`"
                                           placeholder="Busca o selecciona la ubicación en el mapa"
                                           @click="showMap(index, 'propuesta')">
                                    <div class="flex gap-2 mt-2">
                                        <button type="button"
                                                @click="toggleMap(index, 'propuesta')"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl text-sm font-bold flex-1 border-2 border-red-700">
                                            <i class="fas fa-map mr-1"></i>
                                            <span x-text="mapVisible[`propuesta-${index}`] ? 'Ocultar Mapa' : 'Mostrar Mapa'"></span>
                                        </button>
                                        <button type="button"
                                                @click="getCurrentLocation(index, 'propuesta')"
                                                class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl text-sm font-bold border-2 border-red-800">
                                            <i class="fas fa-location-arrow mr-1"></i>
                                            Obtener Ubicación
                                        </button>
                                    </div>
                                    <div :id="`map-propuesta-${index}`" class="mt-2 map-container" x-show="mapVisible[`propuesta-${index}`]" x-transition></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Personas Beneficiadas *</label>
                                    <input type="number" :name="`propuestas[${index}][personas_beneficiadas]`"
                                           class="form-input" min="1" placeholder="Ej: 150" required>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Descripción Breve de la Propuesta *</label>
                                <textarea :name="`propuestas[${index}][descripcion_breve]`"
                                          class="form-input" rows="3"
                                          placeholder="Describe tu propuesta en detalle..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-camera mr-2 text-red-800"></i>
                                    Fotografía de Apoyo (Opcional)
                                </label>
                                <input type="file" :name="`propuestas[${index}][fotografia]`"
                                       class="form-input" accept="image/*">
                                <p class="text-sm text-gray-500 mt-1">Sube una foto que ayude a explicar tu propuesta</p>
                            </div>
                        </div>
                    </template>

                    <div class="text-center" x-show="propuestas.length < maxPropuestas">
                        <button type="button"
                                @click="propuestas.push({})"
                                class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl font-bold text-lg border-2 border-red-700 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Otra Propuesta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Reportes Anónimos -->
            <div class="form-section slide-in-up" x-intersect="updateProgress(90)">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-600 to-orange-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Reportes Anónimos (Opcional)</h3>
                </div>

                <div class="form-group mb-6">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="desea_reporte" value="1"
                               class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                               x-model="deseaReporte">
                        <span class="text-gray-700 font-medium">Sí, deseo hacer un reporte anónimo</span>
                    </label>
                </div>

                <div x-show="deseaReporte" x-data="{ reportes: [{}] }" x-transition>
                    <p class="text-gray-600 mb-6 bg-orange-50 p-4 rounded-lg border-l-4 border-orange-400">
                        <i class="fas fa-shield-alt mr-2 text-orange-500"></i>
                        Tu reporte será completamente anónimo. Puedes denunciar problemas o irregularidades.
                    </p>

                    <template x-for="(reporte, index) in reportes" :key="index">
                        <div class="propuesta-card mb-6 slide-in-up" :style="`animation-delay: ${index * 0.1}s`">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-800" x-text="`Reporte ${index + 1}`"></h4>
                                <button type="button"
                                        x-show="reportes.length > 1"
                                        @click="reportes.splice(index, 1)"
                                        class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Tipo de Reporte *</label>
                                <select :name="`reportes[${index}][tipo_reporte]`" class="form-input">
                                    <option value="">Selecciona el tipo...</option>
                                    <option value="Corrupción">Corrupción</option>
                                    <option value="Mal servicio">Mal servicio público</option>
                                    <option value="Negligencia">Negligencia</option>
                                    <option value="Abuso de autoridad">Abuso de autoridad</option>
                                    <option value="Obras inconclusas">Obras inconclusas o deficientes</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-800"></i>
                                    Ubicación del Problema
                                </label>
                                <input type="text" :name="`reportes[${index}][ubicacion]`"
                                       class="form-input location-input"
                                       :id="`report-location-input-${index}`"
                                       placeholder="Busca o selecciona la ubicación en el mapa"
                                       @click="showMap(index, 'reporte')">
                                <div class="flex gap-2 mt-2">
                                    <button type="button"
                                            @click="toggleMap(index, 'reporte')"
                                            class="bg-gradient-to-r from-red-600 to-red-500 text-white px-4 py-2 rounded-lg hover:from-red-700 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm font-semibold flex-1">
                                        <i class="fas fa-map mr-1"></i>
                                        <span x-text="mapVisible[`reporte-${index}`] ? 'Ocultar Mapa' : 'Mostrar Mapa'"></span>
                                    </button>
                                    <button type="button"
                                            @click="getCurrentLocation(index, 'reporte')"
                                            class="bg-gradient-to-r from-red-700 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-800 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg text-sm font-semibold">
                                        <i class="fas fa-location-arrow mr-1"></i>
                                        Obtener Ubicación
                                    </button>
                                </div>
                                <div :id="`map-reporte-${index}`" class="mt-2 map-container" x-show="mapVisible[`reporte-${index}`]" x-transition></div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Descripción del Reporte *</label>
                                <textarea :name="`reportes[${index}][descripcion]`"
                                          class="form-input" rows="4"
                                          placeholder="Describe detalladamente la situación que deseas reportar..."></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-file-upload mr-2 text-red-800"></i>
                                    Evidencia (Opcional)
                                </label>
                                <input type="file" :name="`reportes[${index}][evidencia]`"
                                       class="form-input" accept="image/*,application/pdf">
                                <p class="text-sm text-gray-500 mt-1">Sube fotos, documentos o videos como evidencia</p>
                            </div>
                        </div>
                    </template>

                    <div class="text-center" x-show="reportes.length < 3">
                        <button type="button"
                                @click="reportes.push({})"
                                class="bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transition-all duration-300 inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Otro Reporte
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botón de Envío -->
            <div class="text-center slide-in-up" x-intersect="updateProgress(100)">
                <button type="submit"
                        class="btn-primary"
                        :disabled="isSubmitting"
                        @click="handleSubmit">
                    <span x-show="!isSubmitting">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Encuesta
                    </span>
                    <span x-show="isSubmitting" class="flex items-center">
                        <div class="loading-spinner mr-2"></div>
                        Procesando...
                    </span>
                </button>

                <p class="text-sm text-gray-500 mt-4">
                    <i class="fas fa-lock mr-2"></i>
                    Tus datos están protegidos y serán tratados de forma confidencial
                </p>
            </div>
        </form>
    </div>

    <script>
        let mapInstances = {};
        let autocompleteInstances = {};

        // Inicializar Google Maps
        function initMap() {
            console.log('Google Maps API cargada correctamente');
        }

        function initLocationPicker(inputId, mapId) {
            const input = document.getElementById(inputId);
            const mapContainer = document.getElementById(mapId);

            if (!input || !mapContainer) {
                console.error('No se encontraron los elementos:', inputId, mapId);
                return;
            }

            // Mostrar el mapa
            mapContainer.style.display = 'block';

            // Si ya existe una instancia, no crear otra
            if (mapInstances[mapId]) {
                return;
            }

            // Coordenadas por defecto (Tecámac, Estado de México)
            const defaultLocation = { lat: 19.7133, lng: -99.0036 };

            // Crear el mapa
            const map = new google.maps.Map(mapContainer, {
                zoom: 13,
                center: defaultLocation,
                mapTypeId: 'roadmap'
            });

            // Crear el marcador
            const marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });

            // Crear el autocomplete
            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            // Configurar el autocomplete para México
            autocomplete.setComponentRestrictions({
                country: ['mx']
            });

            // Cuando se selecciona un lugar del autocomplete
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    console.log("No se encontraron detalles para: '" + place.name + "'");
                    return;
                }

                // Si el lugar tiene viewport, usarlo, sino usar location
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                // Mover el marcador
                marker.setPosition(place.geometry.location);

                // Actualizar el input con la dirección completa
                input.value = place.formatted_address || place.name;
            });

            // Cuando se arrastra el marcador
            marker.addListener('dragend', function() {
                const position = marker.getPosition();

                // Geocoding inverso para obtener la dirección
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: position }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        input.value = results[0].formatted_address;
                    }
                });
            });

            // Cuando se hace click en el mapa
            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);

                // Geocoding inverso
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: event.latLng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        input.value = results[0].formatted_address;
                    }
                });
            });

            // Guardar las instancias
            mapInstances[mapId] = map;
            autocompleteInstances[inputId] = autocomplete;
        }

        function encuestaForm() {
            return {
                progress: 0,
                selectedColonia: null,
                obras: [],
                obrasCalificadas: {},
                isSubmitting: false,
                deseaReporte: false,
                mapVisible: {},
                maps: {},
                propuestas: [{}],
                maxPropuestas: 2,

                init() {
                    // Cargar colonias con obras
                    this.loadColoniaData();
                },

                updateProgress(value) {
                    this.progress = Math.max(this.progress, value);
                },

                async loadColoniaData() {
                    try {
                        const colonias = @json($colonias);
                        this.colonias = colonias;
                    } catch (error) {
                        console.error('Error cargando datos:', error);
                    }
                },

                async loadObras() {
                    const coloniaSelect = document.querySelector('[name="colonia_id"]');
                    const coloniaId = coloniaSelect.value;

                    if (!coloniaId) {
                        this.selectedColonia = null;
                        this.obras = [];
                        return;
                    }

                    // Encontrar la colonia seleccionada
                    const colonias = @json($colonias);
                    this.selectedColonia = colonias.find(c => c.id == coloniaId);

                    try {
                        const response = await fetch(`/encuesta/obras-por-colonia/${coloniaId}`);
                        this.obras = await response.json();
                        this.obrasCalificadas = {};

                        // Actualizar el progreso basado en si hay obras o no
                        this.updateProgress(this.obras.length > 0 ? 50 : 75);
                    } catch (error) {
                        console.error('Error cargando obras:', error);
                        alert('Error al cargar las obras. Por favor, intenta de nuevo.');
                    }
                },

                setObraCalificacion(obraId, calificacion) {
                    this.obrasCalificadas[obraId] = calificacion;
                },

                getObrasCalificadas() {
                    return this.obrasCalificadas;
                },

                toggleMap(index, type) {
                    const key = `${type}-${index}`;
                    this.mapVisible[key] = !this.mapVisible[key];

                    if (this.mapVisible[key]) {
                        this.$nextTick(() => {
                            this.initMap(index, type);
                        });
                    }
                },

                showMap(index, type) {
                    const key = `${type}-${index}`;
                    if (!this.mapVisible[key]) {
                        this.mapVisible[key] = true;

                        // Verificar si Google Maps está disponible
                        if (typeof google === 'undefined' || !google.maps) {
                            alert('Google Maps no está disponible. Por favor recarga la página e intenta de nuevo.');
                            this.mapVisible[key] = false;
                            return;
                        }

                        this.$nextTick(() => {
                            this.initMap(index, type);
                        });
                    }
                },

                initMap(index, type) {
                    const mapId = `map-${type}-${index}`;
                    const inputId = type === 'propuesta' ? `location-input-${index}` : `report-location-input-${index}`;

                    if (this.maps[mapId]) return; // Ya inicializado

                    // Esperar a que el elemento esté disponible
                    setTimeout(() => {
                        const mapElement = document.getElementById(mapId);
                        if (!mapElement) {
                            console.error(`No se encontró elemento del mapa: ${mapId}`);
                            return;
                        }

                        // Asegurarse de que Google Maps esté disponible
                        if (typeof google === 'undefined' || !google.maps) {
                            console.error('Google Maps API no está cargada');
                            alert('Error: Google Maps no está disponible. Por favor recarga la página.');
                            return;
                        }

                        // Coordenadas por defecto (Tecámac, Estado de México)
                        const defaultLocation = { lat: 19.7138, lng: -99.0095 };

                        try {
                            const map = new google.maps.Map(mapElement, {
                                zoom: 13,
                                center: defaultLocation,
                                mapTypeControl: false,
                                streetViewControl: false,
                                fullscreenControl: false,
                                styles: [
                                    {
                                        featureType: "poi",
                                        elementType: "labels",
                                        stylers: [{ visibility: "on" }]
                                    }
                                ]
                            });

                            const marker = new google.maps.Marker({
                                position: defaultLocation,
                                map: map,
                                draggable: true,
                                title: 'Arrastra para seleccionar ubicación',
                                animation: google.maps.Animation.DROP
                            });

                            // Guardar referencia al marcador
                            this.maps[mapId + '_marker'] = marker;

                            // Guardar referencia al marcador
                            this.maps[mapId + '_marker'] = marker;

                            const input = document.getElementById(inputId);
                            if (input) {
                                const autocomplete = new google.maps.places.Autocomplete(input, {
                                    bounds: new google.maps.LatLngBounds(
                                        new google.maps.LatLng(19.65, -99.1),
                                        new google.maps.LatLng(19.8, -98.9)
                                    ),
                                    strictBounds: false,
                                    types: ['address']
                                });

                                autocomplete.addListener('place_changed', () => {
                                    const place = autocomplete.getPlace();
                                    if (place.geometry) {
                                        map.setCenter(place.geometry.location);
                                        marker.setPosition(place.geometry.location);
                                        input.value = place.formatted_address || place.name;
                                    }
                                });
                            }

                            marker.addListener('dragend', () => {
                                const position = marker.getPosition();
                                const geocoder = new google.maps.Geocoder();
                                geocoder.geocode({ location: position }, (results, status) => {
                                    if (status === 'OK' && results[0] && input) {
                                        input.value = results[0].formatted_address;
                                    }
                                });
                            });

                            map.addListener('click', (event) => {
                                marker.setPosition(event.latLng);
                                const geocoder = new google.maps.Geocoder();
                                geocoder.geocode({ location: event.latLng }, (results, status) => {
                                    if (status === 'OK' && results[0] && input) {
                                        input.value = results[0].formatted_address;
                                    }
                                });
                            });

                            this.maps[mapId] = map;
                            console.log(`Mapa inicializado correctamente: ${mapId}`);

                            // Forzar un resize después de un momento para asegurar que se muestre
                            setTimeout(() => {
                                google.maps.event.trigger(map, 'resize');
                            }, 100);

                        } catch (error) {
                            console.error('Error inicializando mapa:', error);
                            alert('Error al cargar el mapa. Por favor intenta de nuevo.');
                        }
                    }, 100);
                },

                getCurrentLocation(index, type) {
                    if (!navigator.geolocation) {
                        alert('La geolocalización no está disponible en este navegador.');
                        return;
                    }

                    // Mostrar indicador de carga
                    const inputId = type === 'propuesta' ? `location-input-${index}` : `report-location-input-${index}`;
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.value = 'Obteniendo ubicación...';
                        input.disabled = true;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            // Restaurar input
                            if (input) {
                                input.disabled = false;
                            }

                            // Verificar que Google Maps esté disponible
                            if (typeof google === 'undefined' || !google.maps) {
                                alert('Google Maps no está disponible. Por favor recarga la página.');
                                if (input) input.value = '';
                                return;
                            }

                            // Obtener dirección usando geocodificación inversa
                            const geocoder = new google.maps.Geocoder();
                            const latlng = { lat: lat, lng: lng };

                            geocoder.geocode({ location: latlng }, (results, status) => {
                                if (status === 'OK' && results[0]) {
                                    if (input) {
                                        input.value = results[0].formatted_address;
                                    }

                                    // Si el mapa está visible, actualizar la posición
                                    const mapId = `map-${type}-${index}`;
                                    if (this.maps[mapId]) {
                                        const map = this.maps[mapId];
                                        const position = new google.maps.LatLng(lat, lng);
                                        map.setCenter(position);

                                        // Encontrar y mover el marcador
                                        if (!this.maps[mapId + '_marker']) {
                                            this.maps[mapId + '_marker'] = new google.maps.Marker({
                                                position: position,
                                                map: map,
                                                draggable: true,
                                                title: 'Tu ubicación actual'
                                            });
                                        } else {
                                            this.maps[mapId + '_marker'].setPosition(position);
                                        }
                                    } else {
                                        // Si el mapa no está visible, mostrarlo
                                        this.showMap(index, type);

                                        // Esperar a que se inicialice y luego actualizar
                                        setTimeout(() => {
                                            if (this.maps[mapId]) {
                                                const map = this.maps[mapId];
                                                const position = new google.maps.LatLng(lat, lng);
                                                map.setCenter(position);

                                                if (this.maps[mapId + '_marker']) {
                                                    this.maps[mapId + '_marker'].setPosition(position);
                                                }
                                            }
                                        }, 500);
                                    }
                                } else {
                                    if (input) {
                                        input.value = `Ubicación: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                                    }
                                }
                            });
                        },
                        (error) => {
                            // Restaurar input
                            if (input) {
                                input.disabled = false;
                                input.value = '';
                            }

                            let mensaje = 'No se pudo obtener la ubicación. ';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    mensaje += 'Permiso denegado por el usuario.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    mensaje += 'Información de ubicación no disponible.';
                                    break;
                                case error.TIMEOUT:
                                    mensaje += 'Tiempo de espera agotado.';
                                    break;
                                default:
                                    mensaje += 'Error desconocido.';
                                    break;
                            }
                            alert(mensaje);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 60000
                        }
                    );
                },

                handleSubmit(event) {
                    event.preventDefault();

                    if (!this.selectedColonia) {
                        alert('Por favor, selecciona una colonia antes de enviar.');
                        return;
                    }

                    // Solo validar obras si la colonia tiene obras disponibles
                    if (this.obras.length > 0) {
                        const calificaciones = Object.keys(this.obrasCalificadas);
                        if (calificaciones.length === 0) {
                            alert('Por favor, califica al menos una obra pública.');
                            return;
                        }
                    }

                    this.isSubmitting = true;

                    setTimeout(() => {
                        event.target.closest('form').submit();
                    }, 100);
                }
            }
        }

        // Efectos adicionales
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });

            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.form-group').style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.closest('.form-group').style.transform = 'scale(1)';
                });
            });
        });

        // Función de callback para Google Maps API
        function initMap() {
            console.log('Google Maps API cargada correctamente');
            // Verificar que todas las librerías estén disponibles
            if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                console.log('Google Maps y Places API están disponibles');
            } else {
                console.error('Error: Google Maps API no se cargó correctamente');
            }
        }

        // Asegurar que Google Maps esté disponible
        window.initMap = initMap;

        // Verificar carga de Google Maps cada 500ms hasta que esté disponible
        let mapCheckInterval = setInterval(() => {
            if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                console.log('Google Maps API verificada y disponible');
                clearInterval(mapCheckInterval);
            } else {
                console.log('Esperando a que Google Maps API se cargue...');
            }
        }, 500);
    </script>
</body>
</html>
