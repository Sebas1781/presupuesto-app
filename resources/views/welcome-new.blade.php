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
            background: linear-gradient(135deg, #530000 0%, #460000 25%, #380000 75%, #2a0000 100%);
            min-height: 100vh;
        }

        /* Nueva paleta de colores oscuros */
        :root {
            --color-primary: #530000;
            --color-secondary: #460000;
            --color-tertiary: #380000;
            --color-dark: #2a0000;
            --gradient-bg: linear-gradient(135deg, #530000 0%, #460000 25%, #380000 75%, #2a0000 100%);
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

        /* Barra superior muy discreta */
        .top-bar {
            position: absolute;
            top: 10px;
            right: 15px;
            z-index: 20;
            padding: 0.5rem;
        }

        .login-btn {
            background: rgba(255,255,255,0.03);
            color: rgba(255,255,255,0.4);
            padding: 0.3rem 0.7rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
            opacity: 0.6;
        }

        .login-btn:hover {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.7);
            opacity: 1;
            transform: translateY(-1px);
        }

        .logo-container {
            position: absolute;
            top: 3rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .logo-tecamac {
            height: 120px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .logo-tecamac:hover {
            transform: scale(1.02);
            filter: brightness(0) invert(1) drop-shadow(0 8px 25px rgba(255,255,255,0.4));
        }

        .main-content {
            text-align: center;
            max-width: 1200px;
            margin: 8rem auto 0;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            min-height: 70vh;
        }

        .content-text {
            text-align: left;
            color: white;
        }

        .president-intro {
            font-size: 1.5rem;
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
            font-size: 4rem;
            font-weight: 700;
            margin: 2rem 0;
            line-height: 1.2;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
        }

        .slogan-image {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            margin: 2rem 0;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .slogan-image:hover {
            transform: scale(1.02);
            filter: brightness(0) invert(1) drop-shadow(0 10px 30px rgba(255,255,255,0.4));
        }

        .slogan-highlight {
            font-style: italic;
            color: #FFD700;
            position: relative;
        }

        .slogan-subtitle {
            font-size: 2.6rem;
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
            color: var(--color-primary);
            padding: 1.3rem 3.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.3rem;
            margin-top: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .cta-button:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            color: var(--color-secondary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
                margin: 6rem auto 0;
            }

            .content-text {
                text-align: center;
                order: 2;
            }

            .image-container {
                order: 1;
            }

            .main-slogan {
                font-size: 2.8rem;
            }

            .slogan-subtitle {
                font-size: 2rem;
            }

            .logo-container {
                top: 2rem;
                left: 50%;
                transform: translateX(-50%);
            }

            .logo-tecamac {
                height: 80px;
                width: auto;
                max-width: 90vw;
                object-fit: contain;
            }

            .top-bar {
                padding: 0.5rem 1rem;
            }

            .president-intro {
                font-size: 1.2rem;
            }

            .cta-button {
                font-size: 1.1rem;
                padding: 1rem 2.5rem;
            }

            .slogan-image {
                max-height: 120px;
            }

            .president-image {
                max-height: 400px;
            }
        }

        .float-delayed {
            animation: float 3s ease-in-out infinite 1.5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        /* Botones animados con nueva paleta */
        .btn-primary {
            background: linear-gradient(135deg, #530000 0%, #460000 100%);
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
    <!-- Barra Superior Discreta -->
    <div class="top-bar fade-in">
        @auth
            <a href="{{ route('admin.dashboard') }}" class="login-btn">
                <i class="fas fa-chart-pie mr-2"></i>
            </a>
        @else
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt mr-2"></i>
            </a>
        @endauth
    </div>

    <!-- Sección Hero Principal -->
    <section class="hero-section">
        <!-- Logo de Tecámac Centrado -->
        <div class="logo-container fade-in">
            <img src="{{ asset('images/ayunto-2026-h.png') }}" alt="Tecámac 2026" class="logo-tecamac">
        </div>

        <!-- Contenido Principal -->
        <div class="main-content">
            <!-- Texto Principal -->
            <div class="content-text">
                <div class="fade-in fade-in-delay-1">
                    <p class="president-intro">
                        ¡La Presidenta Municipal, <span class="president-name">Rosi Wong</span>, te invita a definir juntos el presupuesto 2026!
                    </p>
                </div>

                <div class="fade-in fade-in-delay-2">
                    <img src="{{ asset('images/volar-slogan-2026-n.png') }}" alt="Aquí, la que manda es la gente" class="slogan-image">
                </div>

                <div class="fade-in fade-in-delay-3">
                    <a href="{{ route('encuesta.create') }}" class="cta-button">
                        <i class="fas fa-vote-yea mr-3"></i>
                        ¡Participe Ahora!
                    </a>
                </div>
            </div>

            <!-- Imagen de la Presidenta -->
            <div class="image-container fade-in fade-in-delay-2">
                <img src="{{ asset('images/rosy.png') }}" alt="Presidenta Rosi Wong" class="president-image float-animation">
            </div>
        </div>
    </section>

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
