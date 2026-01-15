<?php
/**
 * Data Mobil - Delete (Soft Delete)
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';

require_admin();

// Get ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    set_flash('error', 'ID mobil tidak valid.');
    redirect('index.php');
}

// Check if mobil exists
$stmt = $conn->prepare("SELECT no_polisi FROM mobil WHERE id_mobil = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data mobil tidak ditemukan.');
    redirect('index.php');
}

$mobil = $result->fetch_assoc();

// Soft delete - set status to nonaktif
$stmt = $conn->prepare("UPDATE mobil SET status = 'nonaktif' WHERE id_mobil = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    set_flash('success', 'Data mobil "' . $mobil['no_polisi'] . '" berhasil dinonaktifkan.');
} else {
    set_flash('error', 'Gagal menghapus data mobil: ' . $conn->error);
}

redirect('index.php');
?>