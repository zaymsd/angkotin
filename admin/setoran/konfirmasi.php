<?php
/**
 * Setoran - Konfirmasi (Admin Only)
 * Approve or Reject setoran
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($id === 0) {
    set_flash('error', 'ID setoran tidak valid.');
    redirect('index.php');
}

if (!in_array($action, ['approve', 'reject'])) {
    set_flash('error', 'Aksi tidak valid.');
    redirect('index.php');
}

// Get setoran data
$stmt = $conn->prepare("SELECT st.*, s.nama_supir FROM setoran st JOIN supir s ON st.id_supir = s.id_supir WHERE st.id_setoran = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data setoran tidak ditemukan.');
    redirect('index.php');
}

$setoran = $result->fetch_assoc();

// Check if already processed
if ($setoran['status'] !== 'pending') {
    set_flash('error', 'Setoran ini sudah di-' . $setoran['status'] . '.');
    redirect('index.php');
}

// Update status
$new_status = ($action === 'approve') ? 'dikonfirmasi' : 'ditolak';
$id_admin_konfirmasi = get_user_id();
$tanggal_konfirmasi = date('Y-m-d H:i:s');

$stmt = $conn->prepare("UPDATE setoran SET status = ?, id_admin_konfirmasi = ?, tanggal_konfirmasi = ? WHERE id_setoran = ?");
$stmt->bind_param("sisi", $new_status, $id_admin_konfirmasi, $tanggal_konfirmasi, $id);

if ($stmt->execute()) {
    $message = ($action === 'approve')
        ? 'Setoran ' . $setoran['nama_supir'] . ' sebesar ' . format_rupiah($setoran['jumlah_setoran']) . ' berhasil dikonfirmasi.'
        : 'Setoran ' . $setoran['nama_supir'] . ' berhasil ditolak.';
    set_flash('success', $message);
} else {
    set_flash('error', 'Gagal mengupdate status setoran: ' . $conn->error);
}

redirect('index.php');
?>