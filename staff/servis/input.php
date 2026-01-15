<?php
/**
 * Servis - Input
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

$page_title = 'Input Servis';

// Get mobil list
$mobil_list = $conn->query("SELECT id_mobil, no_polisi, merk FROM mobil ORDER BY no_polisi");

// Common jenis servis options
$jenis_servis_options = [
    'Ganti Oli',
    'Tune Up',
    'Servis Rem',
    'Servis AC',
    'Ganti Ban',
    'Servis Mesin',
    'Servis Kopling',
    'Ganti Aki',
    'Servis Kelistrikan',
    'Body Repair',
    'Lainnya'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mobil = (int) $_POST['id_mobil'];
    $tanggal_servis = $_POST['tanggal_servis'];
    $jenis_servis = $_POST['jenis_servis'] === 'Lainnya' ? sanitize_input($_POST['jenis_servis_lainnya']) : $_POST['jenis_servis'];
    $biaya = (int) str_replace(['.', ','], '', $_POST['biaya']);
    $keterangan = sanitize_input($_POST['keterangan']);

    $errors = [];

    // Validation
    if ($id_mobil <= 0) {
        $errors[] = 'Pilih mobil yang valid.';
    }

    if (empty($tanggal_servis)) {
        $errors[] = 'Tanggal servis harus diisi.';
    }

    if (empty($jenis_servis)) {
        $errors[] = 'Jenis servis harus diisi.';
    }

    if ($biaya <= 0) {
        $errors[] = 'Biaya servis harus lebih dari 0.';
    }

    if (empty($errors)) {
        $id_user_input = get_user_id();
        $stmt = $conn->prepare("INSERT INTO servis (id_mobil, tanggal_servis, jenis_servis, biaya, keterangan, id_user_input) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issisi", $id_mobil, $tanggal_servis, $jenis_servis, $biaya, $keterangan, $id_user_input);

        if ($stmt->execute()) {
            set_flash('success', 'Data servis berhasil dicatat.');
            redirect('index.php');
        } else {
            set_flash('error', 'Gagal menyimpan data servis: ' . $conn->error);
        }
    } else {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h3><i class="bi bi-wrench"></i> Input Servis</h3>
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

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_servis" class="form-label">Tanggal Servis <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control date-max-today" id="tanggal_servis" name="tanggal_servis"
                            value="<?php echo date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">Tanggal harus diisi</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jenis_servis" class="form-label">Jenis Servis <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_servis" name="jenis_servis" required
                            onchange="toggleLainnya()">
                            <option value="">-- Pilih Jenis --</option>
                            <?php foreach ($jenis_servis_options as $jenis): ?>
                                <option value="<?php echo $jenis; ?>">
                                    <?php echo $jenis; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Pilih jenis servis</div>
                    </div>
                    <div class="mb-3" id="jenis_lainnya_container" style="display: none;">
                        <label for="jenis_servis_lainnya" class="form-label">Jenis Servis Lainnya</label>
                        <input type="text" class="form-control" id="jenis_servis_lainnya" name="jenis_servis_lainnya"
                            placeholder="Masukkan jenis servis">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="biaya" class="form-label">Biaya (Rp) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah-input" id="biaya" name="biaya" placeholder="0"
                            required>
                        <div class="invalid-feedback">Masukkan biaya servis</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                    placeholder="Detail servis, spare part, bengkel, dll."></textarea>
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

<script>
    function toggleLainnya() {
        const jenisSelect = document.getElementById('jenis_servis');
        const lainnyaContainer = document.getElementById('jenis_lainnya_container');
        const lainnyaInput = document.getElementById('jenis_servis_lainnya');

        if (jenisSelect.value === 'Lainnya') {
            lainnyaContainer.style.display = 'block';
            lainnyaInput.setAttribute('required', 'required');
        } else {
            lainnyaContainer.style.display = 'none';
            lainnyaInput.removeAttribute('required');
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>