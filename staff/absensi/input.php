<?php
/**
 * Absensi - Input (Jam Masuk)
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Input Absensi';

// Get active supir and mobil
$supir_list = $conn->query("SELECT id_supir, nama_supir FROM supir WHERE status = 'aktif' ORDER BY nama_supir");
$mobil_list = $conn->query("SELECT id_mobil, no_polisi, merk FROM mobil WHERE status = 'operasional' ORDER BY no_polisi");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_supir = (int) $_POST['id_supir'];
    $id_mobil = (int) $_POST['id_mobil'];
    $tanggal = $_POST['tanggal'];
    $jam_masuk = $_POST['jam_masuk'];
    $keterangan = sanitize_input($_POST['keterangan']);

    $errors = [];

    // Validation
    if ($id_supir <= 0) {
        $errors[] = 'Pilih supir yang valid.';
    }

    if ($id_mobil <= 0) {
        $errors[] = 'Pilih mobil yang valid.';
    }

    if (empty($tanggal)) {
        $errors[] = 'Tanggal harus diisi.';
    }

    if (empty($jam_masuk)) {
        $errors[] = 'Jam masuk harus diisi.';
    }

    // Check if supir already has absensi for this date
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_absensi FROM absensi WHERE id_supir = ? AND tanggal = ?");
        $stmt->bind_param("is", $id_supir, $tanggal);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Supir ini sudah memiliki absensi untuk tanggal tersebut.';
        }
    }

    // Check if mobil already used by another supir for this date
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_absensi, id_supir FROM absensi WHERE id_mobil = ? AND tanggal = ?");
        $stmt->bind_param("is", $id_mobil, $tanggal);
        $stmt->execute();
        $mobil_check = $stmt->get_result();
        if ($mobil_check->num_rows > 0) {
            $existing = $mobil_check->fetch_assoc();
            if ($existing['id_supir'] != $id_supir) {
                $errors[] = 'Mobil ini sudah digunakan supir lain pada tanggal tersebut.';
            }
        }
    }

    if (empty($errors)) {
        $id_user_input = get_user_id();
        $stmt = $conn->prepare("INSERT INTO absensi (id_supir, id_mobil, tanggal, jam_masuk, keterangan, id_user_input) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssi", $id_supir, $id_mobil, $tanggal, $jam_masuk, $keterangan, $id_user_input);

        if ($stmt->execute()) {
            set_flash('success', 'Absensi berhasil dicatat.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal menyimpan absensi: ' . $conn->error);
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-calendar-plus"></i> Input Absensi Masuk</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_supir" class="form-label">Supir <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_supir" name="id_supir" required>
                            <option value="">-- Pilih Supir --</option>
                            <?php while ($supir = $supir_list->fetch_assoc()): ?>
                                <option value="<?php echo $supir['id_supir']; ?>">
                                    <?php echo $supir['nama_supir']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="invalid-feedback">Pilih supir</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_mobil" class="form-label">Mobil <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_mobil" name="id_mobil" required>
                            <option value="">-- Pilih Mobil --</option>
                            <?php while ($mobil = $mobil_list->fetch_assoc()): ?>
                                <option value="<?php echo $mobil['id_mobil']; ?>">
                                    <?php echo $mobil['no_polisi'] . ' - ' . $mobil['merk']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="invalid-feedback">Pilih mobil</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control date-max-today" id="tanggal" name="tanggal"
                            value="<?php echo date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">Tanggal harus diisi</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jam_masuk" class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk"
                            value="<?php echo date('H:i'); ?>" required>
                        <div class="invalid-feedback">Jam masuk harus diisi</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                    placeholder="Opsional"></textarea>
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

<?php include '../../includes/footer.php'; ?>