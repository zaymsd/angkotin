<?php
/**
 * User Management - Create
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Tambah User';

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
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Username sudah terdaftar.';
        }
    }

    if (empty($password)) {
        $errors[] = 'Password harus diisi.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    if (empty($nama_lengkap)) {
        $errors[] = 'Nama lengkap harus diisi.';
    }

    if (empty($errors)) {
        // Hash password
        $password_hash = hash_password($password);

        // Insert to database
        $stmt = $conn->prepare("INSERT INTO users (username, password, nama_lengkap, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password_hash, $nama_lengkap, $role, $status);

        if ($stmt->execute()) {
            set_flash('success', 'User berhasil ditambahkan.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal menambahkan user: ' . $conn->error);
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
            <h3><i class="bi bi-person-plus"></i> Tambah User</h3>
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
                            <input type="text" class="form-control" id="username" name="username" minlength="4"
                                required>
                            <small class="text-muted">Minimal 4 karakter</small>
                            <div class="invalid-feedback">Username harus diisi (minimal 4 karakter)</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                            <div class="invalid-feedback">Nama lengkap harus diisi</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6"
                                required>
                            <small class="text-muted">Minimal 6 karakter</small>
                            <div class="invalid-feedback">Password harus diisi (minimal 6 karakter)</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                minlength="6" required>
                            <div class="invalid-feedback">Konfirmasi password harus sama</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="staff" selected>Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <small class="text-muted">Staff: Akses operasional | Admin: Full access</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Catatan:</strong> Password akan di-hash secara otomatis untuk keamanan.
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function () {
        const password = document.getElementById('password').value;
        const confirm = this.value;

        if (password !== confirm) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });
</script>

<?php include '../../includes/footer.php'; ?>