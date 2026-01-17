<?php
/**
 * Login Page
 * Sistem Informasi Angkot
 */

// Start session
session_start();

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (is_logged_in()) {
    if (is_admin()) {
        redirect('admin/dashboard.php');
    } else {
        redirect('staff/dashboard.php');
    }
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi.';
    } else {
        $result = login_user($conn, $username, $password);

        if ($result['success']) {
            // Check if there's a redirect URL
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                redirect($redirect_url);
            } else {
                // Default redirect based on role
                if ($result['role'] === 'admin') {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('staff/dashboard.php');
                }
            }
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = 'Login';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $page_title; ?> - Angkotin
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-page-body">
    <div class="login-wrapper">
        <div class="row g-0 login-floating-card">
            <!-- Left Side: Branding & Illustration -->
            <div class="col-lg-6 login-left-side">
                <div class="login-illustration">
                    <div class="lottie-container">
                        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_zw0djhar.json"
                            background="transparent" speed="1" loop autoplay></lottie-player>
                    </div>
                    <h1>Angkotin</h1>
                    <p>Sistem Informasi Angkutan Kota Terintegrasi. Solusi cerdas untuk manajemen transportasi masa
                        depan.</p>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-lg-6 login-right-side">
                <div class="login-form-container">
                    <div class="login-title-section">
                        <h3>Selamat Datang!</h3>
                        <p class="text-muted">Silakan login untuk memulai sesi Anda</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <i class="bi bi-exclamation-circle"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="login-input-group">
                            <label for="username">Username</label>
                            <div class="input-wrapper">
                                <input type="text" class="form-control login-input" id="username" name="username"
                                    required autofocus placeholder="Masukkan username">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="invalid-feedback">
                                Username harus diisi
                            </div>
                        </div>

                        <div class="login-input-group">
                            <label for="password">Password</label>
                            <div class="input-wrapper">
                                <input type="password" class="form-control login-input" id="password" name="password"
                                    required placeholder="Masukkan password">
                                <i class="bi bi-lock"></i>
                            </div>
                            <div class="invalid-feedback">
                                Password harus diisi
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-login">
                                MASUK SEKARANG
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>