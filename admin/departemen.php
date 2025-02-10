<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include('../config/koneksi.php');

$pageTitle = 'Manajemen Departemen';
$activePage = 'departemen';
include('view/header.php');

// Ambil data departemen
$query = "SELECT * FROM departemen";
$result = mysqli_query($koneksi, $query);
?>
<main class="content">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-4" style="color: #2c3e50;">Manajemen Departemen</h1>
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
                    <h5 class="card-title mb-0">Daftar Departemen</h5>
                    <button type="button" class="btn btn-tambah" data-bs-toggle="modal" data-bs-target="#tambahDepartemenModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Departemen
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Departemen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_departemen']) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" class="btn btn-sm btn-aksi-edit" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#editDepartemenModal<?= $row['id_departemen'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../proses/hapus_departemen.php?id=<?= $row['id_departemen'] ?>" 
                                           class="btn btn-sm btn-aksi-hapus" 
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Departemen -->
                            <div class="modal fade" id="editDepartemenModal<?= $row['id_departemen'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #2c3e50; color: white;">
                                            <h5 class="modal-title">Edit Departemen</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="../proses/edit_departemen.php" method="post">
                                            <input type="hidden" name="id_departemen" value="<?= $row['id_departemen'] ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Departemen</label>
                                                    <input type="text" name="nama_departemen" class="form-control" 
                                                           value="<?= htmlspecialchars($row['nama_departemen']) ?>" required>
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

        <!-- Modal Tambah Departemen -->
        <div class="modal fade" id="tambahDepartemenModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #2c3e50; color: white;">
                        <h5 class="modal-title">Tambah Departemen</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="../proses/tambah_departemen.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Departemen</label>
                                <input type="text" name="nama_departemen" class="form-control" required>
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