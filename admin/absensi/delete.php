<?php
/**
 * Absensi - Delete
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID absensi tidak valid.');
    redirect('index.php');
}

// Get absensi data
$stmt = $conn->prepare("SELECT a.*, s.nama_supir FROM absensi a JOIN supir s ON a.id_supir = s.id_supir WHERE a.id_absensi = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data absensi tidak ditemukan.');
    redirect('index.php');
}

$absensi = $result->fetch_assoc();

// Delete record
$stmt = $conn->prepare("DELETE FROM absensi WHERE id_absensi = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    set_flash('success', 'Absensi ' . $absensi['nama_supir'] . ' tanggal ' . format_tanggal($absensi['tanggal']) . ' berhasil dihapus.');
} else {
    set_flash('error', 'Gagal menghapus absensi: ' . $conn->error);
}

redirect('index.php');
?>