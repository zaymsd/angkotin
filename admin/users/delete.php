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

// Hard delete
$stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
$stmt->bind_param("i", $id);

try {
    if ($stmt->execute()) {
        set_flash('success', 'User "' . $user['username'] . '" berhasil dihapus.');
    } else {
        throw new Exception($conn->error);
    }
} catch (Exception $e) {
    // Check for foreign key constraint violation (Error 1451)
    if ($conn->errno == 1451) {
        set_flash('error', 'Gagal menghapus: User ini telah menginput data transaksi. Data user tidak dapat dihapus demi integritas data.');
    } else {
        set_flash('error', 'Gagal menghapus user: ' . $e->getMessage());
    }
}

redirect('index.php');
?>