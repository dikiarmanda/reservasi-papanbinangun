<?php
require_once '../config/koneksi.php'; require_once '../config/functions.php'; require_admin();
if(isset($_GET['status'],$_GET['id'])){
    $id=(int)$_GET['id'];$status=$_GET['status'];$allowed=['pending','dikonfirmasi','selesai','dibatalkan'];
    if(in_array($status,$allowed,true)){ $stmt=$conn->prepare("UPDATE bookings SET status=? WHERE id=?");$stmt->bind_param("si",$status,$id);$stmt->execute();}
    redirect('booking.php');
}
include 'header.php'; ?>
<h1>Data Booking</h1><div class="card"><div class="table-wrap"><table><tr><th>Kode</th><th>Tamu</th><th>Homestay</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
<?php $q=$conn->query("SELECT b.*,h.nama homestay FROM bookings b JOIN homestays h ON h.id=b.homestay_id ORDER BY b.id DESC");while($b=$q->fetch_assoc()):?>
<tr><td><b><?=e($b['kode_booking'])?></b><br><small><?=e($b['whatsapp'])?></small></td><td><?=e($b['nama_tamu'])?><br><small><?=$b['jumlah_tamu']?> tamu</small></td><td><?=e($b['homestay'])?></td><td><?=e($b['checkin'])?><br>→ <?=e($b['checkout'])?><br><?=$b['malam']?> malam</td><td><?=rupiah($b['total'])?></td><td><span class="status <?=$b['status']?>"><?=e($b['status'])?></span></td>
<td><select onchange="if(this.value)location.href='?id=<?=$b['id']?>&status='+this.value"><option value="">Ubah status</option><?php foreach(['pending','dikonfirmasi','selesai','dibatalkan'] as $s):?><option value="<?=$s?>"><?=$s?></option><?php endforeach;?></select></td></tr>
<?php endwhile;?></table></div></div>
<?php include 'footer.php'; ?>