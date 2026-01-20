<?php
/**
 * Laporan Performa Armada - Fleet Performance Reports
 * Admin Only (UC-7)
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Performa Armada';

// Filter parameters
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('m');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

// Build date range
$tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));

// Get performance per mobil
$query_mobil = "SELECT 
    m.id_mobil,
    m.no_polisi,
    m.merk,
    m.tahun_pembuatan,
    m.status,
    COUNT(DISTINCT a.id_absensi) as total_hari_operasi,
    COALESCE(SUM(CASE WHEN st.status = 'dikonfirmasi' THEN st.jumlah_setoran ELSE 0 END), 0) as total_setoran,
    COALESCE((SELECT SUM(sv.biaya) FROM servis sv WHERE sv.id_mobil = m.id_mobil AND sv.tanggal_servis BETWEEN ? AND ?), 0) as total_biaya_servis,
    COUNT(DISTINCT sv2.id_servis) as total_servis
FROM mobil m
LEFT JOIN absensi a ON m.id_mobil = a.id_mobil AND a.tanggal BETWEEN ? AND ?
LEFT JOIN setoran st ON m.id_mobil = st.id_mobil AND st.tanggal_setoran BETWEEN ? AND ?
LEFT JOIN servis sv2 ON m.id_mobil = sv2.id_mobil AND sv2.tanggal_servis BETWEEN ? AND ?
GROUP BY m.id_mobil
ORDER BY total_setoran DESC";

$stmt = $conn->prepare($query_mobil);
$stmt->bind_param("ssssssss", $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$performa_mobil = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get performance per supir
$query_supir = "SELECT 
    s.id_supir,
    s.nama_supir,
    s.no_sim,
    s.status,
    COUNT(DISTINCT a.id_absensi) as total_hari_kerja,
    COALESCE(SUM(CASE WHEN st.status = 'dikonfirmasi' THEN st.jumlah_setoran ELSE 0 END), 0) as total_setoran,
    COALESCE(AVG(CASE WHEN st.status = 'dikonfirmasi' THEN st.jumlah_setoran ELSE NULL END), 0) as rata_setoran
FROM supir s
LEFT JOIN absensi a ON s.id_supir = a.id_supir AND a.tanggal BETWEEN ? AND ?
LEFT JOIN setoran st ON s.id_supir = st.id_supir AND st.tanggal_setoran BETWEEN ? AND ?
GROUP BY s.id_supir
ORDER BY total_setoran DESC";

$stmt = $conn->prepare($query_supir);
$stmt->bind_param("ssss", $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$performa_supir = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate summary stats
$total_armada = count($performa_mobil);
$armada_operasional = 0;
$total_pendapatan = 0;
$total_pengeluaran = 0;

foreach ($performa_mobil as $mobil) {
    if ($mobil['status'] === 'operasional')
        $armada_operasional++;
    $total_pendapatan += $mobil['total_setoran'];
    $total_pengeluaran += $mobil['total_biaya_servis'];
}

// Prepare chart data for mobil
$mobil_labels = [];
$mobil_setoran = [];
$mobil_servis = [];
foreach ($performa_mobil as $mobil) {
    $mobil_labels[] = $mobil['no_polisi'];
    $mobil_setoran[] = (int) $mobil['total_setoran'];
    $mobil_servis[] = (int) $mobil['total_biaya_servis'];
}

// Prepare chart data for supir
$supir_labels = [];
$supir_setoran = [];
foreach ($performa_supir as $supir) {
    if ($supir['total_setoran'] > 0) {
        $supir_labels[] = $supir['nama_supir'];
        $supir_setoran[] = (int) $supir['total_setoran'];
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-graph-up-arrow"></i> Performa Armada</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="export_performa_pdf.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" class="btn btn-danger"
            target="_blank">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <select class="form-select" name="bulan">
                    <?php
                    $nama_bulan = [
                        '',
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ];
                    for ($i = 1; $i <= 12; $i++):
                        ?>
                        <option value="<?php echo $i; ?>" <?php echo $bulan == $i ? 'selected' : ''; ?>>
                            <?php echo $nama_bulan[$i]; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun</label>
                <select class="form-select" name="tahun">
                    <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                        <option value="<?php echo $i; ?>" <?php echo $tahun == $i ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card stat-info">
            <div class="card-body">
                <div class="stat-label">Total Armada</div>
                <div class="stat-value">
                    <?php echo $total_armada; ?>
                </div>
                <small class="text-muted">
                    <?php echo $armada_operasional; ?> operasional
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-success">
            <div class="card-body">
                <div class="stat-label">Total Supir</div>
                <div class="stat-value">
                    <?php echo count($performa_supir); ?>
                </div>
                <small class="text-muted">Terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-success">
            <div class="card-body">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    <?php echo format_rupiah($total_pendapatan); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-danger">
            <div class="card-body">
                <div class="stat-label">Total Biaya Servis</div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    <?php echo format_rupiah($total_pengeluaran); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-truck"></i> Performa per Mobil
            </div>
            <div class="card-body">
                <canvas id="chartMobil" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-people"></i> Performa Supir (Top 10)
            </div>
            <div class="card-body">
                <canvas id="chartSupir" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detail Tables -->
<div class="row g-4">
    <!-- Performa Mobil -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-table"></i> Detail Performa Mobil -
                <?php echo $nama_bulan[$bulan] . ' ' . $tahun; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>No Polisi</th>
                                <th>Merk</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Hari Operasi</th>
                                <th>Total Setoran</th>
                                <th>Biaya Servis</th>
                                <th>Net</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($performa_mobil as $mobil):
                                $net = $mobil['total_setoran'] - $mobil['total_biaya_servis'];
                                ?>
                                <tr>
                                    <td><strong>
                                            <?php echo $mobil['no_polisi']; ?>
                                        </strong></td>
                                    <td>
                                        <?php echo $mobil['merk']; ?>
                                    </td>
                                    <td>
                                        <?php echo $mobil['tahun_pembuatan']; ?>
                                    </td>
                                    <td>
                                        <?php echo status_badge($mobil['status']); ?>
                                    </td>
                                    <td>
                                        <?php echo $mobil['total_hari_operasi']; ?> hari
                                    </td>
                                    <td class="text-success">
                                        <?php echo format_rupiah($mobil['total_setoran']); ?>
                                    </td>
                                    <td class="text-danger">
                                        <?php echo format_rupiah($mobil['total_biaya_servis']); ?>
                                    </td>
                                    <td class="<?php echo $net >= 0 ? 'text-success' : 'text-danger'; ?> fw-bold">
                                        <?php echo format_rupiah($net); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Performa Supir -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-table"></i> Detail Performa Supir -
                <?php echo $nama_bulan[$bulan] . ' ' . $tahun; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>Nama Supir</th>
                                <th>No SIM</th>
                                <th>Status</th>
                                <th>Hari Kerja</th>
                                <th>Total Setoran</th>
                                <th>Rata-rata/Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($performa_supir as $supir): ?>
                                <tr>
                                    <td><strong>
                                            <?php echo $supir['nama_supir']; ?>
                                        </strong></td>
                                    <td>
                                        <?php echo $supir['no_sim']; ?>
                                    </td>
                                    <td>
                                        <?php echo status_badge($supir['status']); ?>
                                    </td>
                                    <td>
                                        <?php echo $supir['total_hari_kerja']; ?> hari
                                    </td>
                                    <td class="text-success">
                                        <?php echo format_rupiah($supir['total_setoran']); ?>
                                    </td>
                                    <td>
                                        <?php echo format_rupiah($supir['rata_setoran']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart Mobil
        const ctxMobil = document.getElementById('chartMobil').getContext('2d');
        new Chart(ctxMobil, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($mobil_labels); ?>,
                datasets: [
                    {
                        label: 'Setoran',
                        data: <?php echo json_encode($mobil_setoran); ?>,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Biaya Servis',
                        data: <?php echo json_encode($mobil_servis); ?>,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                            }
                        }
                    }
                }
            }
        });

        // Chart Supir (Pie/Doughnut)
        const ctxSupir = document.getElementById('chartSupir').getContext('2d');
        new Chart(ctxSupir, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_slice($supir_labels, 0, 10)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_slice($supir_setoran, 0, 10)); ?>,
                    backgroundColor: [
                        '#004e89', '#ff6b35', '#198754', '#0dcaf0', '#ffc107',
                        '#6f42c1', '#d63384', '#fd7e14', '#20c997', '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>