<?php
/**
 * Common Utility Functions
 * Sistem Informasi Angkot
 */

/**
 * Sanitize input data
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Format number to Rupiah currency
 * @param float $number Number to format
 * @return string Formatted currency
 */
function format_rupiah($number)
{
    return 'Rp ' . number_format($number, 0, ',', '.');
}

/**
 * Format date to Indonesian format
 * @param string $date Date string
 * @param bool $show_day Show day name
 * @return string Formatted date
 */
function format_tanggal($date, $show_day = false)
{
    if (!$date || $date == '0000-00-00') {
        return '-';
    }

    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $timestamp = strtotime($date);
    $day = date('j', $timestamp);
    $month = $bulan[date('n', $timestamp)];
    $year = date('Y', $timestamp);

    $result = $day . ' ' . $month . ' ' . $year;

    if ($show_day) {
        $day_name = $hari[date('l', $timestamp)];
        $result = $day_name . ', ' . $result;
    }

    return $result;
}

/**
 * Format time to Indonesian format
 * @param string $time Time string
 * @return string Formatted time
 */
function format_waktu($time)
{
    if (!$time || $time == '00:00:00') {
        return '-';
    }
    return date('H:i', strtotime($time)) . ' WIB';
}

/**
 * Format datetime to Indonesian format
 * @param string $datetime Datetime string
 * @return string Formatted datetime
 */
function format_datetime($datetime)
{
    if (!$datetime || $datetime == '0000-00-00 00:00:00') {
        return '-';
    }
    return format_tanggal($datetime) . ' ' . date('H:i', strtotime($datetime)) . ' WIB';
}

/**
 * Get current user role
 * @return string User role (admin/staff)
 */
function get_user_role()
{
    return isset($_SESSION['role']) ? $_SESSION['role'] : '';
}

/**
 * Get current user ID
 * @return int User ID
 */
function get_user_id()
{
    return isset($_SESSION['id_user']) ? (int) $_SESSION['id_user'] : 0;
}

/**
 * Get current username
 * @return string Username
 */
function get_username()
{
    return isset($_SESSION['username']) ? $_SESSION['username'] : '';
}

/**
 * Get current user full name
 * @return string Full name
 */
function get_user_fullname()
{
    return isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : '';
}

/**
 * Check if user is admin
 * @return bool
 */
function is_admin()
{
    return get_user_role() === 'admin';
}

/**
 * Check if user is staff
 * @return bool
 */
function is_staff()
{
    return get_user_role() === 'staff';
}

/**
 * Redirect to another page
 * @param string $url Target URL
 * @param int $delay Delay in seconds (default: 0)
 */
function redirect($url, $delay = 0)
{
    if ($delay > 0) {
        header("Refresh: $delay; url=$url");
    } else {
        header("Location: $url");
        exit();
    }
}

/**
 * Set flash message
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message content
 */
function set_flash($type, $message)
{
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

/**
 * Get and clear flash message
 * @return array|null Flash message data
 */
function get_flash()
{
    if (isset($_SESSION['flash_message'])) {
        $flash = [
            'type' => $_SESSION['flash_type'],
            'message' => $_SESSION['flash_message']
        ];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Generate status badge HTML
 * @param string $status Status value
 * @param array $config Badge configuration
 * @return string HTML badge
 */
function status_badge($status, $config = [])
{
    $default_config = [
        'aktif' => 'success',
        'nonaktif' => 'secondary',
        'operasional' => 'success',
        'servis' => 'warning',
        'pending' => 'warning',
        'dikonfirmasi' => 'success'
    ];

    $badges = array_merge($default_config, $config);
    $badge_class = isset($badges[$status]) ? $badges[$status] : 'secondary';

    return '<span class="badge bg-' . $badge_class . '">' . ucfirst($status) . '</span>';
}

/**
 * Generate pagination HTML
 * @param int $current_page Current page number
 * @param int $total_pages Total pages
 * @param string $base_url Base URL for pagination
 * @return string HTML pagination
 */
function pagination($current_page, $total_pages, $base_url)
{
    if ($total_pages <= 1) {
        return '';
    }

    $html = '<nav><ul class="pagination justify-content-center">';

    // Previous button
    if ($current_page > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($current_page - 1) . '">Previous</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($current_page < $total_pages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($current_page + 1) . '">Next</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    $html .= '</ul></nav>';

    return $html;
}

/**
 * Validate date format
 * @param string $date Date string
 * @param string $format Date format (default: Y-m-d)
 * @return bool
 */
function validate_date($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Calculate percentage
 * @param float $part Part value
 * @param float $total Total value
 * @param int $decimals Decimal places
 * @return float|int
 */
function calculate_percentage($part, $total, $decimals = 2)
{
    if ($total == 0) {
        return 0;
    }
    return round(($part / $total) * 100, $decimals);
}

/**
 * Generate random string
 * @param int $length String length
 * @return string Random string
 */
function generate_random_string($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_string;
}

/**
 * Log activity
 * @param mysqli $conn Database connection
 * @param string $activity Activity description
 */
function log_activity($conn, $activity)
{
    // Optional: Implement activity logging
    // This can be expanded to log user activities to database
    error_log(date('Y-m-d H:i:s') . ' - User ' . get_username() . ': ' . $activity);
}
?>