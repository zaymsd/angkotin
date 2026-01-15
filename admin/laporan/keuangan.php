<?php
/**
 * Laporan Keuangan - Financial Reports
 * Admin Only (UC-6)
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Laporan Keuangan';

// Filter parameters
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('m');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

// Build date range for the selected month
$tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));

// Get total setoran (income) - only confirmed
$stmt = $conn->prepare("SELECT COALESCE(SUM(jumlah_setoran), 0) as total FROM setoran WHERE tanggal_setoran BETWEEN ? AND ? AND status = 'dikonfirmasi'");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$total_setoran = $stmt->get_result()->fetch_assoc()['total'];

// Get total servis (expenses)
$stmt = $conn->prepare("SELECT COALESCE(SUM(biaya), 0) as total FROM servis WHERE tanggal_servis BETWEEN ? AND ?");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$total_servis = $stmt->get_result()->fetch_assoc()['total'];

// Calculate net income
$pendapatan_bersih = $total_setoran - $total_servis;

// Get daily setoran data for chart
$stmt = $conn->prepare("SELECT DATE(tanggal_setoran) as tanggal, SUM(jumlah_setoran) as total 
                        FROM setoran 
                        WHERE tanggal_setoran BETWEEN ? AND ? AND status = 'dikonfirmasi'
                        GROUP BY DATE(tanggal_setoran)
                        ORDER BY tanggal");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$setoran_harian = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get daily servis data for chart
$stmt = $conn->prepare("SELECT DATE(tanggal_servis) as tanggal, SUM(biaya) as total 
                        FROM servis 
                        WHERE tanggal_servis BETWEEN ? AND ?
                        GROUP BY DATE(tanggal_servis)
                        ORDER BY tanggal");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$servis_harian = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get detail setoran
$stmt = $conn->prepare("SELECT st.*, s.nama_supir, m.no_polisi 
                        FROM setoran st 
                        JOIN supir s ON st.id_supir = s.id_supir 
                        JOIN mobil m ON st.id_mobil = m.id_mobil 
                        WHERE st.tanggal_setoran BETWEEN ? AND ? AND st.status = 'dikonfirmasi'
                        ORDER BY st.tanggal_setoran DESC");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$detail_setoran = $stmt->get_result();

// Get detail servis
$stmt = $conn->prepare("SELECT sv.*, m.no_polisi 
                        FROM servis sv 
                        JOIN mobil m ON sv.id_mobil = m.id_mobil 
                        WHERE sv.tanggal_servis BETWEEN ? AND ?
                        ORDER BY sv.tanggal_servis DESC");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$detail_servis = $stmt->get_result();

// Prepare chart data
$chart_labels = [];
$chart_setoran = [];
$chart_servis = [];

// Create date index for the month
$current = strtotime($tanggal_awal);
$end = strtotime($tanggal_akhir);
while ($current <= $end) {
    $date = date('Y-m-d', $current);
    $chart_labels[] = date('d', $current);
    $chart_setoran[$date] = 0;
    $chart_servis[$date] = 0;
    $current = strtotime('+1 day', $current);
}

// Fill setoran data
foreach ($setoran_harian as $row) {
    $chart_setoran[$row['tanggal']] = (int) $row['total'];
}

// Fill servis data
foreach ($servis_harian as $row) {
    $chart_servis[$row['tanggal']] = (int) $row['total'];
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-file-earmark-bar-graph"></i> Laporan Keuangan</h3>
    </div>
    <div class="col-md-6 text-end">
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
    <div class="col-md-4">
        <div class="card stat-card stat-success">
            <div class="card-body">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value text-success">
                    <?php echo format_rupiah($total_setoran); ?>
                </div>
                <small class="text-muted">Setoran dikonfirmasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card stat-danger">
            <div class="card-body">
                <div class="stat-label">Total Pengeluaran</div>
                <div class="stat-value text-danger">
                    <?php echo format_rupiah($total_servis); ?>
                </div>
                <small class="text-muted">Biaya servis</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card <?php echo $pendapatan_bersih >= 0 ? 'stat-success' : 'stat-danger'; ?>">
            <div class="card-body">
                <div class="stat-label">Pendapatan Bersih</div>
                <div class="stat-value <?php echo $pendapatan_bersih >= 0 ? 'text-success' : 'text-danger'; ?>">
                    <?php echo format_rupiah($pendapatan_bersih); ?>
                </div>
                <small class="text-muted">
                    <?php echo $nama_bulan[$bulan] . ' ' . $tahun; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-graph-up"></i> Grafik Keuangan -
        <?php echo $nama_bulan[$bulan] . ' ' . $tahun; ?>
    </div>
    <div class="card-body">
        <canvas id="chartKeuangan" height="100"></canvas>
    </div>
</div>

<!-- Detail Tables -->
<div class="row g-4">
    <!-- Setoran Detail -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="bi bi-arrow-down-circle"></i> Detail Pendapatan (
                <?php echo $detail_setoran->num_rows; ?> transaksi)
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>Tanggal</th>
                                <th>Supir</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $detail_setoran->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php echo format_tanggal($row['tanggal_setoran']); ?>
                                    </td>
                                    <td>
                                        <?php echo $row['nama_supir']; ?><br><small class="text-muted">
                                            <?php echo $row['no_polisi']; ?>
                                        </small>
                                    </td>
                                    <td class="text-success">
                                        <?php echo format_rupiah($row['jumlah_setoran']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="sticky-bottom bg-light">
                            <tr class="fw-bold">
                                <td colspan="2">Total</td>
                                <td class="text-success">
                                    <?php echo format_rupiah($total_setoran); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Servis Detail -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-arrow-up-circle"></i> Detail Pengeluaran (
                <?php echo $detail_servis->num_rows; ?> servis)
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mobil</th>
                                <th>Jenis</th>
                                <th>Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $detail_servis->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php echo format_tanggal($row['tanggal_servis']); ?>
                                    </td>
                                    <td>
                                        <?php echo $row['no_polisi']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['jenis_servis']; ?>
                                    </td>
                                    <td class="text-danger">
                                        <?php echo format_rupiah($row['biaya']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="sticky-bottom bg-light">
                            <tr class="fw-bold">
                                <td colspan="3">Total</td>
                                <td class="text-danger">
                                    <?php echo format_rupiah($total_servis); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('chartKeuangan').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: <?php echo json_encode(array_values($chart_setoran)); ?>,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                            borderColor: 'rgba(25, 135, 84, 1)',
                                borderWidth: 1
    },
        {
            label: 'Pengeluaran',
            data: <?php echo json_encode(array_values($chart_servis)); ?>,
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                }
            ]
        },
    options: {
        responsive: true,
            plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                    ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                    }
                }
            }
        }
    }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>