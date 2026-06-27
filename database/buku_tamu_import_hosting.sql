-- Buku Tamu Digital — import ke database hosting
-- Database: u700125577_santri (satu DB bersama landing page & undangan)
-- Tabel user: bt_users (bukan users, agar tidak bentrok dengan modul lain)
--
-- Cara import: phpMyAdmin → pilih u700125577_santri → Import → file ini → Go
-- Login default: admin / admin123 | ndalem / ndalem123

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

USE `u700125577_santri`;

DROP TABLE IF EXISTS `whatsapp_logs`;
DROP TABLE IF EXISTS `visitors`;
DROP TABLE IF EXISTS `jadwal_terima_tamu`;
DROP TABLE IF EXISTS `pengasuh_status`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `bt_users`;

CREATE TABLE `bt_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'ndalem') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengasuh_status` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `status` ENUM('available', 'busy', 'closed') NOT NULL DEFAULT 'available',
    `message` VARCHAR(255) DEFAULT NULL,
    `updated_by` INT DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`updated_by`) REFERENCES `bt_users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `visitors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_code` VARCHAR(20) NOT NULL UNIQUE,
    `queue_number` INT NOT NULL,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `no_hp` VARCHAR(20) NOT NULL,
    `asal` VARCHAR(150) NOT NULL,
    `jumlah_rombongan` ENUM('1', '2-5', '>5') NOT NULL DEFAULT '1',
    `tujuan_kunjungan` ENUM('sowan', 'jenguk', 'administrasi', 'kerjasama', 'lainnya') NOT NULL,
    `detail_keperluan` TEXT,
    `nama_santri` VARCHAR(150) DEFAULT NULL,
    `foto_path` VARCHAR(255) DEFAULT NULL,
    `jenis_kedatangan` ENUM('sekarang', 'jadwal') NOT NULL DEFAULT 'sekarang',
    `jadwal_kunjungan` DATETIME DEFAULT NULL,
    `waktu_temu` DATETIME DEFAULT NULL,
    `area_masuk` ENUM('pesantren', 'ndalem') NOT NULL DEFAULT 'pesantren',
    `status` ENUM('pending', 'checked_in', 'in_queue', 'approved', 'called', 'completed', 'checked_out', 'rejected') NOT NULL DEFAULT 'pending',
    `hijri_date` VARCHAR(50) DEFAULT NULL,
    `checked_in_at` DATETIME DEFAULT NULL,
    `checked_out_at` DATETIME DEFAULT NULL,
    `approved_at` DATETIME DEFAULT NULL,
    `approved_by` INT DEFAULT NULL,
    `whatsapp_sent` TINYINT(1) DEFAULT 0,
    `staff_wa_notified` TINYINT(1) DEFAULT 0,
    `jadwal_wa_notified` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_created` (`created_at`),
    INDEX `idx_tujuan` (`tujuan_kunjungan`),
    FOREIGN KEY (`approved_by`) REFERENCES `bt_users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `whatsapp_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `visitor_id` INT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `recipient_type` VARCHAR(30) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    `response` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jadwal_terima_tamu` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `area` ENUM('pesantren', 'ndalem') NOT NULL,
    `hari` TINYINT NOT NULL COMMENT '0=Minggu .. 6=Sabtu',
    `jam_mulai` TIME NOT NULL,
    `jam_selesai` TIME NOT NULL,
    `keterangan` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_area_hari` (`area`, `hari`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
    `setting_key` VARCHAR(50) PRIMARY KEY,
    `setting_value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bt_users` (`username`, `password`, `name`, `role`) VALUES
('admin', '$2y$10$Y7Pv5Pv3tdjiFxtyEJXCR.YGQEsL4RQJPw7oXry360RzwvZzqstru', 'Petugas Keamanan', 'admin'),
('ndalem', '$2y$10$AXmi2nh7fUSamKu3uYR7Pu5PwBSkLcxHGhYYzUOXephWJ4bP8oo0S', 'Asisten Pengasuh', 'ndalem');

INSERT INTO `pengasuh_status` (`status`, `message`) VALUES
('available', 'Pengasuh sedang luang dan menerima tamu.');

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('pesantren_name', 'A.P.I Nailul Muna'),
('pesantren_address', 'Jl. Pesantren No. 1, Desa Muna, Kecamatan Ilmu, Jawa Timur'),
('whatsapp_provider', 'fonnte'),
('whatsapp_token', ''),
('whatsapp_enabled', '0'),
('ndalem_ruang', 'Ruang Tunggu Ndalem Barat'),
('daily_queue_counter', '0'),
('daily_queue_date', CURDATE()),
('wa_phone_pengasuh', ''),
('wa_phone_ndalem', ''),
('wa_phone_kantor', ''),
('wa_enabled_pengasuh', '1'),
('wa_enabled_ndalem', '1'),
('wa_enabled_kantor', '1'),
('wa_on_register_pengasuh', '1'),
('wa_on_register_ndalem', '1'),
('wa_on_register_kantor', '1'),
('wa_on_checkin_pengasuh', '1'),
('wa_on_checkin_ndalem', '1'),
('wa_on_checkin_kantor', '1'),
('wa_on_jadwal_pengasuh', '1'),
('wa_on_jadwal_ndalem', '1'),
('wa_on_jadwal_kantor', '1'),
('wa_on_approve_guest', '1'),
('wa_jadwal_reminder_minutes', '60');

SET FOREIGN_KEY_CHECKS = 1;
