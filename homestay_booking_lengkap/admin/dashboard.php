<?php
require_once '../config/koneksi.php'; require_once '../config/functions.php';
require_admin();
$totalH=(int)$conn->query("SELECT COUNT(*) c FROM homestays")->fetch_assoc()['c'];
$totalB=(int)$conn->query("SELECT COUNT(*) c FROM bookings")->fetch_assoc()['c'];
$pending=(int)$conn->query("SELECT COUNT(*) c FROM bookings WHERE status='pending'")->fetch_assoc()['c'];
$revenue=(float)$conn->query("SELECT COALESCE(SUM(total),0) c FROM bookings WHERE status IN ('dikonfirmasi','selesai')")->fetch_assoc()['c'];
include 'header.php'; ?>
<h1>Dashboard</h1><p>Selamat datang, <?=e($_SESSION['admin_nama'])?>.</p>
<div class="stats"><div class="stat"><span>Homestay</span><b><?=$totalH?></b></div><div class="stat"><span>Total Booking</span><b><?=$totalB?></b></div><div class="stat"><span>Pending</span><b><?=$pending?></b></div><div class="stat"><span>Pendapatan</span><b><?=rupiah($revenue)?></b></div></div>
<div class="card"><h2>Booking Terbaru</h2><div class="table-wrap"><table><tr><th>Kode</th><th>Tamu</th><th>Homestay</th><th>Tanggal</th><th>Total</th><th>Status</th></tr>
<?php $q=$conn->query("SELECT b.*,h.nama homestay FROM bookings b JOIN homestays h ON h.id=b.homestay_id ORDER BY b.id DESC LIMIT 10");while($b=$q->fetch_assoc()):?>
<tr><td><?=e($b['kode_booking'])?></td><td><?=e($b['nama_tamu'])?></td><td><?=e($b['homestay'])?></td><td><?=e($b['checkin'])?> → <?=e($b['checkout'])?></td><td><?=rupiah($b['total'])?></td><td><span class="status <?=$b['status']?>"><?=e($b['status'])?></span></td></tr>
<?php endwhile;?></table></div></div>
<?php include 'footer.php'; ?>