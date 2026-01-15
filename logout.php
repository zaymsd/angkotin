<?php
/**
 * Logout Handler
 * Sistem Informasi Angkot
 */

// Start session
session_start();

// Include required files
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Logout user
logout_user();

// Set flash message
set_flash('success', 'Anda telah berhasil logout.');

// Redirect to login page
redirect('index.php');
?>