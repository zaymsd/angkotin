<?php
/**
 * Setoran - List
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Data Setoran';

// Filter parameters
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query
$where_conditions = ["st.tanggal_setoran BETWEEN ? AND ?"];
$params = [$tanggal_awal, $tanggal_akhir];
$types = "ss";

if (!empty($status_filter)) {
    $where_conditions[] = "st.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$where_clause = implode(" AND ", $where_conditions);

$query = "SELECT st.*, s.nama_supir, m.no_polisi 
          FROM setoran st 
          JOIN supir s ON st.id_supir = s.id_supir 
          JOIN mobil m ON st.id_mobil = m.id_mobil 
          WHERE $where_clause 
          ORDER BY st.tanggal_setoran DESC, st.id_setoran DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$total_setoran = 0;
$setoran_data = [];
while ($row = $result->fetch_assoc()) {
    $setoran_data[] = $row;
    if ($row['status'] === 'dikonfirmasi') {
        $total_setoran += $row['jumlah_setoran'];
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-cash-stack"></i> Data Setoran</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="input.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Setoran
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" class="form-control" name="tanggal_awal" value="<?php echo $tanggal_awal; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="dikonfirmasi" <?php echo $status_filter === 'dikonfirmasi' ? 'selected' : ''; ?>>
                        Dikonfirmasi</option>
                    <option value="ditolak" <?php echo $status_filter === 'ditolak' ? 'selected' : ''; ?>>Ditolak
                    </option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Card -->
<div class="card mb-4 stat-card stat-success">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col">
                <div class="stat-label">Total Setoran Dikonfirmasi</div>
                <div class="stat-value">
                    <?php echo format_rupiah($total_setoran); ?>
                </div>
            </div>
            <div class="col-auto">
                <i class="bi bi-cash-coin" style="font-size: 2.5rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Supir</th>
                        <th>Mobil</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($setoran_data as $row): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_setoran']; ?>
                            </td>
                            <td>
                                <?php echo format_tanggal($row['tanggal_setoran']); ?>
                            </td>
                            <td>
                                <?php echo $row['nama_supir']; ?>
                            </td>
                            <td>
                                <?php echo $row['no_polisi']; ?>
                            </td>
                            <td><strong>
                                    <?php echo format_rupiah($row['jumlah_setoran']); ?>
                                </strong></td>
                            <td>
                                <?php echo status_badge($row['status']); ?>
                            </td>
                            <td>
                                <?php echo $row['keterangan'] ?: '-'; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id_setoran']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>