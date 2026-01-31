<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto Participativo 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        /* Animaciones de entrada */
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
        }
        
        .fade-in-delay-1 { animation-delay: 0.2s; }
        .fade-in-delay-2 { animation-delay: 0.4s; }
        .fade-in-delay-3 { animation-delay: 0.6s; }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        .slide-up {
            transform: translateY(50px);
            opacity: 0;
            animation: slideUp 0.8s ease-out forwards;
        }
        
        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
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
    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 fade-in">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-800 to-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-vote-yea text-white text-lg"></i>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">Presupuesto Participativo 2026</h1>
                </div>
                
                <div class="hidden md:flex items-center space-x-6 fade-in fade-in-delay-1">
                    <a href="#inicio" class="text-gray-700 hover:text-red-800 transition-colors">Inicio</a>
                    <a href="#como-funciona" class="text-gray-700 hover:text-red-800 transition-colors">¿Cómo funciona?</a>
                    <a href="#participar" class="text-gray-700 hover:text-red-800 transition-colors">Participar</a>
                </div>
                
                <div class="fade-in fade-in-delay-2">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-red-800 text-white px-6 py-2 rounded-full hover:bg-red-900 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-chart-pie mr-2"></i>Panel Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-red-800 hover:text-red-900 transition-colors px-4 py-2">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="gradient-bg min-h-screen flex items-center relative overflow-hidden">
        <!-- Elementos decorativos flotantes -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full float"></div>
            <div class="absolute top-40 right-20 w-32 h-32 bg-white/5 rounded-full float-delayed"></div>
            <div class="absolute bottom-20 left-20 w-16 h-16 bg-white/10 rounded-full float"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Contenido principal -->
                <div class="text-white">
                    <div class="fade-in">
                        <h1 class="text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                            Tu Voz
                            <span class="block text-yellow-300">Construye</span>
                            <span class="block">el Futuro</span>
                        </h1>
                    </div>
                    
                    <div class="fade-in fade-in-delay-1">
                        <p class="text-xl lg:text-2xl mb-8 text-white/90 leading-relaxed">
                            Únete al <strong>Presupuesto Participativo 2026</strong>. 
                            Decide qué obras públicas son prioritarias para tu comunidad 
                            y construyamos juntos un mejor mañana. 🏗️✨
                        </p>
                    </div>
                    
                    <div class="fade-in fade-in-delay-2">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('encuesta.create') }}" 
                               class="btn-primary text-white px-8 py-4 rounded-full text-lg font-semibold inline-flex items-center justify-center group">
                                <i class="fas fa-rocket mr-3 group-hover:animate-bounce"></i>
                                ¡Participar Ahora!
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                            </a>
                            
                            <button onclick="document.getElementById('como-funciona').scrollIntoView({behavior: 'smooth'})"
                                    class="border-2 border-white/50 text-white px-8 py-4 rounded-full text-lg hover:bg-white/10 transition-all duration-300">
                                <i class="fas fa-info-circle mr-3"></i>
                                Más Información
                            </button>
                        </div>
                    </div>
                    
                    <!-- Stats animados -->
                    <div class="fade-in fade-in-delay-3 mt-12">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2 pulse">1,250+</div>
                                <div class="text-white/80 text-sm">Propuestas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2 pulse" style="animation-delay: 0.5s">85%</div>
                                <div class="text-white/80 text-sm">Participación</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2 pulse" style="animation-delay: 1s">120</div>
                                <div class="text-white/80 text-sm">Colonias</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ilustración -->
                <div class="fade-in fade-in-delay-2">
                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 glow">
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
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section id="participar" class="py-20 gradient-bg">
        <div class="container mx-auto px-6 text-center">
            <div class="slide-up">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    ¡Es tu momento de brillar! ✨
                </h2>
                <p class="text-xl text-white/90 mb-12 max-w-3xl mx-auto">
                    Cada voz importa, cada opinión cuenta. Únete a los miles de ciudadanos 
                    que ya están construyendo el futuro de nuestra comunidad.
                </p>
                
                <a href="{{ route('encuesta.create') }}" 
                   class="btn-primary text-white px-12 py-6 rounded-full text-xl font-bold inline-flex items-center group">
                    <i class="fas fa-rocket mr-4 group-hover:animate-bounce text-2xl"></i>
                    Comenzar Mi Encuesta
                    <i class="fas fa-arrow-right ml-4 group-hover:translate-x-2 transition-transform text-2xl"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Presupuesto Participativo 2026</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Construyendo juntos un futuro mejor para nuestra comunidad 
                        a través de la participación ciudadana activa.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#inicio" class="text-gray-400 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="#como-funciona" class="text-gray-400 hover:text-white transition-colors">¿Cómo funciona?</a></li>
                        <li><a href="{{ route('encuesta.create') }}" class="text-gray-400 hover:text-white transition-colors">Participar</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">Contacto</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-purple-400"></i>
                            <span class="text-gray-400">info@presupuesto2026.gob.mx</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-3 text-purple-400"></i>
                            <span class="text-gray-400">01 800 PARTICIPA</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2026 Presupuesto Participativo. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Smooth scroll for anchor links
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

        // Add some interactive particles
        document.addEventListener('DOMContentLoaded', function() {
            // Create floating particles
            const hero = document.querySelector('#inicio');
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute w-2 h-2 bg-white/20 rounded-full';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 3 + 's';
                particle.style.animation = 'float 4s ease-in-out infinite';
                hero.appendChild(particle);
            }
        });
    </script>
</body>
</html>