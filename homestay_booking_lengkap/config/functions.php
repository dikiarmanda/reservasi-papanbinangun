<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function is_admin() {
    return !empty($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) redirect('login.php');
}

function booking_code() {
    return 'HS' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
}

function whatsapp_url($number, $message) {
    $number = preg_replace('/[^0-9]/', '', $number);
    if (substr($number, 0, 1) === '0') $number = '62' . substr($number, 1);
    return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
}

function google_maps_view_url($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//i', $value)) {
        return $value;
    }

    return 'https://www.google.com/maps?q=' . rawurlencode($value);
}

function google_maps_embed_url($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (!preg_match('/^https?:\/\//i', $value)) {
        return 'https://www.google.com/maps?q=' . rawurlencode($value) . '&z=15&output=embed';
    }

    $resolved = $value;
    if (stripos($value, 'maps.app.goo.gl') !== false || stripos($value, 'goo.gl/maps') !== false) {
        $headers = @get_headers($value, 1);
        if ($headers) {
            $locations = [];
            if (isset($headers['Location'])) {
                $locations = is_array($headers['Location']) ? $headers['Location'] : [$headers['Location']];
            }
            if (!empty($locations)) {
                $resolved = end($locations);
            }
        }
    }

    if (stripos($resolved, 'output=embed') !== false) {
        return $resolved;
    }

    $separator = (strpos($resolved, '?') === false) ? '?' : '&';
    return $resolved . $separator . 'output=embed';
}
?>