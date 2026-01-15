-- ============================================
-- Table: users
-- Description: User management dengan role-based access
-- ============================================
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'staff') NOT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: supir
-- Description: Master data supir/driver
-- ============================================
CREATE TABLE supir (
    id_supir INT PRIMARY KEY AUTO_INCREMENT,
    nama_supir VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15),
    alamat TEXT,
    no_sim VARCHAR(20),
    tanggal_bergabung DATE,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nama (nama_supir),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: mobil
-- Description: Master data mobil/armada
-- ============================================
CREATE TABLE mobil (
    id_mobil INT PRIMARY KEY AUTO_INCREMENT,
    no_polisi VARCHAR(15) UNIQUE NOT NULL,
    merk VARCHAR(50),
    tipe VARCHAR(50),
    tahun_pembuatan YEAR,
    warna VARCHAR(30),
    kapasitas_penumpang INT,
    status ENUM('operasional', 'servis', 'nonaktif') DEFAULT 'operasional',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_no_polisi (no_polisi),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: absensi
-- Description: Data absensi supir harian
-- ============================================
CREATE TABLE absensi (
    id_absensi INT PRIMARY KEY AUTO_INCREMENT,
    id_supir INT NOT NULL,
    id_mobil INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_masuk TIME,
    jam_pulang TIME,
    keterangan TEXT,
    id_user_input INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_supir) REFERENCES supir(id_supir) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_mobil) REFERENCES mobil(id_mobil) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_user_input) REFERENCES users(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY unique_supir_tanggal (id_supir, tanggal),
    INDEX idx_tanggal (tanggal),
    INDEX idx_supir (id_supir),
    INDEX idx_mobil (id_mobil)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: setoran
-- Description: Transaksi setoran harian
-- ============================================
CREATE TABLE setoran (
    id_setoran INT PRIMARY KEY AUTO_INCREMENT,
    id_supir INT NOT NULL,
    id_mobil INT NOT NULL,
    tanggal_setoran DATE NOT NULL,
    jumlah_setoran DECIMAL(12,2) NOT NULL,
    keterangan TEXT,
    status ENUM('pending', 'dikonfirmasi') DEFAULT 'pending',
    id_user_input INT NOT NULL,
    id_admin_konfirmasi INT,
    tanggal_konfirmasi DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_supir) REFERENCES supir(id_supir) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_mobil) REFERENCES mobil(id_mobil) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_user_input) REFERENCES users(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_admin_konfirmasi) REFERENCES users(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_tanggal (tanggal_setoran),
    INDEX idx_status (status),
    INDEX idx_supir (id_supir),
    INDEX idx_mobil (id_mobil)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: servis
-- Description: Data servis & biaya perawatan
-- ============================================
CREATE TABLE servis (
    id_servis INT PRIMARY KEY AUTO_INCREMENT,
    id_mobil INT NOT NULL,
    tanggal_servis DATE NOT NULL,
    jenis_servis VARCHAR(100) NOT NULL,
    bengkel VARCHAR(100),
    biaya DECIMAL(12,2) NOT NULL,
    keterangan TEXT,
    id_user_input INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mobil) REFERENCES mobil(id_mobil) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_user_input) REFERENCES users(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_tanggal (tanggal_servis),
    INDEX idx_mobil (id_mobil),
    INDEX idx_jenis (jenis_servis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Default Data
-- ============================================

-- Default admin user (username: admin, password: admin123)
INSERT INTO users (username, password, nama_lengkap, role, status) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 'aktif'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff Garasi', 'staff', 'aktif');

-- Sample data supir
INSERT INTO supir (nama_supir, no_hp, alamat, no_sim, tanggal_bergabung, status) VALUES
('Budi Santoso', '081234567890', 'Jl. Merdeka No. 10, Bandung', 'SIM1234567890', '2020-01-15', 'aktif'),
('Ahmad Hidayat', '081234567891', 'Jl. Sudirman No. 25, Bandung', 'SIM1234567891', '2020-03-20', 'aktif'),
('Dedi Kurniawan', '081234567892', 'Jl. Asia Afrika No. 5, Bandung', 'SIM1234567892', '2021-05-10', 'aktif'),
('Eko Prasetyo', '081234567893', 'Jl. Cihampelas No. 15, Bandung', 'SIM1234567893', '2021-08-12', 'aktif'),
('Fajar Nugraha', '081234567894', 'Jl. Dago No. 30, Bandung', 'SIM1234567894', '2022-01-05', 'aktif');

-- Sample data mobil
INSERT INTO mobil (no_polisi, merk, tipe, tahun_pembuatan, warna, kapasitas_penumpang, status) VALUES
('D 1234 AB', 'Toyota', 'Kijang', 2015, 'Silver', 12, 'operasional'),
('D 5678 CD', 'Suzuki', 'Carry', 2016, 'Putih', 10, 'operasional'),
('D 9012 EF', 'Daihatsu', 'Gran Max', 2017, 'Biru', 12, 'operasional'),
('D 3456 GH', 'Toyota', 'Avanza', 2018, 'Hitam', 8, 'operasional'),
('D 7890 IJ', 'Suzuki', 'APV', 2019, 'Silver', 10, 'operasional');

-- ============================================
-- Views for Reporting
-- ============================================

-- View: Laporan Setoran Harian
CREATE OR REPLACE VIEW view_setoran_harian AS
SELECT 
    s.id_setoran,
    s.tanggal_setoran,
    sp.nama_supir,
    m.no_polisi,
    s.jumlah_setoran,
    s.status,
    u.nama_lengkap AS input_by,
    a.nama_lengkap AS konfirmasi_by,
    s.tanggal_konfirmasi
FROM setoran s
JOIN supir sp ON s.id_supir = sp.id_supir
JOIN mobil m ON s.id_mobil = m.id_mobil
JOIN users u ON s.id_user_input = u.id_user
LEFT JOIN users a ON s.id_admin_konfirmasi = a.id_user
ORDER BY s.tanggal_setoran DESC, s.created_at DESC;

-- View: Laporan Servis per Mobil
CREATE OR REPLACE VIEW view_servis_mobil AS
SELECT 
    sv.id_servis,
    sv.tanggal_servis,
    m.no_polisi,
    m.merk,
    m.tipe,
    sv.jenis_servis,
    sv.bengkel,
    sv.biaya,
    sv.keterangan,
    u.nama_lengkap AS input_by
FROM servis sv
JOIN mobil m ON sv.id_mobil = m.id_mobil
JOIN users u ON sv.id_user_input = u.id_user
ORDER BY sv.tanggal_servis DESC;

-- View: Performa Mobil
CREATE OR REPLACE VIEW view_performa_mobil AS
SELECT 
    m.id_mobil,
    m.no_polisi,
    m.merk,
    m.tipe,
    COUNT(DISTINCT a.tanggal) AS total_hari_operasional,
    COALESCE(SUM(CASE WHEN s.status = 'dikonfirmasi' THEN s.jumlah_setoran ELSE 0 END), 0) AS total_setoran,
    COUNT(sv.id_servis) AS total_servis,
    COALESCE(SUM(sv.biaya), 0) AS total_biaya_servis,
    COALESCE(SUM(CASE WHEN s.status = 'dikonfirmasi' THEN s.jumlah_setoran ELSE 0 END), 0) - COALESCE(SUM(sv.biaya), 0) AS net_income
FROM mobil m
LEFT JOIN absensi a ON m.id_mobil = a.id_mobil
LEFT JOIN setoran s ON m.id_mobil = s.id_mobil
LEFT JOIN servis sv ON m.id_mobil = sv.id_mobil
WHERE m.status != 'nonaktif'
GROUP BY m.id_mobil, m.no_polisi, m.merk, m.tipe;

-- View: Performa Supir
CREATE OR REPLACE VIEW view_performa_supir AS
SELECT 
    sp.id_supir,
    sp.nama_supir,
    COUNT(DISTINCT a.tanggal) AS total_hari_kerja,
    COALESCE(SUM(CASE WHEN s.status = 'dikonfirmasi' THEN s.jumlah_setoran ELSE 0 END), 0) AS total_setoran,
    CASE 
        WHEN COUNT(DISTINCT a.tanggal) > 0 
        THEN COALESCE(SUM(CASE WHEN s.status = 'dikonfirmasi' THEN s.jumlah_setoran ELSE 0 END), 0) / COUNT(DISTINCT a.tanggal)
        ELSE 0 
    END AS rata_rata_setoran_per_hari
FROM supir sp
LEFT JOIN absensi a ON sp.id_supir = a.id_supir
LEFT JOIN setoran s ON sp.id_supir = s.id_supir
WHERE sp.status = 'aktif'
GROUP BY sp.id_supir, sp.nama_supir;

-- ============================================
-- End of Schema
-- ============================================
