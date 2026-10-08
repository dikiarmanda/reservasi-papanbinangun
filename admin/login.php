<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
if(is_admin()) redirect('dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $u=trim($_POST['username']??'');$p=$_POST['password']??'';
    $stmt=$conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");$stmt->bind_param("s",$u);$stmt->execute();$a=$stmt->get_result()->fetch_assoc();
    if($a && password_verify($p,$a['password'])){$_SESSION['admin_id']=$a['id'];$_SESSION['admin_nama']=$a['nama'];redirect('dashboard.php');}
    $error='Username atau password salah.';
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login Admin</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body><main class="section"><div class="container narrow"><div class="card login-card"><div class="brand center">BinangunHomestay</div><h1>Login Admin</h1><?php if($error):?><div class="alert"><?=e($error)?></div><?php endif;?>
<form method="post"><label>Username<input required name="username" autocomplete="username"></label><label>Password<input required type="password" name="password" autocomplete="current-password"></label><button class="btn full">Login</button></form><p><a href="../index.php">← Kembali ke website</a></p></div></div></main></body></html>