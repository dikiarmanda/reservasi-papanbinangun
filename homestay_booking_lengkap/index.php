<?php
require_once 'config/koneksi.php';
require_once 'config/functions.php';
$rows = $conn->query("SELECT * FROM homestays WHERE status='aktif' ORDER BY id DESC");
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>BinangunHomestay</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="navbar"><div class="container nav-inner"><a class="brand" href="index.php">BinangunHomestay</a>
<nav><a href="index.php">Beranda</a><a href="#homestay">Homestay</a><a href="#cara">Cara Booking</a><a href="#kontak">Kontak</a></nav>
<div style="display:flex; gap:10px; align-items:center;"><a class="btn secondary small" href="admin/login.php">Admin Login</a><a class="btn small" href="#homestay">Pesan</a></div></div></header>

<section class="hero"><div class="container hero-content">
<div><span class="badge">Penginapan nyaman & mudah dipesan</span><h1>Temukan homestay untuk perjalananmu.</h1>
<p>Pilih penginapan, tentukan tanggal, hitung total otomatis, lalu konfirmasi melalui WhatsApp.</p>
<a class="btn" href="#homestay">Lihat Homestay</a></div>
</div></section>

<section id="homestay" class="section"><div class="container"><div class="section-head"><div><span class="eyebrow">Pilihan penginapan</span><h2>Homestay tersedia</h2></div></div>
<div class="grid">
<?php while($h=$rows->fetch_assoc()): $mapsUrl = google_maps_view_url($h['lokasi']); ?>
<article class="stay-card"><img src="<?=e($h['gambar'])?>" alt="<?=e($h['nama'])?>">
<div class="stay-body"><div class="location">📍 <?php if ($mapsUrl): ?><a href="<?=e($mapsUrl)?>" target="_blank" rel="noreferrer"><?=e($h['lokasi'])?></a><?php else: ?><?=e($h['lokasi'])?><?php endif; ?></div><h3><?=e($h['nama'])?></h3><p><?=e($h['deskripsi'])?></p>
<div class="meta"><span>👥 <?=e($h['kapasitas'])?> orang</span><strong><?=rupiah($h['harga'])?>/malam</strong></div>
<a class="btn full" href="booking.php?id=<?=$h['id']?>">Pesan Sekarang</a></div></article>
<?php endwhile; ?>
</div></div></section>

<section id="cara" class="section light"><div class="container"><span class="eyebrow">Mudah dan cepat</span><h2>Cara Booking</h2>
<div class="steps"><div><b>1</b><h3>Pilih homestay</h3><p>Lihat harga, kapasitas, dan fasilitas.</p></div><div><b>2</b><h3>Isi data</h3><p>Tentukan tanggal menginap dan data tamu.</p></div><div><b>3</b><h3>Konfirmasi</h3><p>Kirim detail booking ke WhatsApp admin.</p></div></div></div></section>

<footer id="kontak"><div class="container footer-inner"><div><h3>BinangunHomestay</h3><p>Website pemesanan homestay.</p></div><div><p>WhatsApp Admin</p><a href="https://wa.me/62881011430378">+62 881-0114-30378</a></div></div></footer>
</body></html>