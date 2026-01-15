<?php
/**
 * Authentication & Authorization
 * Sistem Informasi Angkot
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool
 */
function is_logged_in()
{
    return isset($_SESSION['id_user']) && isset($_SESSION['username']);
}

/**
 * Require login - redirect to login page if not authenticated
 * @param string $login_url Login page URL
 */
function require_login($login_url = '../login.php')
{
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect($login_url);
    }
}

/**
 * Check user role and redirect if unauthorized
 * @param string|array $allowed_roles Allowed role(s)
 * @param string $redirect_url Redirect URL if unauthorized
 */
function check_role($allowed_roles, $redirect_url = '../index.php')
{
    if (!is_logged_in()) {
        redirect('../login.php');
    }

    $user_role = get_user_role();

    if (is_array($allowed_roles)) {
        if (!in_array($user_role, $allowed_roles)) {
            set_flash('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect($redirect_url);
        }
    } else {
        if ($user_role !== $allowed_roles) {
            set_flash('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect($redirect_url);
        }
    }
}

/**
 * Require admin role
 */
function require_admin()
{
    check_role('admin', '../index.php');
}

/**
 * Require staff role (or admin)
 */
function require_staff()
{
    check_role(['admin', 'staff'], '../index.php');
}

/**
 * Login user
 * @param mysqli $conn Database connection
 * @param string $username Username
 * @param string $password Password
 * @return array Result array with success status and message
 */
function login_user($conn, $username, $password)
{
    $username = sanitize_input($username);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id_user, username, password, nama_lengkap, role, status FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return [
            'success' => false,
            'message' => 'Username atau password salah.'
        ];
    }

    $user = $result->fetch_assoc();

    // Check if account is active
    if ($user['status'] !== 'aktif') {
        return [
            'success' => false,
            'message' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'
        ];
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        return [
            'success' => false,
            'message' => 'Username atau password salah.'
        ];
    }

    // Set session variables
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_time'] = time();

    // Regenerate session ID for security
    session_regenerate_id(true);

    return [
        'success' => true,
        'message' => 'Login berhasil!',
        'role' => $user['role']
    ];
}

/**
 * Logout user
 */
function logout_user()
{
    // Unset all session variables
    $_SESSION = array();

    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    // Destroy session
    session_destroy();
}

/**
 * Check session timeout (optional security feature)
 * @param int $timeout Timeout in seconds (default: 3600 = 1 hour)
 */
function check_session_timeout($timeout = 3600)
{
    if (isset($_SESSION['login_time'])) {
        $elapsed = time() - $_SESSION['login_time'];

        if ($elapsed > $timeout) {
            logout_user();
            set_flash('warning', 'Sesi Anda telah berakhir. Silakan login kembali.');
            redirect('../login.php');
        }

        // Update login time for activity
        $_SESSION['login_time'] = time();
    }
}

/**
 * Hash password
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool
 */
function verify_password($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Generate CSRF token
 * @return string CSRF token
 */
function generate_csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool
 */
function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * CSRF token input field
 * @return string HTML input field
 */
function csrf_field()
{
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
?>