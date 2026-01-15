<?php
/**
 * Data Supir - Delete (Soft Delete)
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
    set_flash('error', 'ID supir tidak valid.');
    redirect('index.php');
}

// Check if supir exists
$stmt = $conn->prepare("SELECT nama_supir FROM supir WHERE id_supir = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data supir tidak ditemukan.');
    redirect('index.php');
}

$supir = $result->fetch_assoc();

// Soft delete - set status to nonaktif
$stmt = $conn->prepare("UPDATE supir SET status = 'nonaktif' WHERE id_supir = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    set_flash('success', 'Data supir "' . $supir['nama_supir'] . '" berhasil dinonaktifkan.');
} else {
    set_flash('error', 'Gagal menghapus data supir: ' . $conn->error);
}

redirect('index.php');
?>