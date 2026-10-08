CREATE DATABASE IF NOT EXISTS homestay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE homestay_db;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS homestays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    kapasitas INT NOT NULL DEFAULT 1,
    fasilitas TEXT,
    gambar VARCHAR(255) DEFAULT '',
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_booking VARCHAR(30) NOT NULL UNIQUE,
    homestay_id INT NOT NULL,
    nama_tamu VARCHAR(120) NOT NULL,
    whatsapp VARCHAR(30) NOT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    jumlah_tamu INT NOT NULL DEFAULT 1,
    malam INT NOT NULL,
    harga_per_malam DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    catatan TEXT,
    status ENUM('pending','dikonfirmasi','selesai','dibatalkan') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_booking_homestay FOREIGN KEY (homestay_id) REFERENCES homestays(id) ON DELETE RESTRICT
);

INSERT INTO homestays (nama,lokasi,deskripsi,harga,kapasitas,fasilitas,gambar,status) VALUES
('Homestay Papan Binangun','Desa Binangun','Homestay nyaman dengan suasana tenang dan cocok untuk liburan keluarga.',250000,4,'WiFi, AC, Parkir, Kamar mandi dalam, Air panas','assets/images/homestay1.svg','aktif'),
('Villa Pinus Asri','Area Perbukitan','Penginapan dengan suasana alam dan area santai untuk keluarga.',350000,6,'WiFi, AC, Parkir luas, Dapur, Balkon','assets/images/homestay2.svg','aktif'),
('Rumah Singgah Desa','Dekat pusat desa','Pilihan ekonomis untuk perjalanan singkat maupun keluarga kecil.',180000,3,'WiFi, Kipas, Parkir, Dapur bersama','assets/images/homestay3.svg','aktif');

-- Password admin dibuat oleh setup.php agar menggunakan password_hash().
