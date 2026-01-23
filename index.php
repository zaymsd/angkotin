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

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/logoangkot.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#mitra">Mitra</a>
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

    <!-- Mitra Section -->
    <section class="mitra-section" id="mitra">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge">Mitra Kami</span>
                <h2 class="section-title">Berkolaborasi dengan mitra strategis</h2>
            </div>

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div class="col-8 col-md-5 col-lg-4">
                    <div class="mitra-logo-wrapper">
                        <img src="assets/images/PT. Mitra Sinikasih Jaya Logo.jpg" alt="PT. Mitra Sinikasih Jaya"
                            class="mitra-logo">
                    </div>
                    <p class="text-center mt-3 text-white fw-bold">PT. Mitra Sinikasih Jaya</p>
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