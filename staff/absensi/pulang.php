<?php
/**
 * Absensi - Pulang (Update Jam Pulang)
 * Staff & Admin
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_staff();

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

// Check if already pulang
if ($absensi['jam_pulang']) {
    set_flash('error', 'Supir ini sudah tercatat pulang.');
    redirect('index.php');
}

// Update jam pulang
$jam_pulang = date('H:i:s');
$stmt = $conn->prepare("UPDATE absensi SET jam_pulang = ? WHERE id_absensi = ?");
$stmt->bind_param("si", $jam_pulang, $id);

if ($stmt->execute()) {
    set_flash('success', 'Jam pulang ' . $absensi['nama_supir'] . ' berhasil dicatat: ' . format_waktu($jam_pulang));
} else {
    set_flash('error', 'Gagal mencatat jam pulang: ' . $conn->error);
}

redirect('index.php');
?>