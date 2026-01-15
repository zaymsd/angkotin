<?php
/**
 * Data Supir - List & Read
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Data Supir';

// Get all supir
$query = "SELECT * FROM supir ORDER BY status ASC, nama_supir ASC";
$result = $conn->query($query);

include '../../includes/header.php';
?>


    <div class="row mb-3">
        <div class="col-md-6">
            <h3><i class="bi bi-people"></i> Data Supir</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Supir
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
                            <th>Nama Supir</th>
                            <th>No HP</th>
                            <th>No SIM</th>
                            <th>Tanggal Bergabung</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_supir']; ?></td>
                                <td>
                                    <strong><?php echo $row['nama_supir']; ?></strong><br>
                                    <small class="text-muted"><?php echo $row['alamat']; ?></small>
                                </td>
                                <td><?php echo $row['no_hp']; ?></td>
                                <td><?php echo $row['no_sim']; ?></td>
                                <td><?php echo format_tanggal($row['tanggal_bergabung']); ?></td>
                                <td><?php echo status_badge($row['status']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $row['id_supir']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id_supir']; ?>"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-name="<?php echo $row['nama_supir']; ?>">
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
\r\n\r\n<?php include '../../includes/footer.php'; ?>
