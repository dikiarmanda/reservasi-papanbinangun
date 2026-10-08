<?php
require_once 'config/koneksi.php';
require_once 'config/functions.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM homestays WHERE id=? AND status='aktif'");
$stmt->bind_param("i", $id); $stmt->execute(); $h=$stmt->get_result()->fetch_assoc();
if(!$h) die("Homestay tidak ditemukan.");
$mapsUrl = google_maps_view_url($h['lokasi']);
$mapsEmbed = google_maps_embed_url($h['lokasi']);
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking - <?=e($h['nama'])?></title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="navbar"><div class="container nav-inner"><a class="brand" href="index.php">BinangunHomestay</a><a href="index.php">← Kembali</a></div></header>
<main class="section"><div class="container booking-layout">
<div class="card"><img class="detail-img" src="<?=e($h['gambar'])?>" alt="<?=e($h['nama'])?>">
<?php if ($mapsEmbed): ?>
<div style="margin:12px 0;">
<div class="location">📍 <?php if ($mapsUrl): ?><a href="<?=e($mapsUrl)?>" target="_blank" rel="noreferrer"><?=e($h['lokasi'])?></a><?php else: ?><?=e($h['lokasi'])?><?php endif; ?></div>
<iframe src="<?=e($mapsEmbed)?>" width="100%" height="220" style="border:0;border-radius:12px;margin-top:10px;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<?php else: ?>
<span class="location">📍 <?=e($h['lokasi'])?></span>
<?php endif; ?>
<h1><?=e($h['nama'])?></h1><p><?=e($h['deskripsi'])?></p><h3>Fasilitas</h3><p><?=e($h['fasilitas'])?></p><p><b><?=rupiah($h['harga'])?></b> / malam · maksimal <?=$h['kapasitas']?> orang</p></div>
<div class="card"><h2>Form Pemesanan</h2><form action="proses_booking.php" method="post" id="bookingForm">
<input type="hidden" name="homestay_id" value="<?=$h['id']?>">
<label>Nama Tamu<input required name="nama_tamu" maxlength="120"></label>
<label>No. WhatsApp<input name="whatsapp" placeholder="08xxxxxxxxxx"></label>
<div class="two"><label>Check-in<input required type="date" name="checkin" id="checkin"></label><label>Check-out<input required type="date" name="checkout" id="checkout"></label></div>
<label>Jumlah Tamu<input required type="number" min="1" max="<?=$h['kapasitas']?>" value="1" name="jumlah_tamu"></label>
<label>Catatan<textarea name="catatan" rows="3" placeholder="Permintaan khusus (opsional)"></textarea></label>
<div class="total-box"><span>Total</span><strong id="total">Rp 0</strong><small id="nightText">Pilih tanggal menginap.</small></div>
<button class="btn full" type="submit">Buat Booking</button></form></div></div></main>
<script>
const price=<?=json_encode((float)$h['harga'])?>;
const ci=document.getElementById('checkin'), co=document.getElementById('checkout'), total=document.getElementById('total'), nt=document.getElementById('nightText');
const today=new Date().toISOString().slice(0,10); ci.min=today; co.min=today;
function calc(){ if(!ci.value||!co.value)return; const a=new Date(ci.value),b=new Date(co.value),n=Math.round((b-a)/86400000); if(n>0){total.textContent='Rp '+(n*price).toLocaleString('id-ID');nt.textContent=n+' malam × Rp '+price.toLocaleString('id-ID');}else{total.textContent='Rp 0';nt.textContent='Check-out harus setelah check-in.';}}
ci.addEventListener('change',()=>{co.min=ci.value; if(co.value<=ci.value)co.value='';calc()}); co.addEventListener('change',calc);
document.getElementById('bookingForm').addEventListener('submit',e=>{const a=new Date(ci.value),b=new Date(co.value); if(!ci.value||!co.value||b<=a){e.preventDefault();alert('Tanggal check-out harus setelah check-in.');}});
</script></body></html>