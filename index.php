<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Selamat Datang - Angkotin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-blue: #004e89;
            --accent-orange: #ff6b35;
            --dark-blue: #1A252F;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
            color: white;
            display: flex;
            align-items: center;
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
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>') repeat;
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            animation: fadeInUp 1.2s ease;
        }

        .hero-description {
            font-size: 1.1rem;
            margin-bottom: 3rem;
            opacity: 0.85;
            max-width: 600px;
            animation: fadeInUp 1.4s ease;
        }

        .hero-icon {
            font-size: 8rem;
            color: var(--accent-orange);
            margin-bottom: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        .btn-login {
            padding: 1rem 3rem;
            font-size: 1.2rem;
            font-weight: 600;
            background-color: var(--accent-orange);
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            animation: fadeInUp 1.6s ease;
            text-decoration: none;
            color: white;
            display: inline-block;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
            background-color: #ff5722;
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background-color: #f9fafb;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e5e7eb;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 3.5rem;
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark-blue);
        }

        .feature-description {
            color: #6B7280;
            line-height: 1.6;
        }

        /* About Section */
        .about-section {
            padding: 5rem 0;
            background: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: #6B7280;
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-icon {
                font-size: 5rem;
            }

            .btn-login {
                padding: 0.8rem 2rem;
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 hero-content text-center">
                    <div class="hero-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h1 class="hero-title">Sistem Informasi Angkot</h1>
                    <p class="hero-subtitle">Angkotin - Solusi Manajemen Transportasi Angkot Modern</p>
                    <p class="hero-description mx-auto">
                        Platform terpadu untuk mengelola operasional angkutan kota dengan efisien.
                        Monitoring armada, pengelolaan supir, pencatatan setoran, dan laporan keuangan dalam satu
                        sistem.
                    </p>
                    <a href="login.php" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Panel
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan</h2>
            <p class="section-subtitle">
                Sistem yang dirancang khusus untuk memudahkan pengelolaan operasional angkot Anda
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Supir & Armada</h3>
                        <p class="feature-description">
                            Kelola data supir dan armada dengan mudah. Pantau status, riwayat, dan performa setiap unit
                            secara real-time.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Absensi Digital</h3>
                        <p class="feature-description">
                            Sistem absensi digital untuk memantau kehadiran supir dan jadwal operasional dengan akurat.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h3 class="feature-title">Pencatatan Setoran</h3>
                        <p class="feature-description">
                            Catat dan kelola setoran harian dengan sistem yang transparan dan mudah dikontrol.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Servis</h3>
                        <p class="feature-description">
                            Pantau jadwal perawatan dan riwayat servis armada untuk menjaga performa optimal kendaraan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3 class="feature-title">Laporan & Analitik</h3>
                        <p class="feature-description">
                            Dapatkan insight mendalam dengan laporan keuangan dan performa armada yang komprehensif.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
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
    <section class="about-section">
        <div class="container">
            <h2 class="section-title">Tentang Angkotin</h2>
            <p class="section-subtitle">
                Angkotin adalah sistem informasi manajemen yang dikembangkan khusus untuk membantu operator angkutan
                kota
                dalam mengelola bisnis mereka secara lebih efisien dan professional.
            </p>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-3" style="color: var(--primary-blue);">
                                <i class="bi bi-lightbulb-fill me-2"></i> Mengapa Angkotin?
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Efisiensi Operasional:</strong> Otomasi proses administrasi dan pelaporan
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Transparansi Keuangan:</strong> Monitoring setoran dan pengeluaran real-time
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Dashboard Interaktif:</strong> Visualisasi data yang mudah dipahami
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Mobile Friendly:</strong> Akses dari perangkat apapun, kapanpun
                                </li>
                            </ul>

                            <div class="text-center mt-4">
                                <a href="login.php" class="btn btn-lg"
                                    style="background-color: var(--primary-blue); color: white; padding: 1rem 2.5rem; border-radius: 50px;">
                                    <i class="bi bi-rocket-takeoff me-2"></i> Mulai Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4" style="background-color: var(--dark-blue); color: white;">
        <div class="container">
            <p class="mb-0">
                &copy; <?php echo date('Y'); ?> Angkotin - Sistem Informasi Angkot
            </p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>