<?php
/**
 * Absensi - List
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Data Absensi';

// Filter parameters
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');
$id_supir = isset($_GET['id_supir']) ? (int) $_GET['id_supir'] : 0;

// Build query
$where_conditions = ["a.tanggal BETWEEN ? AND ?"];
$params = [$tanggal_awal, $tanggal_akhir];
$types = "ss";

if ($id_supir > 0) {
    $where_conditions[] = "a.id_supir = ?";
    $params[] = $id_supir;
    $types .= "i";
}

$where_clause = implode(" AND ", $where_conditions);

$query = "SELECT a.*, s.nama_supir, m.no_polisi 
          FROM absensi a 
          JOIN supir s ON a.id_supir = s.id_supir 
          JOIN mobil m ON a.id_mobil = m.id_mobil 
          WHERE $where_clause 
          ORDER BY a.tanggal DESC, a.jam_masuk DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get supir list for filter
$supir_list = $conn->query("SELECT id_supir, nama_supir FROM supir WHERE status = 'aktif' ORDER BY nama_supir");

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-calendar-check"></i> Data Absensi</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="input.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Absensi
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
                <label class="form-label">Supir</label>
                <select class="form-select" name="id_supir">
                    <option value="0">-- Semua Supir --</option>
                    <?php while ($supir = $supir_list->fetch_assoc()): ?>
                        <option value="<?php echo $supir['id_supir']; ?>" <?php echo $id_supir == $supir['id_supir'] ? 'selected' : ''; ?>>
                            <?php echo $supir['nama_supir']; ?>
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
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_absensi']; ?>
                            </td>
                            <td>
                                <?php echo format_tanggal($row['tanggal']); ?>
                            </td>
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
                                <?php if ($row['jam_pulang']): ?>
                                    <?php echo format_waktu($row['jam_pulang']); ?>
                                <?php else: ?>
                                    <span class="badge bg-warning">Belum Pulang</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo $row['keterangan'] ?: '-'; ?>
                            </td>
                            <td>
                                <?php if (!$row['jam_pulang']): ?>
                                    <a href="pulang.php?id=<?php echo $row['id_absensi']; ?>" class="btn btn-sm btn-success">
                                        <i class="bi bi-box-arrow-right"></i> Pulang
                                    </a>
                                <?php endif; ?>
                                <a href="edit.php?id=<?php echo $row['id_absensi']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $row['id_absensi']; ?>"
                                    class="btn btn-sm btn-danger btn-delete"
                                    data-name="Absensi <?php echo $row['nama_supir']; ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>