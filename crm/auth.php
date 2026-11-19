<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$script_path = $_SERVER['SCRIPT_NAME'];
$crm_pos = strpos($script_path, '/crm');

if ($crm_pos !== false) {
    $base_url = substr($script_path, 0, $crm_pos + 4);
} else {
    $base_url = '/ajmera/crm';
}

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true
) {
    header("Location: " . $base_url . "/index.php");
    exit;
}