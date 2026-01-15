<?php
/**
 * Servis - Delete
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID servis tidak valid.');
    redirect('index.php');
}

// Get servis data
$stmt = $conn->prepare("SELECT sv.*, m.no_polisi FROM servis sv JOIN mobil m ON sv.id_mobil = m.id_mobil WHERE sv.id_servis = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data servis tidak ditemukan.');
    redirect('index.php');
}

$servis = $result->fetch_assoc();

// Delete record
$stmt = $conn->prepare("DELETE FROM servis WHERE id_servis = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    set_flash('success', 'Data servis ' . $servis['no_polisi'] . ' (' . $servis['jenis_servis'] . ') berhasil dihapus.');
} else {
    set_flash('error', 'Gagal menghapus data servis: ' . $conn->error);
}

redirect('index.php');
?>