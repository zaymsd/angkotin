<?php
/**
 * Common Header Template
 * Sistem Informasi Angkot
 */

// Ensure session is started and auth functions are available
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base path dynamically
$base_path = '';
if (strpos($_SERVER['REQUEST_URI'], '/angkotin') !== false) {
    $base_path = '/angkotin';
}

// Get page title (set in individual pages)
$page_title = isset($page_title) ? $page_title : 'Sistem Informasi Angkot';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo $page_title; ?> - Angkotin
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $base_path; ?>/assets/images/logoangkot.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>/assets/css/style.css">
</head>

<body>
    <?php if (is_logged_in()): ?>
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-brand d-none d-lg-block">
                    <span class="header-page-title"><?php echo $page_title; ?></span>
                </div>
            </div>

            <div class="header-right">
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn-user-dropdown" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span class="user-name"><?php echo get_user_fullname(); ?></span>
                        <span class="badge-role"><?php echo strtoupper(get_user_role()); ?></span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-header">
                                <div class="user-info-dropdown">
                                    <strong><?php echo get_user_fullname(); ?></strong>
                                    <small class="text-muted d-block"><?php echo get_username(); ?></small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo $base_path; ?>/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Include Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="<?php echo is_logged_in() ? 'main-content' : ''; ?>">
        <?php
        // Display flash message if exists
        $flash = get_flash();
        if ($flash):
            $icon = [
                'success' => 'check-circle',
                'error' => 'x-circle',
                'warning' => 'exclamation-triangle',
                'info' => 'info-circle'
            ];
            ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-<?php echo $icon[$flash['type']]; ?>"></i>
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>