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

<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card login-card shadow-lg">
                        <div class="card-body p-5">
                            <div class="login-header">
                                <i class="bi bi-truck-front"></i>
                                <h3 class="mb-2">Sistem Informasi Angkot</h3>
                                <p class="text-muted">Silakan login untuk melanjutkan</p>
                            </div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" class="needs-validation" novalidate>
                                <div class="mb-4">
                                    <label for="username" class="form-label">
                                        <i class="bi bi-person"></i> Username
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="username"
                                        name="username" required autofocus placeholder="Masukkan username">
                                    <div class="invalid-feedback">
                                        Username harus diisi
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock"></i> Password
                                    </label>
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        name="password" required placeholder="Masukkan password">
                                    <div class="invalid-feedback">
                                        Password harus diisi
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right"></i> Login
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    Default login:<br>
                                    <strong>admin / admin123</strong> atau <strong>staff1 / admin123</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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