<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto Participativo 2026 - Tecámac</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #8B1538 0%, #C8102E 50%, #E91E63 100%);
            min-height: 100vh;
        }

        /* Colores oficiales Pantone */
        :root {
            --pantone-7420: #8B1538;
            --pantone-1805: #C8102E;
            --gradient-bg: linear-gradient(135deg, #8B1538 0%, #C8102E 50%, #E91E63 100%);
        }
        
        /* Animaciones de entrada */
        .fade-in {
            opacity: 0;
            animation: fadeIn 1.2s ease-out forwards;
        }
        
        .fade-in-delay-1 { animation-delay: 0.3s; }
        .fade-in-delay-2 { animation-delay: 0.6s; }
        .fade-in-delay-3 { animation-delay: 0.9s; }
        .fade-in-delay-4 { animation-delay: 1.2s; }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        .slide-up {
            transform: translateY(30px);
            opacity: 0;
            animation: slideUp 1s ease-out forwards;
        }
        
        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Estilo principal de hero */
        .hero-section {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: var(--gradient-bg);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x bottom;
            opacity: 0.1;
        }

        .logo-container {
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 10;
        }

        .logo-tecamac {
            height: 80px;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .logo-tecamac:hover {
            transform: scale(1.05);
            filter: brightness(0) invert(1) drop-shadow(0 5px 15px rgba(255,255,255,0.3));
        }

        .main-content {
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            min-height: 80vh;
        }

        .content-text {
            text-align: left;
            color: white;
        }

        .president-intro {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .president-name {
            font-weight: 700;
            color: #FFD700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .main-slogan {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 2rem 0;
            line-height: 1.2;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
        }

        .slogan-highlight {
            font-style: italic;
            color: #FFD700;
            position: relative;
        }

        .slogan-subtitle {
            font-size: 2.2rem;
            font-weight: 500;
            color: rgba(255,255,255,0.95);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .president-image {
            max-width: 100%;
            height: auto;
            max-height: 600px;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
        }

        .president-image:hover {
            transform: scale(1.02);
        }

        .cta-button {
            display: inline-block;
            background: rgba(255,255,255,0.95);
            color: var(--pantone-7420);
            padding: 1.2rem 3rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .cta-button:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            color: var(--pantone-1805);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }
            
            .content-text {
                text-align: center;
                order: 2;
            }
            
            .image-container {
                order: 1;
            }
            
            .main-slogan {
                font-size: 2.5rem;
            }
            
            .slogan-subtitle {
                font-size: 1.8rem;
            }
            
            .logo-container {
                top: 1rem;
                left: 1rem;
            }
            
            .logo-tecamac {
                height: 60px;
            }
        }
        
        /* Animaciones de elementos flotantes */
        .float {
            animation: float 3s ease-in-out infinite;
        }
        
        .float-delayed {
            animation: float 3s ease-in-out infinite 1.5s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        /* Botones animados con colores Pantone */
        .btn-primary {
            background: linear-gradient(135deg, #8B1538 0%, #C8102E 100%);
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(139, 21, 56, 0.4);
        }
        
        /* Cards con hover effect */
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }
        
        /* Gradientes animados con colores Pantone Gobierno de México */
        .gradient-bg {
            background: linear-gradient(-45deg, #8B1538, #C8102E, #A01C3A, #B91C3C);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Efectos de pulso */
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }
        
        /* Efectos de glow */
        .glow {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            }
            to {
                box-shadow: 0 0 30px rgba(102, 126, 234, 0.6);
            }
        }
        
        /* Navbar scroll effect */
        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Sección Hero Principal -->
    <section class="hero-section">
        <!-- Logo de Tecámac -->
        <div class="logo-container fade-in">
            <img src="{{ asset('images/AYUNTO 2026 H.png') }}" alt="Tecámac 2026" class="logo-tecamac">
        </div>

        <!-- Contenido Principal -->
        <div class="main-content">
            <!-- Texto Principal -->
            <div class="content-text">
                <div class="fade-in fade-in-delay-1">
                    <p class="president-intro">
                        La Presidenta Municipal, <span class="president-name">Rosi Wong</span>, te invita a definir juntos el presupuesto 2026. ¡Participa! Porque:
                    </p>
                </div>

                <div class="fade-in fade-in-delay-2">
                    <h1 class="main-slogan">
                        Aquí, la que <span class="slogan-highlight">manda</span>
                    </h1>
                    <p class="slogan-subtitle">es la gente</p>
                </div>

                <div class="fade-in fade-in-delay-3">
                    <a href="{{ route('encuesta.create') }}" class="cta-button">
                        <i class="fas fa-vote-yea mr-3"></i>
                        ¡Participa Ahora!
                    </a>
                </div>

                <!-- Botones adicionales -->
                <div class="fade-in fade-in-delay-4" style="margin-top: 1rem;">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" 
                           class="cta-button" style="margin-left: 1rem; background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-chart-pie mr-2"></i>Panel Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="cta-button" style="margin-left: 1rem; background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Imagen de la Presidenta -->
            <div class="image-container fade-in fade-in-delay-2">
                <img src="{{ asset('images/rosy.png') }}" alt="Presidenta Rosi Wong" class="president-image float-animation">
            </div>
        </div>
    </section>

    <!-- Sección de Información -->
    <section id="como-funciona" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">¿Cómo Funciona?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Tu participación es fundamental para construir el Tecámac que todos queremos. Sigue estos sencillos pasos:
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Paso 1 -->
                <div class="feature-card bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-800 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">1. Regístrate</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Completa el formulario con tus datos básicos y selecciona tu colonia para participar.
                    </p>
                </div>

                <!-- Paso 2 -->
                <div class="feature-card bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-800 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">2. Evalúa</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Califica las obras públicas existentes en tu colonia del 1 al 5 según su estado actual.
                    </p>
                </div>

                <!-- Paso 3 -->
                <div class="feature-card bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-800 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">3. Propón</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Comparte tus ideas para nuevas obras públicas que beneficien a tu comunidad.
                    </p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('encuesta.create') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-red-800 to-red-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:from-red-900 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-rocket mr-3"></i>
                    ¡Empezar Mi Participación!
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <img src="{{ asset('images/VOLAR SLOGAN 2026 N.png') }}" alt="Slogan Tecámac 2026" class="h-16 mb-4 filter brightness-0 invert">
                    <p class="text-white/80">
                        Construyendo juntos el futuro de Tecámac a través de la participación ciudadana.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces Útiles</h4>
                    <ul class="space-y-2">
                        <li><a href="#como-funciona" class="text-white/80 hover:text-white transition-colors">¿Cómo funciona?</a></li>
                        <li><a href="{{ route('encuesta.create') }}" class="text-white/80 hover:text-white transition-colors">Participar</a></li>
                        <li><a href="{{ route('login') }}" class="text-white/80 hover:text-white transition-colors">Iniciar Sesión</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                    <p class="text-white/80 mb-2">
                        <i class="fas fa-envelope mr-2"></i>
                        presupuesto@tecamac.gob.mx
                    </p>
                    <p class="text-white/80">
                        <i class="fas fa-phone mr-2"></i>
                        (55) 1234-5678
                    </p>
                </div>
            </div>
            
            <div class="border-t border-white/20 mt-8 pt-8 text-center">
                <p class="text-white/60">
                    © 2026 H. Ayuntamiento de Tecámac. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-white/10 rounded-xl">
                                    <span>🏥 Centros de Salud</span>
                                    <span class="bg-green-400 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aprobado</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/10 rounded-xl">
                                    <span>🏫 Escuelas</span>
                                    <span class="bg-yellow-400 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">En proceso</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/10 rounded-xl">
                                    <span>🚧 Pavimentación</span>
                                    <span class="bg-blue-400 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">Propuesto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cómo Funciona Section -->
    <section id="como-funciona" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 slide-up">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                    ¿Cómo Funciona?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Participar en el presupuesto participativo es fácil y directo. 
                    Sigue estos simples pasos para hacer que tu voz sea escuchada.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Paso 1 -->
                <div class="feature-card rounded-2xl p-8 text-center slide-up" style="animation-delay: 0.1s">
                    <div class="w-20 h-20 bg-gradient-to-r from-red-800 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 float">
                        <i class="fas fa-user-plus text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">1. Regístrate</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Completa tus datos sociodemográficos y selecciona tu colonia. 
                        Tu información es segura y confidencial.
                    </p>
                </div>
                
                <!-- Paso 2 -->
                <div class="feature-card rounded-2xl p-8 text-center slide-up" style="animation-delay: 0.2s">
                    <div class="w-20 h-20 bg-gradient-to-r from-red-700 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6 float-delayed">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">2. Califica</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Evalúa las obras públicas existentes en tu colonia y 
                        comparte tu opinión sobre su estado actual.
                    </p>
                </div>
                
                <!-- Paso 3 -->
                <div class="feature-card rounded-2xl p-8 text-center slide-up" style="animation-delay: 0.3s">
                    <div class="w-20 h-20 bg-gradient-to-r from-yellow-600 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 float">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">3. Propón</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Envía hasta 3 propuestas de mejoras para tu comunidad. 
                        ¡Tu idea puede convertirse en realidad!
                    </p>
        </div>
    </footer>

    <script>
        // Efectos de scroll suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Efectos de partículas flotantes
        document.addEventListener('DOMContentLoaded', function() {
            const hero = document.querySelector('.hero-section');
            
            // Crear partículas flotantes
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute w-2 h-2 bg-white/20 rounded-full';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 3 + 's';
                particle.style.animation = 'float 6s ease-in-out infinite';
                hero.appendChild(particle);
            }
        });
    </script>
</body>
</html>