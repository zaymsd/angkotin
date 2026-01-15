<?php
/**
 * Data Mobil - Edit
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$page_title = 'Edit Mobil';

// Get ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID mobil tidak valid.');
    redirect('index.php');
}

// Get current data
$stmt = $conn->prepare("SELECT * FROM mobil WHERE id_mobil = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data mobil tidak ditemukan.');
    redirect('index.php');
}

$mobil = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_polisi = strtoupper(sanitize_input($_POST['no_polisi']));
    $merk = sanitize_input($_POST['merk']);
    $tipe = sanitize_input($_POST['tipe']);
    $tahun_pembuatan = $_POST['tahun_pembuatan'];
    $warna = sanitize_input($_POST['warna']);
    $kapasitas_penumpang = (int) $_POST['kapasitas_penumpang'];
    $status = $_POST['status'];

    // Validation
    $errors = [];

    if (empty($no_polisi)) {
        $errors[] = 'No polisi harus diisi.';
    } else {
        // Check if no_polisi already exists (except current record)
        $stmt = $conn->prepare("SELECT id_mobil FROM mobil WHERE no_polisi = ? AND id_mobil != ?");
        $stmt->bind_param("si", $no_polisi, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'No polisi sudah terdaftar.';
        }
    }

    if (empty($merk)) {
        $errors[] = 'Merk mobil harus diisi.';
    }

    if ($kapasitas_penumpang < 1) {
        $errors[] = 'Kapasitas penumpang harus lebih dari 0.';
    }

    if (empty($errors)) {
        // Update database
        $stmt = $conn->prepare("UPDATE mobil SET no_polisi = ?, merk = ?, tipe = ?, tahun_pembuatan = ?, warna = ?, kapasitas_penumpang = ?, status = ? WHERE id_mobil = ?");
        $stmt->bind_param("sssisisi", $no_polisi, $merk, $tipe, $tahun_pembuatan, $warna, $kapasitas_penumpang, $status, $id);

        if ($stmt->execute()) {
            set_flash('success', 'Data mobil berhasil diupdate.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal mengupdate data mobil: ' . $conn->error);
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
            <h3><i class="bi bi-pencil"></i> Edit Mobil</h3>
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
                            <label for="no_polisi" class="form-label">No Polisi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="no_polisi" name="no_polisi"
                                value="<?php echo $mobil['no_polisi']; ?>" required>
                            <div class="invalid-feedback">No polisi harus diisi</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="merk" class="form-label">Merk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="merk" name="merk"
                                value="<?php echo $mobil['merk']; ?>" required>
                            <div class="invalid-feedback">Merk harus diisi</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <input type="text" class="form-control" id="tipe" name="tipe"
                                value="<?php echo $mobil['tipe']; ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                            <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan"
                                min="1990" max="<?php echo date('Y'); ?>"
                                value="<?php echo $mobil['tahun_pembuatan']; ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="warna" class="form-label">Warna</label>
                            <input type="text" class="form-control" id="warna" name="warna"
                                value="<?php echo $mobil['warna']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kapasitas_penumpang" class="form-label">Kapasitas Penumpang <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="kapasitas_penumpang"
                                name="kapasitas_penumpang" min="1" max="30"
                                value="<?php echo $mobil['kapasitas_penumpang']; ?>" required>
                            <div class="invalid-feedback">Kapasitas harus diisi</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="operasional" <?php echo $mobil['status'] === 'operasional' ? 'selected' : ''; ?>>Operasional</option>
                                <option value="servis" <?php echo $mobil['status'] === 'servis' ? 'selected' : ''; ?>
                                    >Servis</option>
                                <option value="nonaktif" <?php echo $mobil['status'] === 'nonaktif' ? 'selected' : ''; ?>
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

<?php include '../../includes/footer.php'; ?>