<?php
/**
 * Sidebar Navigation Component
 * Sistem Informasi Angkot
 */

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="bi bi-truck"></i>
            <span class="sidebar-brand-text">Angkotin</span>
        </div>
        <button class="sidebar-toggle-btn d-lg-none" id="sidebarToggle">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="sidebar-item <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo $base_path . (is_admin() ? '/admin/dashboard.php' : '/staff/dashboard.php'); ?>"
                    class="sidebar-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if (is_admin()): ?>
                <!-- Admin Only: Master Data -->
                <li class="sidebar-section-title">
                    <span>Master Data</span>
                </li>

                <li class="sidebar-item <?php echo $current_dir == 'supir' ? 'active' : ''; ?>">
                    <a href="<?php echo $base_path; ?>/admin/supir/index.php" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Data Supir</span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo $current_dir == 'mobil' ? 'active' : ''; ?>">
                    <a href="<?php echo $base_path; ?>/admin/mobil/index.php" class="sidebar-link">
                        <i class="bi bi-truck"></i>
                        <span>Data Mobil</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Operational Section -->
            <li class="sidebar-section-title">
                <span>Operasional</span>
            </li>

            <!-- Absensi -->
            <li class="sidebar-item has-submenu <?php echo $current_dir == 'absensi' ? 'active' : ''; ?>">
                <a href="#absensiSubmenu" class="sidebar-link" data-bs-toggle="collapse"
                    aria-expanded="<?php echo $current_dir == 'absensi' ? 'true' : 'false'; ?>">
                    <i class="bi bi-calendar-check"></i>
                    <span>Absensi</span>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul class="sidebar-submenu collapse <?php echo $current_dir == 'absensi' ? 'show' : ''; ?>"
                    id="absensiSubmenu">
                    <li>
                        <a href="<?php echo $base_path . (is_admin() ? '/admin' : '/staff'); ?>/absensi/index.php">
                            <i class="bi bi-list-ul"></i>
                            <span>List Absensi</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $base_path . (is_admin() ? '/admin' : '/staff'); ?>/absensi/input.php">
                            <i class="bi bi-plus-circle"></i>
                            <span>Input Absensi</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Setoran -->
            <li class="sidebar-item has-submenu <?php echo $current_dir == 'setoran' ? 'active' : ''; ?>">
                <a href="#setoranSubmenu" class="sidebar-link" data-bs-toggle="collapse"
                    aria-expanded="<?php echo $current_dir == 'setoran' ? 'true' : 'false'; ?>">
                    <i class="bi bi-cash-stack"></i>
                    <span>Setoran</span>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul class="sidebar-submenu collapse <?php echo $current_dir == 'setoran' ? 'show' : ''; ?>"
                    id="setoranSubmenu">
                    <li>
                        <a href="<?php echo $base_path . (is_admin() ? '/admin' : '/staff'); ?>/setoran/index.php">
                            <i class="bi bi-list-ul"></i>
                            <span>List Setoran</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $base_path . (is_admin() ? '/admin' : '/staff'); ?>/setoran/input.php">
                            <i class="bi bi-plus-circle"></i>
                            <span>Input Setoran</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Servis -->
            <li class="sidebar-item has-submenu <?php echo $current_dir == 'servis' ? 'active' : ''; ?>">
                <a href="#servisSubmenu" class="sidebar-link" data-bs-toggle="collapse"
                    aria-expanded="<?php echo $current_dir == 'servis' ? 'true' : 'false'; ?>">
                    <i class="bi bi-tools"></i>
                    <span>Servis</span>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul class="sidebar-submenu collapse <?php echo $current_dir == 'servis' ? 'show' : ''; ?>"
                    id="servisSubmenu">
                    <li>
                        <a href="<?php echo $base_path . (is_admin() ? '/admin' : '/staff'); ?>/servis/index.php">
                            <i class="bi bi-list-ul"></i>
                            <span>List Servis</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $base_path . (is_admin() ? '/admin' : '/staff'); ?>/servis/input.php">
                            <i class="bi bi-plus-circle"></i>
                            <span>Input Servis</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php if (is_admin()): ?>
                <!-- Admin Only Section -->
                <li class="sidebar-section-title">
                    <span>Administrasi</span>
                </li>

                <!-- Laporan -->
                <li class="sidebar-item has-submenu <?php echo $current_dir == 'laporan' ? 'active' : ''; ?>">
                    <a href="#laporanSubmenu" class="sidebar-link" data-bs-toggle="collapse"
                        aria-expanded="<?php echo $current_dir == 'laporan' ? 'true' : 'false'; ?>">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Laporan</span>
                        <i class="bi bi-chevron-down submenu-icon"></i>
                    </a>
                    <ul class="sidebar-submenu collapse <?php echo $current_dir == 'laporan' ? 'show' : ''; ?>"
                        id="laporanSubmenu">
                        <li>
                            <a href="<?php echo $base_path; ?>/admin/laporan/keuangan.php">
                                <i class="bi bi-currency-dollar"></i>
                                <span>Laporan Keuangan</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $base_path; ?>/admin/laporan/performa.php">
                                <i class="bi bi-graph-up"></i>
                                <span>Performa Armada</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users -->
                <li class="sidebar-item <?php echo $current_dir == 'users' ? 'active' : ''; ?>">
                    <a href="<?php echo $base_path; ?>/admin/users/index.php" class="sidebar-link">
                        <i class="bi bi-people"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user-info">
            <i class="bi bi-person-circle"></i>
            <div class="sidebar-user-details">
                <span class="sidebar-user-name">
                    <?php echo get_user_fullname(); ?>
                </span>
                <span class="sidebar-user-role">
                    <?php echo strtoupper(get_user_role()); ?>
                </span>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>