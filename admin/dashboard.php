<?php
/**
 * Admin Dashboard
 * Sistem Informasi Angkot
 */

// Start session and check authentication
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin role
require_admin();

$page_title = 'Dashboard Admin';

// Get statistics
$stats = [];

// Total supir aktif
$query = "SELECT COUNT(*) as total FROM supir WHERE status = 'aktif'";
$result = $conn->query($query);
$stats['supir_aktif'] = $result->fetch_assoc()['total'];

// Total mobil operasional
$query = "SELECT COUNT(*) as total FROM mobil WHERE status = 'operasional'";
$result = $conn->query($query);
$stats['mobil_operasional'] = $result->fetch_assoc()['total'];

// Total setoran hari ini
$query = "SELECT COALESCE(SUM(jumlah_setoran), 0) as total FROM setoran WHERE tanggal_setoran = CURDATE() AND status = 'dikonfirmasi'";
$result = $conn->query($query);
$stats['setoran_hari_ini'] = $result->fetch_assoc()['total'];

// Setoran pending konfirmasi
$query = "SELECT COUNT(*) as total FROM setoran WHERE status = 'pending'";
$result = $conn->query($query);
$stats['setoran_pending'] = $result->fetch_assoc()['total'];

// Absensi hari ini
$query = "SELECT COUNT(*) as total FROM absensi WHERE tanggal = CURDATE()";
$result = $conn->query($query);
$stats['absensi_hari_ini'] = $result->fetch_assoc()['total'];

// Total setoran bulan ini
$query = "SELECT COALESCE(SUM(jumlah_setoran), 0) as total FROM setoran WHERE MONTH(tanggal_setoran) = MONTH(CURDATE()) AND YEAR(tanggal_setoran) = YEAR(CURDATE()) AND status = 'dikonfirmasi'";
$result = $conn->query($query);
$stats['setoran_bulan_ini'] = $result->fetch_assoc()['total'];

// Total pengeluaran servis bulan ini
$query = "SELECT COALESCE(SUM(biaya), 0) as total FROM servis WHERE MONTH(tanggal_servis) = MONTH(CURDATE()) AND YEAR(tanggal_servis) = YEAR(CURDATE())";
$result = $conn->query($query);
$stats['servis_bulan_ini'] = $result->fetch_assoc()['total'];

// Pendapatan bersih bulan ini
$stats['pendapatan_bersih'] = $stats['setoran_bulan_ini'] - $stats['servis_bulan_ini'];

// Recent activities - Setoran pending
$query = "SELECT s.*, sp.nama_supir, m.no_polisi, u.nama_lengkap as input_by 
          FROM setoran s
          JOIN supir sp ON s.id_supir = sp.id_supir
          JOIN mobil m ON s.id_mobil = m.id_mobil
          JOIN users u ON s.id_user_input = u.id_user
          WHERE s.status = 'pending'
          ORDER BY s.created_at DESC
          LIMIT 5";
$setoran_pending = $conn->query($query);

// Recent absensi
$query = "SELECT a.*, sp.nama_supir, m.no_polisi 
          FROM absensi a
          JOIN supir sp ON a.id_supir = sp.id_supir
          JOIN mobil m ON a.id_mobil = m.id_mobil
          WHERE a.tanggal = CURDATE()
          ORDER BY a.created_at DESC
          LIMIT 5";
$recent_absensi = $conn->query($query);

include '../includes/header.php';
?>


<!-- Welcome Section -->
<div class="welcome-section">
    <h2><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
    <p>Selamat datang,
        <?php echo get_user_fullname(); ?>! |
        <?php echo format_tanggal(date('Y-m-d'), true); ?>
    </p>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card stat-success">
            <div class="card-body position-relative">
                <div class="stat-label">Supir Aktif</div>
                <div class="stat-value">
                    <?php echo $stats['supir_aktif']; ?>
                </div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card stat-info">
            <div class="card-body position-relative">
                <div class="stat-label">Mobil Operasional</div>
                <div class="stat-value">
                    <?php echo $stats['mobil_operasional']; ?>
                </div>
                <i class="bi bi-truck stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card stat-success">
            <div class="card-body position-relative">
                <div class="stat-label">Setoran Hari Ini</div>
                <div class="stat-value">
                    <?php echo format_rupiah($stats['setoran_hari_ini']); ?>
                </div>
                <i class="bi bi-cash-stack stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card stat-warning">
            <div class="card-body position-relative">
                <div class="stat-label">Pending Konfirmasi</div>
                <div class="stat-value">
                    <?php echo $stats['setoran_pending']; ?>
                </div>
                <i class="bi bi-exclamation-triangle stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-label">Total Setoran Bulan Ini</div>
                <div class="stat-value text-success">
                    <?php echo format_rupiah($stats['setoran_bulan_ini']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-label">Total Servis Bulan Ini</div>
                <div class="stat-value text-danger">
                    <?php echo format_rupiah($stats['servis_bulan_ini']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card <?php echo $stats['pendapatan_bersih'] >= 0 ? 'stat-success' : 'stat-danger'; ?>">
            <div class="card-body">
                <div class="stat-label">Pendapatan Bersih Bulan Ini</div>
                <div class="stat-value">
                    <?php echo format_rupiah($stats['pendapatan_bersih']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Setoran Pending Konfirmasi
            </div>
            <div class="card-body">
                <?php if ($setoran_pending->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Supir</th>
                                    <th>Jumlah</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $setoran_pending->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php echo format_tanggal($row['tanggal_setoran']); ?>
                                        </td>
                                        <td>
                                            <?php echo $row['nama_supir']; ?><br><small class="text-muted">
                                                <?php echo $row['no_polisi']; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php echo format_rupiah($row['jumlah_setoran']); ?>
                                        </td>
                                        <td>
                                            <a href="setoran/index.php?id=<?php echo $row['id_setoran']; ?>"
                                                class="btn btn-sm btn-success">
                                                Konfirmasi
                                            </a>
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
                    <p class="text-muted text-center mb-0">Tidak ada setoran pending</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
</div>

<?php include '../includes/footer.php'; ?>