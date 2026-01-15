<?php
/**
 * Servis - List
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Data Servis';

// Filter parameters
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');
$id_mobil = isset($_GET['id_mobil']) ? (int) $_GET['id_mobil'] : 0;

// Build query
$where_conditions = ["sv.tanggal_servis BETWEEN ? AND ?"];
$params = [$tanggal_awal, $tanggal_akhir];
$types = "ss";

if ($id_mobil > 0) {
    $where_conditions[] = "sv.id_mobil = ?";
    $params[] = $id_mobil;
    $types .= "i";
}

$where_clause = implode(" AND ", $where_conditions);

$query = "SELECT sv.*, m.no_polisi, m.merk 
          FROM servis sv 
          JOIN mobil m ON sv.id_mobil = m.id_mobil 
          WHERE $where_clause 
          ORDER BY sv.tanggal_servis DESC, sv.id_servis DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$total_biaya = 0;
$servis_data = [];
while ($row = $result->fetch_assoc()) {
    $servis_data[] = $row;
    $total_biaya += $row['biaya'];
}

// Get mobil list for filter
$mobil_list = $conn->query("SELECT id_mobil, no_polisi, merk FROM mobil ORDER BY no_polisi");

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-tools"></i> Data Servis</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="input.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Servis
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
                <label class="form-label">Mobil</label>
                <select class="form-select" name="id_mobil">
                    <option value="0">-- Semua Mobil --</option>
                    <?php while ($mobil = $mobil_list->fetch_assoc()): ?>
                        <option value="<?php echo $mobil['id_mobil']; ?>" <?php echo $id_mobil == $mobil['id_mobil'] ? 'selected' : ''; ?>>
                            <?php echo $mobil['no_polisi'] . ' - ' . $mobil['merk']; ?>
                        </option>
                    <?php endwhile; ?>
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
<div class="card mb-4 stat-card stat-danger">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col">
                <div class="stat-label">Total Biaya Servis</div>
                <div class="stat-value">
                    <?php echo format_rupiah($total_biaya); ?>
                </div>
                <small class="text-muted">
                    <?php echo count($servis_data); ?> kali servis
                </small>
            </div>
            <div class="col-auto">
                <i class="bi bi-wrench" style="font-size: 2.5rem; opacity: 0.3;"></i>
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
                        <th>Mobil</th>
                        <th>Jenis Servis</th>
                        <th>Biaya</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servis_data as $row): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_servis']; ?>
                            </td>
                            <td>
                                <?php echo format_tanggal($row['tanggal_servis']); ?>
                            </td>
                            <td>
                                <strong>
                                    <?php echo $row['no_polisi']; ?>
                                </strong><br>
                                <small class="text-muted">
                                    <?php echo $row['merk']; ?>
                                </small>
                            </td>
                            <td>
                                <?php echo $row['jenis_servis']; ?>
                            </td>
                            <td><strong class="text-danger">
                                    <?php echo format_rupiah($row['biaya']); ?>
                                </strong></td>
                            <td>
                                <?php echo $row['keterangan'] ?: '-'; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id_servis']; ?>" class="btn btn-sm btn-warning">
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