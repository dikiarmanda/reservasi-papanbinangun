<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_admin();

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM homestays WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirect('homestay.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nama = trim((string) ($_POST['nama'] ?? ''));
    $lokasi = trim((string) ($_POST['lokasi'] ?? ''));
    $desc = trim((string) ($_POST['deskripsi'] ?? ''));
    $harga = (float) ($_POST['harga'] ?? 0);
    $kap = (int) ($_POST['kapasitas'] ?? 1);
    $fas = trim((string) ($_POST['fasilitas'] ?? ''));
    $gambar = trim((string) ($_POST['gambar'] ?? ''));
    $status = (string) ($_POST['status'] ?? 'aktif');

    if ($id && $gambar === '') {
        $existing = $conn->prepare("SELECT gambar FROM homestays WHERE id=? LIMIT 1");
        $existing->bind_param("i", $id);
        $existing->execute();
        $existingData = $existing->get_result()->fetch_assoc();
        if ($existingData && !empty($existingData['gambar'])) {
            $gambar = $existingData['gambar'];
        }
    }

    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK && !empty($_FILES['gambar_file']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $ext = strtolower(pathinfo($_FILES['gambar_file']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed, true)) {
            if ($id) {
                $old = $conn->prepare("SELECT gambar FROM homestays WHERE id=? LIMIT 1");
                $old->bind_param("i", $id);
                $old->execute();
                $oldData = $old->get_result()->fetch_assoc();
                if ($oldData && !empty($oldData['gambar']) && $oldData['gambar'] !== $gambar && file_exists('../' . $oldData['gambar'])) {
                    unlink('../' . $oldData['gambar']);
                }
            }

            $uploadDir = '../assets/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = 'homestay-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $uploadDir . $filename)) {
                $gambar = 'assets/images/' . $filename;
            }
        }
    }

    if ($id) {
        $stmt = $conn->prepare("UPDATE homestays SET nama=?,lokasi=?,deskripsi=?,harga=?,kapasitas=?,fasilitas=?,gambar=?,status=? WHERE id=?");
        $stmt->bind_param("sssdiissi", $nama, $lokasi, $desc, $harga, $kap, $fas, $gambar, $status, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO homestays (nama,lokasi,deskripsi,harga,kapasitas,fasilitas,gambar,status) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssdiiss", $nama, $lokasi, $desc, $harga, $kap, $fas, $gambar, $status);
    }

    $stmt->execute();
    redirect('homestay.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM homestays WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}

include 'header.php';
?>
<h1>Kelola Homestay</h1>
<div class="card">
    <h2><?= $edit ? 'Edit' : 'Tambah' ?> Homestay</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">
        <input type="hidden" name="gambar" value="<?= e($edit['gambar'] ?? '') ?>">
        <label>Nama<input required name="nama" value="<?= e($edit['nama'] ?? '') ?>"></label>
        <label>Lokasi / Google Maps<input required type="text" name="lokasi" value="<?= e($edit['lokasi'] ?? '') ?>" placeholder="https://maps.app.goo.gl/Z7nJHH4RJHZRD9hs6"></label>
        <small>Masukkan nama tempat, alamat, atau link Google Maps. Contoh: https://maps.app.goo.gl/Z7nJHH4RJHZRD9hs6</small>
        <label>Deskripsi<textarea required name="deskripsi"><?= e($edit['deskripsi'] ?? '') ?></textarea></label>
        <div class="two">
            <label>Harga/malam<input required type="number" step="1000" min="0" name="harga" value="<?= e($edit['harga'] ?? 0) ?>"></label>
            <label>Kapasitas<input required type="number" name="kapasitas" min="1" value="<?= e($edit['kapasitas'] ?? 1) ?>"></label>
        </div>
        <label>Fasilitas<input name="fasilitas" value="<?= e($edit['fasilitas'] ?? '') ?>"></label>
        <div id="drop-zone" style="border:2px dashed #cbd5e1; border-radius:12px; padding:14px; margin:10px 0; background:#f8fafc; cursor:pointer;">
            <label for="gambar_file" style="display:block; cursor:pointer;">Upload gambar baru / drag & drop image</label>
            <input id="gambar_file" type="file" name="gambar_file" accept="image/*" style="display:none;">
        </div>
        <p class="small">Tip: tekan Ctrl+V atau klik kanan → Paste untuk menempel gambar langsung dari clipboard.</p>
        <div id="paste-preview" style="display:none; margin:10px 0;">
            <img id="paste-preview-image" src="" alt="Preview pasted image" style="max-width:200px;max-height:140px;object-fit:cover;border-radius:8px;">
        </div>
        <?php if (!empty($edit['gambar'] ?? '')): ?>
            <div class="card">
                <img src="../<?= e($edit['gambar']) ?>" alt="Preview" style="max-width:200px;max-height:140px;object-fit:cover;border-radius:8px;">
            </div>
        <?php endif; ?>
        <label>Status<select name="status">
            <option value="aktif" <?= ($edit['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="nonaktif" <?= ($edit['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
        </select></label>
        <button class="btn">Simpan</button>
        <?php if ($edit): ?><a class="btn secondary" href="homestay.php">Batal</a><?php endif; ?>
    </form>
</div>

<script>
(function () {
    const fileInput = document.getElementById('gambar_file');
    const dropZone = document.getElementById('drop-zone');
    const previewBox = document.getElementById('paste-preview');
    const previewImage = document.getElementById('paste-preview-image');

    function setPreviewFromFile(file) {
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            previewImage.src = event.target.result;
            previewBox.style.display = 'block';
        };
        reader.readAsDataURL(file);

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (fileInput.files && fileInput.files[0]) {
                setPreviewFromFile(fileInput.files[0]);
            }
        });
    }

    if (dropZone) {
        dropZone.addEventListener('click', function () {
            fileInput.click();
        });

        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropZone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropZone.style.borderColor = '#2563eb';
                dropZone.style.background = '#eff6ff';
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            dropZone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropZone.style.borderColor = '#cbd5e1';
                dropZone.style.background = '#f8fafc';
            });
        });

        dropZone.addEventListener('drop', function (event) {
            const file = event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files[0] : null;
            if (file) {
                setPreviewFromFile(file);
            }
        });
    }

    document.addEventListener('paste', function (event) {
        const target = event.target;
        if (target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA')) {
            return;
        }

        const items = event.clipboardData && event.clipboardData.items ? event.clipboardData.items : [];
        for (const item of items) {
            if (item.kind === 'file' && item.type.startsWith('image/')) {
                event.preventDefault();
                setPreviewFromFile(item.getAsFile());
                return;
            }
        }
    });
})();
</script>

<div class="card">
    <h2>Daftar Homestay</h2>
    <div class="table-wrap">
        <table>
            <tr><th>Nama</th><th>Harga</th><th>Kapasitas</th><th>Status</th><th>Aksi</th></tr>
            <?php $q = $conn->query("SELECT * FROM homestays ORDER BY id DESC"); while ($h = $q->fetch_assoc()): ?>
                <tr>
                    <td><?= e($h['nama']) ?></td>
                    <td><?= rupiah($h['harga']) ?></td>
                    <td><?= $h['kapasitas'] ?></td>
                    <td><?= e($h['status']) ?></td>
                    <td><a href="?edit=<?= $h['id'] ?>">Edit</a> · <a onclick="return confirm('Hapus homestay?')" href="?hapus=<?= $h['id'] ?>">Hapus</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>
