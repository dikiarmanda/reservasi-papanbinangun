<?php
require_once 'config/koneksi.php';
require_once 'config/functions.php';

$messages = [];
$conn->query("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$check = $conn->query("SELECT id FROM admins WHERE username='admin' LIMIT 1");
if ($check && $check->num_rows === 0) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (nama, username, password) VALUES (?,?,?)");
    $nama = 'Administrator';
    $username = 'admin';
    $stmt->bind_param("sss", $nama, $username, $password);
    $stmt->execute();
    $messages[] = "Admin berhasil dibuat: username admin, password admin123";
} else {
    $messages[] = "Admin sudah ada. Jika lupa password, hapus user admin dari database lalu jalankan setup.php lagi.";
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Setup Homestay</title>
<link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="container narrow"><div class="card">
<h1>Setup berhasil</h1>
<?php foreach($messages as $m): ?><p><?=e($m)?></p><?php endforeach; ?>
<p><a class="btn" href="admin/login.php">Login Admin</a> <a class="btn secondary" href="index.php">Buka Website</a></p>
<p class="warning">Demi keamanan, hapus atau rename file <b>setup.php</b> setelah instalasi.</p>
</div></div></body></html>