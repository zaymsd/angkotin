<?php
/**
 * Data Supir - Create
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Tambah Supir';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_supir = sanitize_input($_POST['nama_supir']);
    $no_hp = sanitize_input($_POST['no_hp']);
    $alamat = sanitize_input($_POST['alamat']);
    $no_sim = sanitize_input($_POST['no_sim']);
    $tanggal_bergabung = $_POST['tanggal_bergabung'];
    $status = $_POST['status'];

    // Validation
    $errors = [];

    if (empty($nama_supir)) {
        $errors[] = 'Nama supir harus diisi.';
    }

    if (empty($no_sim)) {
        $errors[] = 'No SIM harus diisi.';
    }

    if (empty($tanggal_bergabung)) {
        $errors[] = 'Tanggal bergabung harus diisi.';
    }

    if (empty($errors)) {
        // Insert to database
        $stmt = $conn->prepare("INSERT INTO supir (nama_supir, no_hp, alamat, no_sim, tanggal_bergabung, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nama_supir, $no_hp, $alamat, $no_sim, $tanggal_bergabung, $status);

        if ($stmt->execute()) {
            set_flash('success', 'Data supir berhasil ditambahkan.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal menambahkan data supir: ' . $conn->error);
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
            <h3><i class="bi bi-person-plus"></i> Tambah Supir</h3>
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
                            <label for="nama_supir" class="form-label">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_supir" name="nama_supir" required>
                            <div class="invalid-feedback">Nama supir harus diisi</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="081234567890">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="no_sim" class="form-label">No SIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_sim" name="no_sim" required>
                            <div class="invalid-feedback">No SIM harus diisi</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control date-max-today" id="tanggal_bergabung"
                                name="tanggal_bergabung" required>
                            <div class="invalid-feedback">Tanggal bergabung harus diisi</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
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

<?php include '../../includes/footer.php'; ?>