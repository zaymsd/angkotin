<?php
/**
 * Setoran - Delete
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID setoran tidak valid.');
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

// Delete record
$stmt = $conn->prepare("DELETE FROM setoran WHERE id_setoran = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    set_flash('success', 'Setoran ' . $setoran['nama_supir'] . ' sebesar ' . format_rupiah($setoran['jumlah_setoran']) . ' berhasil dihapus.');
} else {
    set_flash('error', 'Gagal menghapus setoran: ' . $conn->error);
}

redirect('index.php');
?>