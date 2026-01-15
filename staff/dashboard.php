<?php
/**
 * Staff Dashboard
 * Sistem Informasi Angkot
 */

// Start session and check authentication
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require staff or admin role
require_staff();

$page_title = 'Dashboard Staff';

// Get statistics
$stats = [];

// Absensi hari ini
$query = "SELECT COUNT(*) as total FROM absensi WHERE tanggal = CURDATE()";
$result = $conn->query($query);
$stats['absensi_hari_ini'] = $result->fetch_assoc()['total'];

// Setoran hari ini
$query = "SELECT COUNT(*) as total, COALESCE(SUM(jumlah_setoran), 0) as jumlah FROM setoran WHERE tanggal_setoran = CURDATE()";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$stats['setoran_count'] = $row['total'];
$stats['setoran_jumlah'] = $row['jumlah'];

// Servis bulan ini
$query = "SELECT COUNT(*) as total, COALESCE(SUM(biaya), 0) as jumlah FROM servis WHERE MONTH(tanggal_servis) = MONTH(CURDATE()) AND YEAR(tanggal_servis) = YEAR(CURDATE())";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$stats['servis_count'] = $row['total'];
$stats['servis_jumlah'] = $row['jumlah'];

// Recent activities - Absensi hari ini
$query = "SELECT a.*, sp.nama_supir, m.no_polisi 
          FROM absensi a
          JOIN supir sp ON a.id_supir = sp.id_supir
          JOIN mobil m ON a.id_mobil = m.id_mobil
          WHERE a.tanggal = CURDATE()
          ORDER BY a.created_at DESC
          LIMIT 5";
$recent_absensi = $conn->query($query);

// Recent setoran
$query = "SELECT s.*, sp.nama_supir, m.no_polisi 
          FROM setoran s
          JOIN supir sp ON s.id_supir = sp.id_supir
          JOIN mobil m ON s.id_mobil = m.id_mobil
          WHERE s.tanggal_setoran = CURDATE()
          ORDER BY s.created_at DESC
          LIMIT 5";
$recent_setoran = $conn->query($query);

include '../includes/header.php';
?>

<!-- Welcome Section -->
<div class="welcome-section">
    <h2><i class="bi bi-speedometer2"></i> Dashboard Staff</h2>
    <p>Selamat datang,
        <?php echo get_user_fullname(); ?>! |
        <?php echo format_tanggal(date('Y-m-d'), true); ?>
    </p>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card stat-info">
            <div class="card-body position-relative">
                <div class="stat-label">Absensi Hari Ini</div>
                <div class="stat-value">
                    <?php echo $stats['absensi_hari_ini']; ?>
                </div>
                <i class="bi bi-calendar-check stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card stat-success">
            <div class="card-body position-relative">
                <div class="stat-label">Setoran Hari Ini</div>
                <div class="stat-value">
                    <?php echo format_rupiah($stats['setoran_jumlah']); ?>
                </div>
                <small class="text-muted">
                    <?php echo $stats['setoran_count']; ?> transaksi
                </small>
                <i class="bi bi-cash-stack stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card stat-warning">
            <div class="card-body position-relative">
                <div class="stat-label">Servis Bulan Ini</div>
                <div class="stat-value">
                    <?php echo format_rupiah($stats['servis_jumlah']); ?>
                </div>
                <small class="text-muted">
                    <?php echo $stats['servis_count']; ?> kali servis
                </small>
                <i class="bi bi-tools stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar-check"></i> Absensi Hari Ini
            </div>
            <div class="card-body">
                <?php if ($recent_absensi->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Supir</th>
                                    <th>Mobil</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recent_absensi->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['nama_supir']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['no_polisi']; ?>
                                        </td>
                                        <td>
                                            <?php echo format_waktu($row['jam_masuk']); ?>
                                        </td>
                                        <td>
                                            <?php echo format_waktu($row['jam_pulang']); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-2">
                        <a href="absensi/index.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada absensi hari ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-cash-stack"></i> Setoran Hari Ini
            </div>
            <div class="card-body">
                <?php if ($recent_setoran->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Supir</th>
                                    <th>Mobil</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recent_setoran->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['nama_supir']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['no_polisi']; ?>
                                        </td>
                                        <td>
                                            <?php echo format_rupiah($row['jumlah_setoran']); ?>
                                        </td>
                                        <td>
                                            <?php echo status_badge($row['status']); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-2">
                        <a href="setoran/index.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada setoran hari ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>\r\n\r\n<?php include '../includes/footer.php'; ?>