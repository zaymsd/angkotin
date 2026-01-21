<?php
/**
 * Absensi - Edit
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Edit Absensi';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID absensi tidak valid.');
    redirect('index.php');
}

// Get absensi data
$stmt = $conn->prepare("SELECT * FROM absensi WHERE id_absensi = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data absensi tidak ditemukan.');
    redirect('index.php');
}

$absensi = $result->fetch_assoc();

// Get active supir and mobil
$supir_list = $conn->query("SELECT id_supir, nama_supir FROM supir WHERE status = 'aktif' ORDER BY nama_supir");
$mobil_list = $conn->query("SELECT id_mobil, no_polisi, merk FROM mobil WHERE status = 'operasional' ORDER BY no_polisi");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_supir = (int) $_POST['id_supir'];
    $id_mobil = (int) $_POST['id_mobil'];
    $tanggal = $_POST['tanggal'];
    $jam_masuk = $_POST['jam_masuk'];
    $jam_pulang = $_POST['jam_pulang'] ?: null;
    $keterangan = sanitize_input($_POST['keterangan']);

    $errors = [];

    // Validation
    if ($id_supir <= 0) {
        $errors[] = 'Pilih supir yang valid.';
    }

    if ($id_mobil <= 0) {
        $errors[] = 'Pilih mobil yang valid.';
    }

    // Check duplicate supir (exclude current record)
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_absensi FROM absensi WHERE id_supir = ? AND tanggal = ? AND id_absensi != ?");
        $stmt->bind_param("isi", $id_supir, $tanggal, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Supir ini sudah memiliki absensi untuk tanggal tersebut.';
        }
    }

    // Check duplicate mobil (exclude current record)
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_absensi, id_supir FROM absensi WHERE id_mobil = ? AND tanggal = ? AND id_absensi != ?");
        $stmt->bind_param("isi", $id_mobil, $tanggal, $id);
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
        $stmt = $conn->prepare("UPDATE absensi SET id_supir = ?, id_mobil = ?, tanggal = ?, jam_masuk = ?, jam_pulang = ?, keterangan = ? WHERE id_absensi = ?");
        $stmt->bind_param("iissssi", $id_supir, $id_mobil, $tanggal, $jam_masuk, $jam_pulang, $keterangan, $id);

        if ($stmt->execute()) {
            set_flash('success', 'Absensi berhasil diupdate.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal mengupdate absensi: ' . $conn->error);
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-pencil"></i> Edit Absensi</h3>
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
                                <option value="<?php echo $supir['id_supir']; ?>" <?php echo $absensi['id_supir'] == $supir['id_supir'] ? 'selected' : ''; ?>>
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
                                <option value="<?php echo $mobil['id_mobil']; ?>" <?php echo $absensi['id_mobil'] == $mobil['id_mobil'] ? 'selected' : ''; ?>>
                                    <?php echo $mobil['no_polisi'] . ' - ' . $mobil['merk']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                            value="<?php echo $absensi['tanggal']; ?>" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="jam_masuk" class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk"
                            value="<?php echo substr($absensi['jam_masuk'], 0, 5); ?>" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="jam_pulang" class="form-label">Jam Pulang</label>
                        <input type="time" class="form-control" id="jam_pulang" name="jam_pulang"
                            value="<?php echo $absensi['jam_pulang'] ? substr($absensi['jam_pulang'], 0, 5) : ''; ?>">
                        <small class="text-muted">Kosongkan jika belum pulang</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan"
                    rows="2"><?php echo $absensi['keterangan']; ?></textarea>
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