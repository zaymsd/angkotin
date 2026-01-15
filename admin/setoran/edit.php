<?php
/**
 * Setoran - Edit
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Edit Setoran';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID setoran tidak valid.');
    redirect('index.php');
}

// Get setoran data
$stmt = $conn->prepare("SELECT * FROM setoran WHERE id_setoran = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data setoran tidak ditemukan.');
    redirect('index.php');
}

$setoran = $result->fetch_assoc();

// Check if already confirmed (only admin can edit confirmed setoran)
if ($setoran['status'] === 'dikonfirmasi' && !is_admin()) {
    set_flash('error', 'Setoran yang sudah dikonfirmasi hanya bisa diedit oleh Admin.');
    redirect('index.php');
}

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

    if ($jumlah_setoran <= 0) {
        $errors[] = 'Jumlah setoran harus lebih dari 0.';
    }

    // Check duplicate (exclude current record)
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_setoran FROM setoran WHERE id_supir = ? AND id_mobil = ? AND tanggal_setoran = ? AND id_setoran != ?");
        $stmt->bind_param("iisi", $id_supir, $id_mobil, $tanggal_setoran, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Setoran untuk supir dan mobil ini pada tanggal tersebut sudah ada.';
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE setoran SET id_supir = ?, id_mobil = ?, tanggal_setoran = ?, jumlah_setoran = ?, keterangan = ? WHERE id_setoran = ?");
        $stmt->bind_param("iisisi", $id_supir, $id_mobil, $tanggal_setoran, $jumlah_setoran, $keterangan, $id);

        if ($stmt->execute()) {
            set_flash('success', 'Setoran berhasil diupdate.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal mengupdate setoran: ' . $conn->error);
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-pencil"></i> Edit Setoran</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<?php if ($setoran['status'] !== 'pending'): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        Setoran ini sudah berstatus <strong>
            <?php echo strtoupper($setoran['status']); ?>
        </strong>.
        Perubahan akan tetap disimpan.
    </div>
<?php endif; ?>

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
                                <option value="<?php echo $supir['id_supir']; ?>" <?php echo $setoran['id_supir'] == $supir['id_supir'] ? 'selected' : ''; ?>>
                                    <?php echo $supir['nama_supir']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_mobil" class="form-label">Mobil <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_mobil" name="id_mobil" required>
                            <option value="">-- Pilih Mobil --</option>
                            <?php while ($mobil = $mobil_list->fetch_assoc()): ?>
                                <option value="<?php echo $mobil['id_mobil']; ?>" <?php echo $setoran['id_mobil'] == $mobil['id_mobil'] ? 'selected' : ''; ?>>
                                    <?php echo $mobil['no_polisi'] . ' - ' . $mobil['merk']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_setoran" class="form-label">Tanggal Setoran <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_setoran" name="tanggal_setoran"
                            value="<?php echo $setoran['tanggal_setoran']; ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jumlah_setoran" class="form-label">Jumlah Setoran (Rp) <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah-input" id="jumlah_setoran" name="jumlah_setoran"
                            value="<?php echo number_format($setoran['jumlah_setoran'], 0, ',', '.'); ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan"
                    rows="2"><?php echo $setoran['keterangan']; ?></textarea>
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

<?php include '../../includes/footer.php'; ?>