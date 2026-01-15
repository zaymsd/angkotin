<?php
/**
 * Setoran - Input
 * Staff & Admin
 * Mobile-friendly form
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Input Setoran';

// Get active supir and mobil
$supir_list = $conn->query("SELECT id_supir, nama_supir FROM supir WHERE status = 'aktif' ORDER BY nama_supir");
$mobil_list = $conn->query("SELECT id_mobil, no_polisi, merk FROM mobil WHERE status = 'operasional' ORDER BY no_polisi");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_supir = (int) $_POST['id_supir'];
    $id_mobil = (int) $_POST['id_mobil'];
    $tanggal_setoran = $_POST['tanggal_setoran'];
    $jumlah_setoran = (int) str_replace(['.', ','], '', $_POST['jumlah_setoran']);
    $keterangan = sanitize_input($_POST['keterangan']);

    $errors = [];

    // Validation
    if ($id_supir <= 0) {
        $errors[] = 'Pilih supir yang valid.';
    }

    if ($id_mobil <= 0) {
        $errors[] = 'Pilih mobil yang valid.';
    }

    if (empty($tanggal_setoran)) {
        $errors[] = 'Tanggal setoran harus diisi.';
    }

    if ($jumlah_setoran <= 0) {
        $errors[] = 'Jumlah setoran harus lebih dari 0.';
    }

    // Check duplicate setoran for same supir, mobil, date
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_setoran FROM setoran WHERE id_supir = ? AND id_mobil = ? AND tanggal_setoran = ?");
        $stmt->bind_param("iis", $id_supir, $id_mobil, $tanggal_setoran);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Setoran untuk supir dan mobil ini pada tanggal tersebut sudah ada.';
        }
    }

    if (empty($errors)) {
        $id_user_input = get_user_id();
        $stmt = $conn->prepare("INSERT INTO setoran (id_supir, id_mobil, tanggal_setoran, jumlah_setoran, keterangan, status, id_user_input) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
        $stmt->bind_param("iisisi", $id_supir, $id_mobil, $tanggal_setoran, $jumlah_setoran, $keterangan, $id_user_input);

        if ($stmt->execute()) {
            set_flash('success', 'Setoran berhasil dicatat. Menunggu konfirmasi admin.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal menyimpan setoran: ' . $conn->error);
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-cash-coin"></i> Input Setoran</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" class="needs-validation mobile-form" novalidate>
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
                        <label for="tanggal_setoran" class="form-label">Tanggal Setoran <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control date-max-today" id="tanggal_setoran"
                            name="tanggal_setoran" value="<?php echo date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">Tanggal harus diisi</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jumlah_setoran" class="form-label">Jumlah Setoran (Rp) <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah-input" id="jumlah_setoran" name="jumlah_setoran"
                            placeholder="0" required>
                        <div class="invalid-feedback">Masukkan jumlah setoran</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                    placeholder="Opsional"></textarea>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Setoran akan berstatus <strong>Pending</strong> dan memerlukan konfirmasi dari Admin.
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