<?php
/**
 * User Management - Edit
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Edit User';

// Get ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID user tidak valid.');
    redirect('index.php');
}

// Get current data
$stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data user tidak ditemukan.');
    redirect('index.php');
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama_lengkap = sanitize_input($_POST['nama_lengkap']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Validation
    $errors = [];

    if (empty($username)) {
        $errors[] = 'Username harus diisi.';
    } elseif (strlen($username) < 4) {
        $errors[] = 'Username minimal 4 karakter.';
    } else {
        // Check if username already exists (except current user)
        $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ? AND id_user != ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Username sudah terdaftar.';
        }
    }

    // Password validation (only if password is being changed)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }

        if ($password !== $confirm_password) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }
    }

    if (empty($nama_lengkap)) {
        $errors[] = 'Nama lengkap harus diisi.';
    }

    if (empty($errors)) {
        // Update database
        if (!empty($password)) {
            // Update with new password
            $password_hash = hash_password($password);
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, nama_lengkap = ?, role = ?, status = ? WHERE id_user = ?");
            $stmt->bind_param("sssssi", $username, $password_hash, $nama_lengkap, $role, $status, $id);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("UPDATE users SET username = ?, nama_lengkap = ?, role = ?, status = ? WHERE id_user = ?");
            $stmt->bind_param("ssssi", $username, $nama_lengkap, $role, $status, $id);
        }

        if ($stmt->execute()) {
            set_flash('success', 'Data user berhasil diupdate.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal mengupdate data user: ' . $conn->error);
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3><i class="bi bi-pencil"></i> Edit User</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?php echo $user['username']; ?>" minlength="4" required>
                            <small class="text-muted">Minimal 4 karakter</small>
                            <div class="invalid-feedback">Username harus diisi (minimal 4 karakter)</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                value="<?php echo $user['nama_lengkap']; ?>" required>
                            <div class="invalid-feedback">Nama lengkap harus diisi</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Ganti Password:</strong> Kosongkan field password jika tidak ingin mengubah password.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru (Opsional)</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6">
                            <small class="text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin ubah
                                password.</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                minlength="6">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff
                                </option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin
                                </option>
                            </select>
                            <small class="text-muted">Staff: Akses operasional | Admin: Full access</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" <?php echo $user['status'] === 'aktif' ? 'selected' : ''; ?>>Aktif
                                </option>
                                <option value="nonaktif" <?php echo $user['status'] === 'nonaktif' ? 'selected' : ''; ?>
                                    >Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Password confirmation validation (only if password field is filled)
    document.getElementById('confirm_password').addEventListener('input', function () {
        const password = document.getElementById('password').value;
        const confirm = this.value;

        if (password && password !== confirm) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });
</script>

<?php include '../../includes/footer.php'; ?>