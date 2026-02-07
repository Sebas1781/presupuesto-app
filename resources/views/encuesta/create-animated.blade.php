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
        .bg-red-600, .bg-gradient-to-r.from-red-600 {
            background-color: #dc2626 !important;
            background-image: linear-gradient(to right, #dc2626, #b91c1c) !important;
        }

        .bg-red-700, .bg-gradient-to-r.from-red-700 {
            background-color: #b91c1c !important;
            background-image: linear-gradient(to right, #b91c1c, #991b1b) !important;
        }

        .hover\:bg-red-700:hover, .hover\:from-red-700:hover {
            background-color: #b91c1c !important;
            background-image: linear-gradient(to right, #b91c1c, #991b1b) !important;
        }

        .hover\:bg-red-800:hover, .hover\:to-red-700:hover {
            background-color: #991b1b !important;
            background-image: linear-gradient(to right, #b91c1c, #991b1b) !important;
        }

        button[class*="bg-red"], button[class*="from-red"] {
            color: white !important;
            font-weight: bold !important;
            border: none !important;
        }

        button[class*="bg-red"]:hover, button[class*="from-red"]:hover {
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4) !important;
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

        /* === LOGO RESPONSIVE === */
        .header-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        /* === TABLAS: desktop visible, mobile oculto === */
        .tabla-desktop { display: block; }
        .tabla-mobile { display: none; }

        /* === ESCALA 1-10 RESPONSIVE === */
        .escala-container {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }
        .escala-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #60a5fa;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
            color: #374151;
            flex-shrink: 0;
        }
        .escala-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .escala-btn:hover {
            background: #dbeafe;
        }
        .escala-label {
            font-size: 12px;
            color: #6b7280;
            white-space: nowrap;
        }

        /* === SNACKBAR === */
        .snackbar {
            position: fixed;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            background: #dc2626;
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            z-index: 10000;
            box-shadow: 0 8px 30px rgba(220, 38, 38, 0.4);
            transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 90vw;
            text-align: center;
        }
        .snackbar.show {
            bottom: 30px;
        }

        /* === CAMPO CON ERROR === */
        .field-error {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2) !important;
            animation: shake 0.5s ease-in-out;
        }

        /* === MOBILE DROPDOWN CARD === */
        .mobile-question-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 10px;
        }
        .mobile-question-card label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
        }
        .mobile-question-card select {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            background: white;
        }

        @media (max-width: 768px) {
            .header-logo {
                height: 36px;
            }
            .tabla-desktop { display: none !important; }
            .tabla-mobile { display: block !important; }

            .escala-container {
                justify-content: center;
                gap: 8px;
            }
            .escala-btn {
                width: 40px;
                height: 40px;
                font-size: 15px;
            }
            .escala-label {
                width: 100%;
                text-align: center;
                font-size: 11px;
            }
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

        /* Estilos para secciones ocultas/mostradas */
        .hidden {
            display: none !important;
        }

        .show {
            display: block !important;
            animation: slideInUp 0.6s ease-out forwards;
        }

        /* Mejoras visuales para mensajes de error */
        .error-message {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Estilos para el modal de emergencia */
        .emergency-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 20px;
            box-sizing: border-box;
        }

        .emergency-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            max-width: 500px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.7) translateY(50px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 auto;
        }

        .emergency-modal.show .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .modal-btn-primary:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .modal-btn-secondary {
            background: #f8fafc;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .modal-btn-secondary:hover {
            background: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
        }

        .warning-icon {
            font-size: 4rem;
            color: #fbbf24;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>

<body x-data="encuestaForm()" class="antialiased">
    <!-- Header mejorado -->
    <header class="header-gradient text-white shadow-xl">
        <div class="max-w-6xl mx-auto px-6 py-6 relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 slide-in-up">
                    <img src="{{ asset('images/ayunto-2026-h.png') }}" alt="Tecámac 2026" class="header-logo">
                    <div>
                        <h1 class="text-2xl font-bold">Consulta Ciudadana 2026</h1>
                        <p class="text-white/80 text-sm">Nuestro municipio es mucha pieza</p>
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
                <span class="text-sm font-medium text-gray-600">Progreso de la consulta ciudadana</span>
                <span class="text-sm font-medium text-gray-600" x-text="Math.round(progress) + '%'">0%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" :style="`width: ${progress}%`"></div>
            </div>
        </div>
    </div>

    <!-- Modal de Emergencia -->
    <div id="emergencyModal" class="emergency-modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-exclamation-triangle warning-icon"></i>
                <h2 class="text-2xl font-bold mb-2">Confirmación Importante</h2>
                <p class="text-lg opacity-90">Antes de continuar</p>
            </div>
            <div class="modal-body">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart-pulse text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Estado de Emergencia</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        <strong>No me encuentro en una situación de emergencia médica o peligro de muerte en este momento.</strong>
                    </p>
                </div>

                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-lg mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-orange-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-orange-800 font-medium mb-2">Aviso Importante:</p>
                            <p class="text-orange-700 text-sm leading-relaxed">
                                Este formulario <strong>NO es monitoreado 24/7</strong>. Si tienes una emergencia médica o te encuentras en peligro inmediato, <strong>NO uses este sitio</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-red-500 mr-3 text-xl"></i>
                        <div>
                            <p class="text-red-800 font-bold text-lg">En caso de emergencia:</p>
                            <p class="text-red-700 text-xl font-bold">Llama al 911 INMEDIATAMENTE</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEmergencyModal(false)" class="modal-btn modal-btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="button" onclick="closeEmergencyModal(true)" class="modal-btn modal-btn-primary">
                    <i class="fas fa-check mr-2"></i>
                    Entiendo y Deseo Continuar
                </button>
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
                            <option value="LGBTIITTQ" {{ old('genero') == 'LGBTIITTQ' ? 'selected' : '' }}>LGBTTTIQ+</option>
                        </select>
                    </div>

                    <!-- Edad -->
                    <div class="form-group slide-in-up slide-in-up-delay-3">
                        <label class="form-label">
                            <i class="fas fa-birthday-cake mr-2 text-red-800"></i>
                            Edad *
                        </label>
                        <select name="edad" class="form-input" required>
                            <option value="">Selecciona tu rango de edad...</option>
                            <option value="De 18 a 24 años" {{ old('edad') == 'De 18 a 24 años' ? 'selected' : '' }}>De 18 a 24 años</option>
                            <option value="De 25 a 34 años" {{ old('edad') == 'De 25 a 34 años' ? 'selected' : '' }}>De 25 a 34 años</option>
                            <option value="De 35 a 49 años" {{ old('edad') == 'De 35 a 49 años' ? 'selected' : '' }}>De 35 a 49 años</option>
                            <option value="De 50 a 59 años" {{ old('edad') == 'De 50 a 59 años' ? 'selected' : '' }}>De 50 a 59 años</option>
                            <option value="Más de 60 años" {{ old('edad') == 'Más de 60 años' ? 'selected' : '' }}>Más de 60 años</option>
                        </select>
                    </div>

                    <!-- Nivel Educativo -->
                    <div class="form-group slide-in-up slide-in-up-delay-4">
                        <label class="form-label">
                            <i class="fas fa-graduation-cap mr-2 text-red-800"></i>
                            Nivel Educativo *
                        </label>
                        <select name="nivel_educativo" class="form-input" required>
                            <option value="">Selecciona...</option>
                            <option value="Ninguno" {{ old('nivel_educativo') == 'Sin estudios' ? 'selected' : '' }}>Ninguno</option>
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

                <!-- Botón para continuar -->
                <div class="flex justify-end mt-6">
                    <button type="button"
                            @click="validateAndContinue()"
                            class="btn-primary">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Continuar
                    </button>
                </div>

                <!-- Mensaje de error -->
                <div id="sociodemographic-error" class="hidden mt-4 p-4 bg-red-100 border-l-4 border-red-500 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-red-700 font-medium">Por favor completa todos los campos obligatorios de Datos Sociodemográficos antes de continuar.</span>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Calificación de Obras (inicialmente oculta) -->
            <div id="obras-section" class="form-section slide-in-up hidden" x-show="selectedColonia && obras.length > 0">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-700 to-red-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Califica las Obras Públicas</h3>
                </div>

                <p class="text-gray-600 mb-6 bg-red-50 p-4 rounded-lg border-l-4 border-red-400">
                    <i class="fas fa-info-circle mr-2 text-red-500"></i>
                    Califique la prioridad de estas obras públicas del 1 al 5 (donde "1" es poco urgente y "5" es muy urgente)
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
            <div class="form-section slide-in-up" x-show="selectedColonia && obras.length === 0">
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">¡TU OPINIÓN ES MUY IMPORTANTE!</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                      A CONTINUACIÓN PODRÁS AGREGAR TUS PROPUESTAS
                    </p>
                </div>
            </div>

            <!-- Sección Seguridad Pública -->
            <div id="seguridad-section" class="form-section slide-in-up hidden" x-data="{
                seguridad: {
                    servicio_seguridad: '',
                    confia_policia: '',
                    horario_inseguro: '',
                    problemas_seguridad: {},
                    lugares_seguros: {},
                    situaciones_escala: {},
                    emergencia_transporte: '',
                    caminar_noche: '',
                    hijos_solos: '',
                    transporte_publico: ''
                }
            }">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-700 to-blue-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">BLOQUE C: SEGURIDAD PÚBLICA</h3>
                </div>

                <!-- C.1. Calificación del servicio de seguridad pública -->
                <div class="propuesta-card mb-6 slide-in-up">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        C.1. ¿Cómo califica el servicio de seguridad pública en su comunidad?
                    </h4>
                    <div class="space-y-2">
                        <template x-for="opcion in ['Excelente', 'Muy buena', 'Buena', 'Regular', 'Mala']">
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" :value="opcion" x-model="seguridad.servicio_seguridad" name="seguridad[servicio_seguridad]" class="form-radio text-blue-600">
                                <span x-text="opcion" class="text-gray-700"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- C.2. Confianza en policías -->
                <div class="propuesta-card mb-6 slide-in-up">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        C.2. En general, ¿confía en las y los policías de la Guardia Civil de Tecámac?
                    </h4>
                    <div class="space-y-2">
                        <template x-for="opcion in ['Sí', 'No', 'No sé']">
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" :value="opcion" x-model="seguridad.confia_policia" name="seguridad[confia_policia]" class="form-radio text-blue-600">
                                <span x-text="opcion" class="text-gray-700"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- C.3. Horario de inseguridad -->
                <div class="propuesta-card mb-6 slide-in-up">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        En caso de que usted y su familia se sientan inseguros en la comunidad en donde viven, ¿en qué horario es más frecuente?
                    </h4>
                    <div class="space-y-2">
                        <template x-for="opcion in [
                            'De 6 a 9 de la mañana',
                            'De 9 a 12 del día',
                            'De 12 de medio día a 3 de la tarde',
                            'De 3 a 6 de la tarde',
                            'De 6 de la tarde a 9 de la noche',
                            'De 9 de la noche a 12 de la madrugada',
                            'De 12 a 6 de la madrugada',
                            'Nos sentimos seguros en nuestra colonia'
                        ]">
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" :value="opcion" x-model="seguridad.horario_inseguro" name="seguridad[horario_inseguro]" class="form-radio text-blue-600">
                                <span x-text="opcion" class="text-gray-700"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- C.4. Problemas de inseguridad y violencia -->
                <div class="propuesta-card mb-6 slide-in-up">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        C.4. Refiriéndonos al entorno de su domicilio, ¿qué tanta preocupación le causan los siguientes problemas de inseguridad o violencia?
                    </h4>

                    <!-- DESKTOP: Tabla con radios -->
                    <div class="tabla-desktop overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="text-left p-3 font-medium text-gray-600">Problema</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Me preocupa mucho</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Más o menos me preocupa</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Me preocupa poco</th>
                                    <th class="text-center p-3 font-medium text-gray-600">No me preocupa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(problema, index) in [
                                    'Corrupción de los elementos de seguridad',
                                    'Robo a casa habitación',
                                    'Asaltos a transeúntes',
                                    'Robo de vehículos, motos o autopartes',
                                    'Extorsión por llamada telefónica',
                                    'Venta de sustancias ilícitas (drogas)',
                                    'Falta de vigilancia y presencia de policías',
                                    'Venta y/o consumo de alcohol en la calle',
                                    'Violencia familiar, o contra las mujeres, niñas o niños',
                                    'Violencia en contra de los y las adultos mayores',
                                    'Violencia en contra de los animales domésticos o mascotas',
                                    'Violencia en contra de las personas discapacitadas',
                                    'Bullying en las escuelas',
                                    'Acoso o molestias en la calle a mujeres, señoritas, niñas',
                                    'Actos de discriminación o molestia a personas que se identifican como parte de la comunidad de LGTTBIQ+',
                                    'Riñas, peleas o lesiones entre vecinos',
                                    'Venta y/o consumo de sustancias ilícitas (drogas) en la calle'
                                ]" :key="index">
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="p-3 text-gray-700" x-text="problema"></td>
                                        <template x-for="valor in [4, 3, 2, 1]">
                                            <td class="text-center p-3">
                                                <input type="radio" :value="valor" :name="`seguridad[problemas_seguridad][${index}]`" class="form-radio text-blue-600 seguridad-required" :data-pregunta="problema">
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE: Dropdowns -->
                    <div class="tabla-mobile space-y-2">
                        <template x-for="(problema, index) in [
                            'Corrupción de los elementos de seguridad',
                            'Robo a casa habitación',
                            'Asaltos a transeúntes',
                            'Robo de vehículos, motos o autopartes',
                            'Extorsión por llamada telefónica',
                            'Venta de sustancias ilícitas (drogas)',
                            'Falta de vigilancia y presencia de policías',
                            'Venta y/o consumo de alcohol en la calle',
                            'Violencia familiar, o contra las mujeres, niñas o niños',
                            'Violencia en contra de los y las adultos mayores',
                            'Violencia en contra de los animales domésticos o mascotas',
                            'Violencia en contra de las personas discapacitadas',
                            'Bullying en las escuelas',
                            'Acoso o molestias en la calle a mujeres, señoritas, niñas',
                            'Actos de discriminación o molestia a personas que se identifican como parte de la comunidad de LGTTBIQ+',
                            'Riñas, peleas o lesiones entre vecinos',
                            'Venta y/o consumo de sustancias ilícitas (drogas) en la calle'
                        ]" :key="'m-prob-'+index">
                            <div class="mobile-question-card">
                                <label x-text="problema"></label>
                                <select :name="`seguridad[problemas_seguridad][${index}]`" class="seguridad-required" :data-pregunta="problema">
                                    <option value="">Seleccione...</option>
                                    <option value="4">Me preocupa mucho</option>
                                    <option value="3">Más o menos me preocupa</option>
                                    <option value="2">Me preocupa poco</option>
                                    <option value="1">No me preocupa</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- C.22. Lugares seguros -->
                <div class="propuesta-card mb-6 slide-in-up">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        C.22. Imagine que se encuentra con su familia en los siguientes lugares que le voy a mencionar ¿qué tan seguros e inseguros se sentirían si fuera un viernes a las 8 de la noche en?
                    </h4>

                    <!-- DESKTOP: Tabla con radios -->
                    <div class="tabla-desktop overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="text-left p-3 font-medium text-gray-600">Lugar</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Seguros</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Más o menos seguros</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Poco seguros</th>
                                    <th class="text-center p-3 font-medium text-gray-600">Totalmente inseguros</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(lugar, index) in [
                                    'Un parque de su comunidad',
                                    'En el mercado o tianguis',
                                    'Al visitar una plaza comercial o un supermercado',
                                    'En un cajero automático',
                                    'A bordo de un camión, micro o vagoneta de transporte público de pasajeros',
                                    'Al exterior de una escuela',
                                    'Caminando en las calles cercanas a su domicilio',
                                    'En el Municipio de Tecámac'
                                ]" :key="index">
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="p-3 text-gray-700" x-text="lugar"></td>
                                        <template x-for="valor in [4, 3, 2, 1]">
                                            <td class="text-center p-3">
                                                <input type="radio" :value="valor" :name="`seguridad[lugares_seguros][${index}]`" class="form-radio text-blue-600 seguridad-required" :data-pregunta="lugar">
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE: Dropdowns -->
                    <div class="tabla-mobile space-y-2">
                        <template x-for="(lugar, index) in [
                            'Un parque de su comunidad',
                            'En el mercado o tianguis',
                            'Al visitar una plaza comercial o un supermercado',
                            'En un cajero automático',
                            'A bordo de un camión, micro o vagoneta de transporte público de pasajeros',
                            'Al exterior de una escuela',
                            'Caminando en las calles cercanas a su domicilio',
                            'En el Municipio de Tecámac'
                        ]" :key="'m-lug-'+index">
                            <div class="mobile-question-card">
                                <label x-text="lugar"></label>
                                <select :name="`seguridad[lugares_seguros][${index}]`" class="seguridad-required" :data-pregunta="lugar">
                                    <option value="">Seleccione...</option>
                                    <option value="4">Seguros</option>
                                    <option value="3">Más o menos seguros</option>
                                    <option value="2">Poco seguros</option>
                                    <option value="1">Totalmente inseguros</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- C.31. Escala de situaciones -->
                <div class="propuesta-card mb-6 slide-in-up">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        C.31. Imagine las siguientes situaciones y responda en una escala del 1 al 10 (uno es "muy inseguro" y diez es "muy seguro")
                    </h4>
                    <p class="text-gray-600 mb-4 text-sm">Califique del 1 al 10:</p>

                    <div class="space-y-6">
                        <!-- C.32 -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <h5 class="font-medium text-gray-800 mb-3">
                                C.32. Imaginando una situación de emergencia, si usted necesitara salir de su domicilio a las 3 de la madrugada y caminar cinco calles para tomar un taxi o transporte, ¿qué tan seguro/a se sentiría de hacerlo?
                            </h5>
                            <div class="escala-container">
                                <span class="escala-label">1 (Muy inseguro)</span>
                                <template x-for="i in 10">
                                    <button type="button"
                                            class="escala-btn"
                                            :class="seguridad.situaciones_escala.emergencia_transporte == i ? 'active' : ''"
                                            @click="seguridad.situaciones_escala.emergencia_transporte = i"
                                            x-text="i">
                                    </button>
                                </template>
                                <span class="escala-label">10 (Muy seguro)</span>
                                <input type="hidden" name="seguridad[emergencia_transporte]" :value="seguridad.situaciones_escala.emergencia_transporte">
                            </div>
                        </div>

                        <!-- C.33 -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <h5 class="font-medium text-gray-800 mb-3">
                                C.33. ¿Qué tan seguro/a se siente al caminar solo/a después de las 10 de la noche, en su colonia?
                            </h5>
                            <div class="escala-container">
                                <span class="escala-label">1 (Muy inseguro)</span>
                                <template x-for="i in 10">
                                    <button type="button"
                                            class="escala-btn"
                                            :class="seguridad.situaciones_escala.caminar_noche == i ? 'active' : ''"
                                            @click="seguridad.situaciones_escala.caminar_noche = i"
                                            x-text="i">
                                    </button>
                                </template>
                                <span class="escala-label">10 (Muy seguro)</span>
                                <input type="hidden" name="seguridad[caminar_noche]" :value="seguridad.situaciones_escala.caminar_noche">
                            </div>
                        </div>

                        <!-- C.34 -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <h5 class="font-medium text-gray-800 mb-3">
                                C.34. ¿Qué tan tranquilo/a se siente de que sus hijas o hijos caminen solos/as por su colonia durante el día?
                            </h5>
                            <div class="escala-container">
                                <span class="escala-label">1 (Muy inseguro)</span>
                                <template x-for="i in 10">
                                    <button type="button"
                                            class="escala-btn"
                                            :class="seguridad.situaciones_escala.hijos_solos == i ? 'active' : ''"
                                            @click="seguridad.situaciones_escala.hijos_solos = i"
                                            x-text="i">
                                    </button>
                                </template>
                                <span class="escala-label">10 (Muy seguro)</span>
                                <input type="hidden" name="seguridad[hijos_solos]" :value="seguridad.situaciones_escala.hijos_solos">
                            </div>
                        </div>

                        <!-- C.35 -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <h5 class="font-medium text-gray-800 mb-3">
                                C.35. ¿Qué tan seguro/a se siente al esperar el transporte público en su colonia durante la noche?
                            </h5>
                            <div class="escala-container">
                                <span class="escala-label">1 (Muy inseguro)</span>
                                <template x-for="i in 10">
                                    <button type="button"
                                            class="escala-btn"
                                            :class="seguridad.situaciones_escala.transporte_publico == i ? 'active' : ''"
                                            @click="seguridad.situaciones_escala.transporte_publico = i"
                                            x-text="i">
                                    </button>
                                </template>
                                <span class="escala-label">10 (Muy seguro)</span>
                                <input type="hidden" name="seguridad[transporte_publico]" :value="seguridad.situaciones_escala.transporte_publico">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Propuestas (inicialmente oculta) -->
            <div id="propuestas-section" class="form-section slide-in-up hidden" x-intersect="updateProgress(75)">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-yellow-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-lightbulb text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Tus Propuestas de Mejora</h3>
                </div>



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
                                        <option value="Obra">Obra</option>
                                        <option value="Bacheo">Bacheo</option>
                                        <option value="Luminaria">Luminaria</option>
                                        <option value="Apoyo">Apoyo</option>
                                        <option value="Seguridad publica">Seguridad publica</option>
                                        <option value="Bienestar animal">Bienestar animal</option>
                                        <option value="Recolección de RSU">Recolección de RSU</option>
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
                                    <select :name="`propuestas[${index}][personas_beneficiadas]`" class="form-input" required>
                                        <option value="">Selecciona...</option>
                                        <option value="Toda la comunidad">Toda la comunidad</option>
                                        <option value="Algunos vecinos">Algunos vecinos</option>
                                        <option value="Yo u otra persona">Yo u otra persona</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Descripción breve de la propuesta *</label>
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

            <!-- Sección 4: Reportes Anónimos (inicialmente oculta) -->
            <div id="reportes-section" class="form-section slide-in-up hidden" x-intersect="updateProgress(90)">
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
                               @click="showEmergencyConfirmation($event)">
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
                                    <option value="Maltrato infantil">Maltrato infantil</option>
                                    <option value="Violencia de genero">Violencia de genero</option>
                                    <option value="Maltrato animal">Maltrato animal</option>
                                    <option value="violencia a otra persona con discapacidad">Violencia a otra persona con discapacidad</option>
+                                </select>
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
            <!-- Botón de envío - Solo mostrar cuando todas las secciones estén completas -->
            <div class="text-center slide-in-up" x-intersect="updateProgress(100)">
                <div x-show="progress >= 50 && selectedColonia">
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
                        Las opiniones y propuestas recabadas en esta encuesta serán consideradas como un ejercicio de participación ciudadana para la integración del Presupuesto Municipal 2026, conforme a la Ley Orgánica Municipal del Estado de México y el Código Financiero del Estado de México y Municipios. La asignación y aprobación final de los recursos corresponde al Ayuntamiento, a través del Cabildo, con base en la viabilidad técnica, la disponibilidad presupuestal y las prioridades del municipio.
Los resultados de este ejercicio se integrarán al proceso de planeación y se darán a conocer de manera transparente, como parte del compromiso del gobierno municipal con la rendición de cuentas y el uso responsable de los recursos públicos.
                    </p>
                </div>

                <!-- Mensaje cuando no se puede enviar todavía -->
                <div x-show="!(progress >= 50 && selectedColonia)"
                     class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="text-gray-600 mb-2">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        Complete los datos sociodemográficos para continuar
                    </div>
                    <div class="text-sm text-gray-500">
                        <span x-show="!selectedColonia">• Seleccione una colonia</span>
                        <span x-show="progress < 50">• Complete todos los campos requeridos</span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Snackbar de validación -->
    <div id="snackbar" class="snackbar"></div>

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

                // Validar datos sociodemográficos antes de continuar
                validateAndContinue() {
                    const requiredFields = [
                        { name: 'colonia_id', label: 'Colonia' },
                        { name: 'genero', label: 'Género' },
                        { name: 'edad', label: 'Edad' },
                        { name: 'nivel_educativo', label: 'Nivel Educativo' },
                        { name: 'estado_civil', label: 'Estado Civil' }
                    ];

                    let allFieldsValid = true;
                    let missingFields = [];
                    let firstMissing = null;

                    requiredFields.forEach(function(f) {
                        const field = document.querySelector('[name="' + f.name + '"]');
                        if (!field || !field.value.trim()) {
                            allFieldsValid = false;
                            missingFields.push(f.label);
                            if (!firstMissing && field) { firstMissing = field; markFieldError(field); }
                        }
                    });

                    const errorDiv = document.getElementById('sociodemographic-error');

                    if (allFieldsValid) {
                        // Ocultar mensaje de error
                        errorDiv.classList.add('hidden');

                        // Mostrar sección de obras
                        const obrasSection = document.getElementById('obras-section');
                        obrasSection.classList.remove('hidden');

                        // Mostrar sección de seguridad pública
                        const seguridadSection = document.getElementById('seguridad-section');
                        seguridadSection.classList.remove('hidden');

                        // Mostrar sección de propuestas
                        const propuestasSection = document.getElementById('propuestas-section');
                        propuestasSection.classList.remove('hidden');

                        // Mostrar sección de reportes
                        const reportesSection = document.getElementById('reportes-section');
                        reportesSection.classList.remove('hidden');

                        // Scroll suave a la sección de obras
                        obrasSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

                        // Actualizar progreso
                        this.progress = Math.max(this.progress, 50);
                        this.updateProgress(50);
                    } else {
                        // Mostrar snackbar con campos faltantes
                        showSnackbar('Campos obligatorios faltantes: ' + missingFields.join(', '), 5000);

                        // Mostrar mensaje de error inline también
                        errorDiv.classList.remove('hidden');
                        errorDiv.querySelector('span').textContent = 'Por favor completa: ' + missingFields.join(', ');

                        // Focus al primer campo faltante
                        if (firstMissing) {
                            firstMissing.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            setTimeout(function() { firstMissing.focus(); }, 500);
                        }
                    }
                },

                // Mostrar popup de confirmación para reportes anónimos
                showEmergencyConfirmation(event) {
                    event.preventDefault();

                    // Guardar referencia al checkbox
                    this.emergencyCheckbox = event.target;

                    // Mostrar el modal personalizado
                    const modal = document.getElementById('emergencyModal');
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
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

                        // No actualizar progreso automáticamente - solo cuando userían clic en 'Continuar'
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

                    // ============================
                    // VALIDACIÓN COMPLETA DEL FORMULARIO
                    // ============================
                    let firstInvalid = null;
                    let errorMessages = [];

                    // --- 1. DATOS SOCIODEMOGRÁFICOS ---
                    const socioFields = [
                        { name: 'colonia_id', label: 'Colonia' },
                        { name: 'genero', label: 'Género' },
                        { name: 'edad', label: 'Edad' },
                        { name: 'nivel_educativo', label: 'Nivel Educativo' },
                        { name: 'estado_civil', label: 'Estado Civil' }
                    ];
                    socioFields.forEach(function(f) {
                        const el = document.querySelector('[name="' + f.name + '"]');
                        if (!el || !el.value || !el.value.trim()) {
                            errorMessages.push(f.label);
                            if (!firstInvalid && el) { firstInvalid = el; markFieldError(el); }
                        }
                    });

                    // --- 2. OBRAS PÚBLICAS (al menos una si hay obras) ---
                    if (this.obras && this.obras.length > 0) {
                        const calificaciones = Object.keys(this.obrasCalificadas);
                        if (calificaciones.length === 0) {
                            errorMessages.push('Calificación de al menos una obra');
                            if (!firstInvalid) {
                                var obrasEl = document.getElementById('obras-section');
                                if (obrasEl) firstInvalid = obrasEl;
                            }
                        }
                    }

                    // --- 3. SEGURIDAD: C.1 servicio_seguridad ---
                    var c1Checked = document.querySelector('input[name="seguridad[servicio_seguridad]"]:checked');
                    if (!c1Checked) {
                        errorMessages.push('C.1 Servicio de seguridad');
                        if (!firstInvalid) {
                            var c1El = document.querySelector('input[name="seguridad[servicio_seguridad]"]');
                            if (c1El) { firstInvalid = c1El.closest('.propuesta-card') || c1El; markFieldError(c1El.closest('.propuesta-card')); }
                        }
                    }

                    // --- 4. SEGURIDAD: C.2 confia_policia ---
                    var c2Checked = document.querySelector('input[name="seguridad[confia_policia]"]:checked');
                    if (!c2Checked) {
                        errorMessages.push('C.2 Confianza en policías');
                        if (!firstInvalid) {
                            var c2El = document.querySelector('input[name="seguridad[confia_policia]"]');
                            if (c2El) { firstInvalid = c2El.closest('.propuesta-card') || c2El; markFieldError(c2El.closest('.propuesta-card')); }
                        }
                    }

                    // --- 5. SEGURIDAD: C.3 horario_inseguro ---
                    var c3Checked = document.querySelector('input[name="seguridad[horario_inseguro]"]:checked');
                    if (!c3Checked) {
                        errorMessages.push('C.3 Horario de inseguridad');
                        if (!firstInvalid) {
                            var c3El = document.querySelector('input[name="seguridad[horario_inseguro]"]');
                            if (c3El) { firstInvalid = c3El.closest('.propuesta-card') || c3El; markFieldError(c3El.closest('.propuesta-card')); }
                        }
                    }

                    // --- 6. C.4 Problemas de seguridad (17 preguntas) ---
                    var c4Total = 17;
                    var c4Missing = [];
                    for (var ci = 0; ci < c4Total; ci++) {
                        var fieldName = 'seguridad[problemas_seguridad][' + ci + ']';
                        if (isMobile()) {
                            var sel = document.querySelector('select[name="' + fieldName + '"]');
                            if (sel && (!sel.value || sel.value === '')) {
                                c4Missing.push(ci);
                                if (!firstInvalid) { firstInvalid = sel; markFieldError(sel.closest('.mobile-question-card') || sel); }
                            }
                        } else {
                            var checked = document.querySelector('input[name="' + fieldName + '"]:checked');
                            if (!checked) {
                                c4Missing.push(ci);
                                if (!firstInvalid) {
                                    var radioEl = document.querySelector('input[name="' + fieldName + '"]');
                                    if (radioEl) { firstInvalid = radioEl.closest('tr') || radioEl; markFieldError(radioEl.closest('tr')); }
                                }
                            }
                        }
                    }
                    if (c4Missing.length > 0) {
                        errorMessages.push('C.4 Problemas de inseguridad (' + c4Missing.length + ' sin responder)');
                    }

                    // --- 7. C.22 Lugares seguros (8 lugares) ---
                    var c22Total = 8;
                    var c22Missing = [];
                    for (var li = 0; li < c22Total; li++) {
                        var lugarName = 'seguridad[lugares_seguros][' + li + ']';
                        if (isMobile()) {
                            var sel2 = document.querySelector('select[name="' + lugarName + '"]');
                            if (sel2 && (!sel2.value || sel2.value === '')) {
                                c22Missing.push(li);
                                if (!firstInvalid) { firstInvalid = sel2; markFieldError(sel2.closest('.mobile-question-card') || sel2); }
                            }
                        } else {
                            var checked2 = document.querySelector('input[name="' + lugarName + '"]:checked');
                            if (!checked2) {
                                c22Missing.push(li);
                                if (!firstInvalid) {
                                    var radioEl2 = document.querySelector('input[name="' + lugarName + '"]');
                                    if (radioEl2) { firstInvalid = radioEl2.closest('tr') || radioEl2; markFieldError(radioEl2.closest('tr')); }
                                }
                            }
                        }
                    }
                    if (c22Missing.length > 0) {
                        errorMessages.push('C.22 Lugares seguros (' + c22Missing.length + ' sin responder)');
                    }

                    // --- 8. C.32-C.35 Escalas 1-10 ---
                    var escalas = [
                        { name: 'seguridad[emergencia_transporte]', label: 'C.32 Emergencia transporte' },
                        { name: 'seguridad[caminar_noche]', label: 'C.33 Caminar de noche' },
                        { name: 'seguridad[hijos_solos]', label: 'C.34 Hijos solos' },
                        { name: 'seguridad[transporte_publico]', label: 'C.35 Transporte público noche' }
                    ];
                    escalas.forEach(function(esc) {
                        var hiddenInput = document.querySelector('input[type="hidden"][name="' + esc.name + '"]');
                        if (!hiddenInput || !hiddenInput.value || hiddenInput.value === '') {
                            errorMessages.push(esc.label);
                            if (!firstInvalid) {
                                var container = hiddenInput ? hiddenInput.closest('.border-l-4') : null;
                                if (container) { firstInvalid = container; markFieldError(container); }
                            }
                        }
                    });

                    // ============================
                    // SI HAY ERRORES: mostrar snackbar + focus
                    // ============================
                    if (errorMessages.length > 0) {
                        var msg;
                        if (errorMessages.length <= 3) {
                            msg = 'Faltan campos: ' + errorMessages.join(', ');
                        } else {
                            msg = 'Faltan ' + errorMessages.length + ' campos obligatorios por completar';
                        }
                        showSnackbar(msg, 5000);

                        if (firstInvalid) {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            // Intentar focus si es un input/select
                            setTimeout(function() {
                                if (firstInvalid.tagName === 'INPUT' || firstInvalid.tagName === 'SELECT') {
                                    firstInvalid.focus();
                                } else {
                                    var focusable = firstInvalid.querySelector('input, select, button');
                                    if (focusable) focusable.focus();
                                }
                            }, 500);
                        }
                        return;
                    }

                    // ============================
                    // TODO OK: enviar formulario
                    // ============================
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

        // === SNACKBAR GLOBAL ===
        function showSnackbar(message, duration) {
            duration = duration || 4000;
            const snackbar = document.getElementById('snackbar');
            if (!snackbar) return;
            snackbar.textContent = message;
            snackbar.classList.add('show');
            clearTimeout(snackbar._timeout);
            snackbar._timeout = setTimeout(function() {
                snackbar.classList.remove('show');
            }, duration);
        }

        // === HELPER: Añadir error visual a un campo ===
        function markFieldError(el) {
            if (!el) return;
            el.classList.add('field-error');
            setTimeout(function() { el.classList.remove('field-error'); }, 2500);
        }

        // === HELPER: Comprobar si estamos en móvil ===
        function isMobile() {
            return window.innerWidth <= 768;
        }

        // Funciones para el modal de emergencia
        function closeEmergencyModal(confirmed) {
            const modal = document.getElementById('emergencyModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';

            // Obtener la instancia de Alpine.js y manejar el checkbox
            const formData = Alpine.$data(document.querySelector('[x-data]'));

            if (confirmed) {
                if (formData.emergencyCheckbox) {
                    formData.emergencyCheckbox.checked = true;
                }
                formData.deseaReporte = true;
            } else {
                if (formData.emergencyCheckbox) {
                    formData.emergencyCheckbox.checked = false;
                }
                formData.deseaReporte = false;
            }
        }

        // Cerrar modal al hacer click fuera de él
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('emergencyModal');
            if (event.target === modal) {
                closeEmergencyModal(false);
            }
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('emergencyModal');
                if (modal.classList.contains('show')) {
                    closeEmergencyModal(false);
                }
            }
        });
    </script>
</body>
</html>
