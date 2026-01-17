<?php
/**
 * User Management - List & Read
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Manajemen User';

// Get all users
$query = "SELECT * FROM users ORDER BY role ASC, nama_lengkap ASC";
$result = $conn->query($query);

include '../../includes/header.php';
?>


<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-people"></i> Manajemen User</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah User
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
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_user']; ?>
                            </td>
                            <td><strong>
                                    <?php echo $row['username']; ?>
                                </strong></td>
                            <td>
                                <?php echo $row['nama_lengkap']; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $row['role'] === 'admin' ? 'primary' : 'info'; ?>">
                                    <?php echo strtoupper($row['role']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo status_badge($row['status']); ?>
                            </td>
                            <td>
                                <?php echo format_tanggal($row['created_at']); ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id_user']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <?php if ($row['id_user'] != get_user_id()): ?>
                                    <a href="delete.php?id=<?php echo $row['id_user']; ?>"
                                        class="btn btn-sm btn-danger btn-delete" data-name="<?php echo $row['username']; ?>">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>