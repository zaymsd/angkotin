<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Selamat Datang - Angkotin</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            /* Dark Blue Theme - Biru Dongker */
            --primary-dark: #0a1628;
            --primary-blue: #1e3a5f;
            --accent-blue: #3b82f6;
            --accent-cyan: #06b6d4;
            --accent-orange: #f97316;
            --text-light: #f1f5f9;
            --text-muted: #94a3b8;
            --glass-bg: rgba(30, 58, 95, 0.3);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-light);
            overflow-x: hidden;
        }

        /* ==================== NAVBAR ==================== */
        .navbar-custom {
            background: transparent;
            padding: 1rem 0;
            transition: all 0.4s ease;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-custom.scrolled {
            background: rgba(10, 22, 40, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .navbar-brand-logo {
            height: 45px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(6, 182, 212, 0.3));
            transition: all 0.3s ease;
        }

        .navbar-brand:hover .navbar-brand-logo {
            transform: scale(1.05);
            filter: drop-shadow(0 4px 12px rgba(6, 182, 212, 0.5));
        }

        .navbar-brand-text {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent-cyan) !important;
            background: var(--glass-bg);
        }

        .nav-link.active {
            color: var(--accent-cyan) !important;
        }

        .btn-nav-login {
            background: linear-gradient(135deg, var(--accent-orange), #fb923c);
            color: white !important;
            padding: 0.6rem 1.5rem !important;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-nav-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
        }

        /* ==================== HERO SECTION ==================== */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }

        /* Animated Background Elements */
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(249, 115, 22, 0.1) 0%, transparent 40%);
            animation: backgroundPulse 15s ease-in-out infinite;
        }

        @keyframes backgroundPulse {

            0%,
            100% {
                transform: scale(1) rotate(0deg);
            }

            50% {
                transform: scale(1.1) rotate(5deg);
            }
        }

        /* Floating Particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent-cyan);
            border-radius: 50%;
            opacity: 0.5;
            animation: float 20s infinite;
        }

        .particle:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
            animation-duration: 25s;
        }

        .particle:nth-child(2) {
            left: 20%;
            animation-delay: 2s;
            animation-duration: 20s;
        }

        .particle:nth-child(3) {
            left: 30%;
            animation-delay: 4s;
            animation-duration: 28s;
        }

        .particle:nth-child(4) {
            left: 40%;
            animation-delay: 6s;
            animation-duration: 22s;
        }

        .particle:nth-child(5) {
            left: 50%;
            animation-delay: 8s;
            animation-duration: 26s;
        }

        .particle:nth-child(6) {
            left: 60%;
            animation-delay: 10s;
            animation-duration: 24s;
        }

        .particle:nth-child(7) {
            left: 70%;
            animation-delay: 12s;
            animation-duration: 21s;
        }

        .particle:nth-child(8) {
            left: 80%;
            animation-delay: 14s;
            animation-duration: 27s;
        }

        .particle:nth-child(9) {
            left: 90%;
            animation-delay: 16s;
            animation-duration: 23s;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 0.5;
            }

            90% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--accent-cyan);
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--text-light) 0%, var(--accent-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--accent-orange), #fb923c);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(249, 115, 22, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            color: var(--text-light);
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: var(--accent-blue);
            color: var(--accent-cyan);
            transform: translateY(-3px);
        }

        .hero-lottie {
            position: relative;
            z-index: 2;
        }

        .lottie-glow {
            filter: drop-shadow(0 0 40px rgba(6, 182, 212, 0.3));
        }

        /* ==================== HERO IMAGE ANIMATIONS ==================== */
        .hero-image-wrapper {
            position: relative;
            z-index: 2;
            perspective: 1000px;
        }

        .hero-image-container {
            position: relative;
            display: inline-block;
            transform-style: preserve-3d;
            transition: transform 0.1s ease-out;
        }

        .hero-image-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.4) 0%, rgba(59, 130, 246, 0.2) 40%, transparent 70%);
            filter: blur(40px);
            animation: pulseGlow 3s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                opacity: 0.6;
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }

        .hero-image-main {
            max-width: 100%;
            height: auto;
            max-height: 450px;
            object-fit: contain;
            animation: floatImage 4s ease-in-out infinite;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.4));
            transition: transform 0.3s ease;
        }

        @keyframes floatImage {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-15px) rotate(1deg);
            }

            50% {
                transform: translateY(-25px) rotate(0deg);
            }

            75% {
                transform: translateY(-15px) rotate(-1deg);
            }
        }

        .hero-image-container:hover .hero-image-main {
            animation-play-state: paused;
            transform: scale(1.05);
        }

        /* Floating Decorative Elements */
        .hero-float-element {
            position: absolute;
            width: 50px;
            height: 50px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--accent-cyan);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 10;
        }

        .float-1 {
            top: 10%;
            right: 5%;
            animation: floatElement1 5s ease-in-out infinite;
        }

        .float-2 {
            bottom: 20%;
            left: 0%;
            animation: floatElement2 6s ease-in-out infinite;
            animation-delay: 1s;
        }

        .float-3 {
            top: 50%;
            right: 0%;
            animation: floatElement3 4s ease-in-out infinite;
            animation-delay: 2s;
        }

        @keyframes floatElement1 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        @keyframes floatElement2 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(-10deg);
            }
        }

        @keyframes floatElement3 {

            0%,
            100% {
                transform: translateX(0) rotate(0deg);
            }

            50% {
                transform: translateX(-10px) rotate(5deg);
            }
        }

        /* ==================== FEATURES SECTION ==================== */
        .features-section {
            padding: 6rem 0;
            background: linear-gradient(180deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
            position: relative;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-cyan));
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.4s ease;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(59, 130, 246, 0.15);
            border-color: var(--accent-blue);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .feature-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-cyan));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 0.75rem;
        }

        .feature-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* ==================== ABOUT SECTION ==================== */
        .about-section {
            padding: 6rem 0;
            background: var(--primary-dark);
            position: relative;
        }

        .about-content {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .about-content::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .about-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-cyan);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .about-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem;
        }

        .about-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .about-list li:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateX(10px);
        }

        .about-list li i {
            color: var(--accent-cyan);
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .about-list li strong {
            color: var(--text-light);
            display: block;
            margin-bottom: 0.25rem;
        }

        .about-list li span {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ==================== FOOTER ==================== */
        .footer {
            background: linear-gradient(180deg, var(--primary-dark) 0%, #050a15 100%);
            padding: 3rem 0 1.5rem;
            border-top: 1px solid var(--glass-border);
        }

        .footer-content {
            text-align: center;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .footer-brand span {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-logo {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(6, 182, 212, 0.4));
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(10, 22, 40, 0.98);
                backdrop-filter: blur(20px);
                padding: 1rem;
                border-radius: 15px;
                margin-top: 1rem;
                border: 1px solid var(--glass-border);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="#beranda">
                <img src="assets/images/logoangkot.png" alt="Angkotin Logo" class="navbar-brand-logo">
                <span class="navbar-brand-text">Angkotin</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link btn-nav-login" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <!-- Floating Particles -->
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                        Kelola Armada Angkot Anda dengan <span style="color: var(--accent-cyan);">Lebih Mudah</span>
                    </h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
                        Platform terpadu untuk monitoring armada, pengelolaan supir, pencatatan setoran,
                        dan laporan keuangan dalam satu sistem terintegrasi.
                    </p>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                        <a href="login.php" class="btn-primary-custom">
                            <i class="bi bi-rocket-takeoff"></i> Mulai Sekarang
                        </a>
                        <a href="#fitur" class="btn-secondary-custom">
                            <i class="bi bi-play-circle"></i> Pelajari Lebih
                        </a>
                    </div>
                </div>
                <!-- Hero Image with Animations -->
                <div class="col-lg-6 hero-image-wrapper text-center" data-aos="zoom-in" data-aos-delay="500">
                    <div class="hero-image-container">
                        <!-- Glow Effect Background -->
                        <div class="hero-image-glow"></div>
                        <!-- Main Image -->
                        <img src="assets/images/header.png" alt="Angkotin Fleet" class="hero-image-main" id="heroImage">
                        <!-- Floating Elements -->
                        <div class="hero-float-element float-1"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="hero-float-element float-2"><i class="bi bi-speedometer2"></i></div>
                        <div class="hero-float-element float-3"><i class="bi bi-fuel-pump-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge">Fitur Unggulan</span>
                <h2 class="section-title">Solusi Lengkap untuk Bisnis Anda</h2>
                <p class="section-subtitle">
                    Sistem yang dirancang khusus untuk memudahkan pengelolaan operasional angkot
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Supir & Armada</h3>
                        <p class="feature-description">
                            Kelola data supir dan armada dengan mudah. Pantau status dan performa setiap unit secara
                            real-time.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Absensi Digital</h3>
                        <p class="feature-description">
                            Sistem absensi digital untuk memantau kehadiran supir dan jadwal operasional dengan akurat.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h3 class="feature-title">Pencatatan Setoran</h3>
                        <p class="feature-description">
                            Catat dan kelola setoran harian dengan sistem yang transparan dan mudah dikontrol.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Servis</h3>
                        <p class="feature-description">
                            Pantau jadwal perawatan dan riwayat servis armada untuk menjaga performa optimal.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3 class="feature-title">Laporan & Analitik</h3>
                        <p class="feature-description">
                            Dapatkan insight mendalam dengan laporan keuangan dan performa yang komprehensif.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Multi-Role Access</h3>
                        <p class="feature-description">
                            Sistem role-based dengan akses berbeda untuk admin dan staff, menjaga keamanan data.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="tentang">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row align-items-center">
                        <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                            <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_yd8fbnml.json"
                                background="transparent" speed="1" class="lottie-glow"
                                style="width: 100%; max-width: 400px; margin: 0 auto;" loop autoplay>
                            </lottie-player>
                        </div>
                        <div class="col-lg-7" data-aos="fade-left">
                            <div class="about-content">
                                <h3 class="about-title">
                                    <i class="bi bi-lightbulb-fill"></i> Mengapa Angkotin?
                                </h3>
                                <ul class="about-list">
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>
                                            <strong>Efisiensi Operasional</strong>
                                            <span>Otomasi proses administrasi dan pelaporan</span>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>
                                            <strong>Transparansi Keuangan</strong>
                                            <span>Monitoring setoran dan pengeluaran real-time</span>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>
                                            <strong>Dashboard Interaktif</strong>
                                            <span>Visualisasi data yang mudah dipahami</span>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>
                                            <strong>Mobile Friendly</strong>
                                            <span>Akses dari perangkat apapun, kapanpun</span>
                                        </div>
                                    </li>
                                </ul>
                                <a href="login.php" class="btn-primary-custom">
                                    <i class="bi bi-rocket-takeoff"></i> Mulai Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="assets/images/logoangkot.png" alt="Angkotin Logo" class="footer-logo">
                    <span>Angkotin</span>
                </div>
                <p class="footer-text">
                    &copy; <?php echo date('Y'); ?> Angkotin - Sistem Informasi Angkot. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for nav links & active state
        document.querySelectorAll('.nav-link[href^="#"]').forEach(link => {
            link.addEventListener('click', function () {
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Update active nav on scroll
        const sections = document.querySelectorAll('section[id]');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.nav-link[href^="#"]').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Hero Image Parallax Tilt Effect
        const heroImageContainer = document.querySelector('.hero-image-container');
        const heroImage = document.getElementById('heroImage');

        if (heroImageContainer && heroImage) {
            heroImageContainer.addEventListener('mousemove', (e) => {
                const rect = heroImageContainer.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;

                heroImageContainer.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            heroImageContainer.addEventListener('mouseleave', () => {
                heroImageContainer.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
            });
        }
    </script>
</body>

</html>