<?php
/**
 * User Management - Delete (Soft Delete)
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
    set_flash('error', 'ID user tidak valid.');
    redirect('index.php');
}

// Prevent self-delete
if ($id == get_user_id()) {
    set_flash('error', 'Anda tidak bisa menghapus akun sendiri.');
    redirect('index.php');
}

// Check if user exists
$stmt = $conn->prepare("SELECT username FROM users WHERE id_user = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    set_flash('error', 'Data user tidak ditemukan.');
    redirect('index.php');
}

$user = $result->fetch_assoc();

// Soft delete - set status to nonaktif
$stmt = $conn->prepare("UPDATE users SET status = 'nonaktif' WHERE id_user = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    set_flash('success', 'User "' . $user['username'] . '" berhasil dinonaktifkan.');
} else {
    set_flash('error', 'Gagal menghapus user: ' . $conn->error);
}

redirect('index.php');
?>