<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "homestay_db";

$adminConn = new mysqli($host, $user, $pass, 'mysql');
if ($adminConn->connect_error) {
    die("Koneksi database gagal: " . $adminConn->connect_error);
}

$adminConn->query("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$adminConn->close();
?>