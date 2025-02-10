-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Feb 2025 pada 01.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pegawai`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `departemen`
--

CREATE TABLE `departemen` (
  `id_departemen` int(11) NOT NULL,
  `nama_departemen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `departemen`
--

INSERT INTO `departemen` (`id_departemen`, `nama_departemen`) VALUES
(21, 'Kesehatan & Keselamatan Kerja (K3)'),
(22, 'Hubungan Masyarakat '),
(23, 'Analis Data & Statistik'),
(24, 'Pengembangan Bisnis'),
(25, 'Manajemen Proyek');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(100) DEFAULT NULL,
  `id_departemen` int(11) DEFAULT NULL,
  `gaji` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `id_departemen`, `gaji`) VALUES
(23, 'Kepala Humas', 22, 20000000.00),
(24, 'Spesialis Komunikasi', 22, 10000000.00),
(25, 'Data Scientist', 23, 25000000.00),
(26, 'Analis Data ', 23, 15000000.00),
(27, 'Business Development Manager', 24, 18000000.00),
(28, 'Analis Pengembangan Bisnis', 24, 11000000.00),
(29, 'Manajer K3', 21, 17000000.00),
(30, 'Spesialis Keselamatan Kerja', 21, 12000000.00),
(32, 'Koordinator Proyek ', 25, 12000000.00),
(33, 'Machine Learning Engineer ', 23, 20000000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `id_jabatan` int(11) DEFAULT NULL,
  `id_departemen` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `gaji` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `nip`, `id_jabatan`, `id_departemen`, `email`, `jenis_kelamin`, `gaji`) VALUES
(1, 'Andika Pratama', '1092837465', 29, 21, 'andika.pratama@email.com', 'Laki-laki', 17000000.00),
(2, 'Siti', '5628394710', 30, 21, 'siti.marlina@email.com', 'Perempuan', 12000000.00),
(3, 'Fitri Handayani', '9283746512', 24, 22, 'fitri.handayani@email.com', 'Perempuan', 10000000.00),
(4, 'Lestari Wijayanti', '8374629102', 28, 24, 'lestari.wijayanti@email.com', 'Perempuan', 11000000.00),
(5, 'Farhan Alamsyah', '29384756019', 27, 24, 'farhan.alamsyah@email.com', 'Laki-laki', 18000000.00),
(6, 'Haryanti Putra', '7482910374', 23, 22, 'haryanti.putri@email.com', 'Perempuan', 20000000.00),
(7, 'Mariam Sekar', '9203847561', 26, 23, 'mariam.sekar@gmail.com', 'Perempuan', 15000000.00),
(8, 'Dewi Purnamasari', '7382910465', 27, 24, 'dewi.purnamasari@email.com', 'Perempuan', 18000000.00),
(9, 'Maulana Saputra', '1975463783', 33, 23, 'maulana.saputra@gmail.com', 'Laki-laki', 20000000.00);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id_departemen`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`),
  ADD KEY `id_departemen` (`id_departemen`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `id_jabatan` (`id_jabatan`),
  ADD KEY `id_departemen` (`id_departemen`),
  ADD KEY `nip_2` (`nip`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id_departemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD CONSTRAINT `jabatan_ibfk_1` FOREIGN KEY (`id_departemen`) REFERENCES `departemen` (`id_departemen`);

--
-- Ketidakleluasaan untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`),
  ADD CONSTRAINT `pegawai_ibfk_2` FOREIGN KEY (`id_departemen`) REFERENCES `departemen` (`id_departemen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
