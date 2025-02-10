<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include('../config/koneksi.php');

$pageTitle = 'Manajemen Pegawai';
$activePage = 'pegawai';
include('view/header.php');

// Ambil data pegawai dengan informasi jabatan dan departemen
$query = "SELECT pegawai.*, jabatan.nama_jabatan, departemen.nama_departemen 
          FROM pegawai 
          JOIN jabatan ON pegawai.id_jabatan = jabatan.id_jabatan
          JOIN departemen ON pegawai.id_departemen = departemen.id_departemen";
$result = mysqli_query($koneksi, $query);

// Ambil daftar jabatan untuk dropdown
$query_jabatan = "SELECT * FROM jabatan";
$jabatan_result = mysqli_query($koneksi, $query_jabatan);

// Ambil daftar departemen untuk dropdown
$query_departemen = "SELECT * FROM departemen";
$departemen_result = mysqli_query($koneksi, $query_departemen);
?>
<main class="content">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-4" style="color: #2c3e50;">Manajemen Pegawai</h1>
            </div>
        </div>

        <style>
            .card-header {
                background: linear-gradient(135deg, #2c3e50, #34495e);
                color: white;
            }
            .btn-tambah {
                background-color: #3498db;
                border-color: #3498db;
                color: white;
                transition: all 0.3s ease;
            }
            .btn-tambah:hover {
                background-color: #2980b9;
                border-color: #2980b9;
            }
            .table thead {
                background-color: #34495e;
                color: white;
            }
            .table-hover tbody tr:hover {
                background-color: rgba(52, 73, 94, 0.1);
            }
            .btn-aksi-edit {
                background-color: #3498db;
                color: white;
            }
            .btn-aksi-hapus {
                background-color: #e74c3c;
                color: white;
            }
        </style>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Pegawai</h5>
                    <button type="button" class="btn btn-tambah" data-bs-toggle="modal" data-bs-target="#tambahPegawaiModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Pegawai
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Gaji</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nip']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                                <td><?= htmlspecialchars($row['nama_jabatan']) ?></td>
                                <td><?= htmlspecialchars($row['nama_departemen']) ?></td>
                                <td>Rp. <?= number_format($row['gaji'], 0, ',', '.') ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" class="btn btn-sm btn-aksi-edit" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#editPegawaiModal<?= $row['id_pegawai'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../proses/hapus_pegawai.php?id=<?= $row['id_pegawai'] ?>" 
                                           class="btn btn-sm btn-aksi-hapus" 
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Pegawai -->
                            <div class="modal fade" id="editPegawaiModal<?= $row['id_pegawai'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #2c3e50; color: white;">
                                            <h5 class="modal-title">Edit Pegawai</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="../proses/edit_pegawai.php" method="post">
                                            <input type="hidden" name="id_pegawai" value="<?= $row['id_pegawai'] ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">NIP</label>
                                                    <input type="text" name="nip" class="form-control" 
                                                           value="<?= htmlspecialchars($row['nip']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama</label>
                                                    <input type="text" name="nama" class="form-control" 
                                                           value="<?= htmlspecialchars($row['nama']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" 
                                                           value="<?= htmlspecialchars($row['email']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <select name="jenis_kelamin" class="form-select" required>
                                                        <option value="Laki-laki" <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                                        <option value="Perempuan" <?= $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jabatan</label>
                                                    <select name="id_jabatan" class="form-select" required>
                                                        <?php 
                                                        mysqli_data_seek($jabatan_result, 0);
                                                        while($jbt = mysqli_fetch_assoc($jabatan_result)): ?>
                                                            <option value="<?= $jbt['id_jabatan'] ?>" 
                                                                <?= $jbt['id_jabatan'] == $row['id_jabatan'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($jbt['nama_jabatan']) ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Departemen</label>
                                                    <select name="id_departemen" class="form-select" required>
                                                        <?php 
                                                        mysqli_data_seek($departemen_result, 0);
                                                        while($dept = mysqli_fetch_assoc($departemen_result)): ?>
                                                            <option value="<?= $dept['id_departemen'] ?>" 
                                                                <?= $dept['id_departemen'] == $row['id_departemen'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($dept['nama_departemen']) ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gaji</label>
                                                    <input type="text" name="gaji" class="form-control rupiah" 
                                                           value="<?= number_format($row['gaji'], 0, ',', '.') ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn" style="background-color: #3498db; color: white;">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Pegawai -->
        <div class="modal fade" id="tambahPegawaiModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #2c3e50; color: white;">
                        <h5 class="modal-title">Tambah Pegawai</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="../proses/tambah_pegawai.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <select name="id_jabatan" class="form-select" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php 
                                    mysqli_data_seek($jabatan_result, 0);
                                    while($jbt = mysqli_fetch_assoc($jabatan_result)): ?>
                                        <option value="<?= $jbt['id_jabatan'] ?>">
                                            <?= htmlspecialchars($jbt['nama_jabatan']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Departemen</label>
                                <select name="id_departemen" class="form-select" required>
                                    <option value="">Pilih Departemen</option>
                                    <?php 
                                    mysqli_data_seek($departemen_result, 0);
                                    while($dept = mysqli_fetch_assoc($departemen_result)): ?>
                                        <option value="<?= $dept['id_departemen'] ?>">
                                            <?= htmlspecialchars($dept['nama_departemen']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gaji</label>
                                <input type="text" name="gaji" class="form-control rupiah" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn" style="background-color: #3498db; color: white;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include('view/footer.php'); ?>