<?php
/**
 * Data Mobil - List & Read
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Data Mobil';

// Get all mobil
$query = "SELECT * FROM mobil ORDER BY status ASC, no_polisi ASC";
$result = $conn->query($query);

include '../../includes/header.php';
?>


<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-truck"></i> Data Mobil / Armada</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Mobil
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No Polisi</th>
                        <th>Merk & Tipe</th>
                        <th>Tahun</th>
                        <th>Warna</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_mobil']; ?>
                            </td>
                            <td><strong>
                                    <?php echo $row['no_polisi']; ?>
                                </strong></td>
                            <td>
                                <?php echo $row['merk'] . ' ' . $row['tipe']; ?>
                            </td>
                            <td>
                                <?php echo $row['tahun_pembuatan']; ?>
                            </td>
                            <td>
                                <?php echo $row['warna']; ?>
                            </td>
                            <td>
                                <?php echo $row['kapasitas_penumpang']; ?> orang
                            </td>
                            <td>
                                <?php echo status_badge($row['status']); ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id_mobil']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="delete.php?id=<?php echo $row['id_mobil']; ?>"
                                    class="btn btn-sm btn-danger btn-delete" data-name="<?php echo $row['no_polisi']; ?>">
                                    <i class="bi bi-trash"></i> Hapus
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