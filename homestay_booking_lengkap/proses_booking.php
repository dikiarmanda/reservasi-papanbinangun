<?php
require_once 'config/koneksi.php';
require_once 'config/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');

$hid=(int)($_POST['homestay_id']??0);
$nama=trim($_POST['nama_tamu']??'');
$wa=trim($_POST['whatsapp']??'');
$checkin=$_POST['checkin']??'';
$checkout=$_POST['checkout']??'';
$tamu=(int)($_POST['jumlah_tamu']??1);
$catatan=trim($_POST['catatan']??'');

$stmt=$conn->prepare("SELECT * FROM homestays WHERE id=? AND status='aktif'");
$stmt->bind_param("i",$hid);$stmt->execute();$h=$stmt->get_result()->fetch_assoc();
if(!$h) die("Homestay tidak tersedia.");

$start=DateTime::createFromFormat('Y-m-d',$checkin);
$end=DateTime::createFromFormat('Y-m-d',$checkout);
if(!$start||!$end||$end<=$start||$tamu<1||$tamu>$h['kapasitas']||$nama==='') die("Data booking tidak valid.");

$malam=(int)$start->diff($end)->days;
$total=$malam*(float)$h['harga'];
$kode=booking_code();

$stmt=$conn->prepare("INSERT INTO bookings (kode_booking,homestay_id,nama_tamu,whatsapp,checkin,checkout,jumlah_tamu,malam,harga_per_malam,total,catatan) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("sissssiidds",$kode,$hid,$nama,$wa,$checkin,$checkout,$tamu,$malam,$h['harga'],$total,$catatan);
$stmt->execute();

$msg="Halo Admin, saya ingin konfirmasi booking.%0A%0AKode: {$kode}%0AHomestay: {$h['nama']}%0ANama: {$nama}%0ACheck-in: {$checkin}%0ACheck-out: {$checkout}%0ATamu: {$tamu}%0AMalam: {$malam}%0ATotal: ".rupiah($total)."%0A%0AMohon konfirmasi ketersediaannya.";
$waAdmin='62881011430378';
// prepare WhatsApp URL safely to avoid inline-php quoting issues in HTML
$waMessage = "Halo Admin, saya ingin konfirmasi booking.\n\nKode: $kode\nHomestay: {$h['nama']}\nNama: $nama\nCheck-in: $checkin\nCheck-out: $checkout\nTamu: $tamu\nMalam: $malam\nTotal: " . rupiah($total);
$waUrl = whatsapp_url($waAdmin, $waMessage);
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Booking Berhasil</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><main class="section"><div class="container narrow"><div class="card success-card"><div class="success-icon">✓</div><h1>Booking berhasil dibuat</h1><p>Simpan kode booking berikut untuk pengecekan.</p><div class="booking-code"><?=e($kode)?></div>
<div class="summary"><p><b>Homestay:</b> <?=e($h['nama'])?></p><p><b>Check-in:</b> <?=e($checkin)?></p><p><b>Check-out:</b> <?=e($checkout)?></p><p><b>Total:</b> <?=rupiah($total)?></p></div>
<a class="btn full" target="_blank" href="<?=e($waUrl)?>">Konfirmasi via WhatsApp</a>
<a class="btn secondary full" href="index.php">Kembali ke Beranda</a></div></div></main></body></html>