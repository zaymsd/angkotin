<?php
/**
 * Database Configuration
 * Sistem Informasi Angkot
 */

// Database credentials
// == LOCALHOST ==
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_angkotin');

// == PRODUCTION (Uncomment and fill this when on cPanel) ==
/*
define('DB_HOST', 'localhost'); // Biasanya tetap localhost di cPanel
define('DB_USER', 'username_cpanel_anda');
define('DB_PASS', 'password_db_anda');
define('DB_NAME', 'nama_db_cpanel_anda');
*/

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset to utf8mb4 for full unicode support
$conn->set_charset("utf8mb4");

/**
 * Function to execute query safely
 * @param mysqli $conn Database connection
 * @param string $query SQL query
 * @return mysqli_result|bool
 */
function db_query($conn, $query)
{
    $result = $conn->query($query);
    if (!$result) {
        error_log("Database Error: " . $conn->error);
    }
    return $result;
}

/**
 * Function to execute prepared statement
 * @param mysqli $conn Database connection
 * @param string $query SQL query with placeholders
 * @param string $types Parameter types (s=string, i=integer, d=double, b=blob)
 * @param array $params Parameters to bind
 * @return mysqli_stmt|false
 */
function db_prepare($conn, $query, $types = '', $params = [])
{
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare Error: " . $conn->error);
        return false;
    }

    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    return $stmt;
}

/**
 * Get single row from result
 * @param mysqli_result $result
 * @return array|null
 */
function db_fetch($result)
{
    return $result->fetch_assoc();
}

/**
 * Get all rows from result
 * @param mysqli_result $result
 * @return array
 */
function db_fetch_all($result)
{
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Escape string for SQL
 * @param mysqli $conn
 * @param string $string
 * @return string
 */
function db_escape($conn, $string)
{
    return $conn->real_escape_string($string);
}
?>