<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include('../config/koneksi.php');

$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// Query total statistik
$query_total = "SELECT 
    (SELECT COUNT(*) FROM pegawai) as total_pegawai,
    (SELECT COUNT(*) FROM departemen) as total_departemen,
    (SELECT COUNT(*) FROM jabatan) as total_jabatan,
    (SELECT SUM(gaji) FROM pegawai) as total_gaji";
$result_total = mysqli_query($koneksi, $query_total);
$data_total = mysqli_fetch_assoc($result_total);

$total_pegawai = $data_total['total_pegawai'];
$total_departemen = $data_total['total_departemen'];
$total_jabatan = $data_total['total_jabatan'];
$total_gaji = $data_total['total_gaji'];

// Query untuk gender
$query_gender = "SELECT 
    SUM(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) as pria,
    SUM(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) as wanita
    FROM pegawai";
$result_gender = mysqli_query($koneksi, $query_gender);
$data_gender = mysqli_fetch_assoc($result_gender);

$pria = $data_gender['pria'] ?? 0;
$wanita = $data_gender['wanita'] ?? 0;

// Query untuk departemen
$query_departemen = "SELECT 
    d.nama_departemen, 
    COUNT(p.id_pegawai) as jumlah_pegawai 
    FROM departemen d
    LEFT JOIN pegawai p ON d.id_departemen = p.id_departemen
    GROUP BY d.id_departemen, d.nama_departemen";
$result_departemen = mysqli_query($koneksi, $query_departemen);

$departemen_labels = [];
$departemen_data = [];

while ($row = mysqli_fetch_assoc($result_departemen)) {
    $departemen_labels[] = $row['nama_departemen'];
    $departemen_data[] = $row['jumlah_pegawai'];
}

// Query statistik tambahan
$query_statistik = "SELECT 
    (SELECT nama_departemen FROM departemen d 
     LEFT JOIN pegawai p ON d.id_departemen = p.id_departemen 
     GROUP BY d.id_departemen, d.nama_departemen 
     ORDER BY COUNT(p.id_pegawai) DESC 
     LIMIT 1) as departemen_terbesar,
    (SELECT AVG(gaji) FROM pegawai) as rata_gaji,
    (SELECT COUNT(*) FROM pegawai WHERE jenis_kelamin = 'Laki-laki') as pegawai_pria,
    (SELECT COUNT(*) FROM pegawai WHERE jenis_kelamin = 'Perempuan') as pegawai_wanita";
$result_statistik = mysqli_query($koneksi, $query_statistik);
$data_statistik = mysqli_fetch_assoc($result_statistik);

$departemen_terbesar = $data_statistik['departemen_terbesar'];
$rata_gaji = $data_statistik['rata_gaji'];
$pegawai_pria = $data_statistik['pegawai_pria'];
$pegawai_wanita = $data_statistik['pegawai_wanita'];

include('view/header.php');
?>

<main class="content">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 text-white p-3 rounded" style="background-color: #2c3e50;">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Total Pegawai</h6>
                            <h4 class="mb-0"><?= number_format($total_pegawai) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 text-white p-3 rounded" style="background-color: #34495e;">
                            <i class="bi bi-building fs-2"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Total Departemen</h6>
                            <h4 class="mb-0"><?= number_format($total_departemen) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 text-white p-3 rounded" style="background-color: #2c3e50;">
                            <i class="bi bi-briefcase fs-2"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Total Jabatan</h6>
                            <h4 class="mb-0"><?= number_format($total_jabatan) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 text-white p-3 rounded" style="background-color: #34495e;">
                            <i class="bi bi-cash-coin fs-2"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Total Gaji</h6>
                            <h4 class="mb-0">Rp. <?= number_format($total_gaji, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik dan Informasi -->
        <div class="row g-3 mt-3">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-70">
                    <div class="card-body">
                        <canvas id="genderChart" width="100%" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-70">
                    <div class="card-body">
                        <canvas id="departemenChart" width="100%" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Gender
    var genderCtx = document.getElementById('genderChart').getContext('2d');
    var genderChart = new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: ['Pria', 'Wanita'],
            datasets: [{
                data: [<?= $pria ?>, <?= $wanita ?>],
                backgroundColor: ['#2c3e50', '#3498db'], 
                hoverBackgroundColor: ['#34495e', '#2980b9']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        font: {
                            size: 10
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Komposisi Gender Pegawai',
                    font: {
                        size: 14
                    }
                }
            }
        }
    });

    // Grafik Pegawai per Departemen
    var departemenCtx = document.getElementById('departemenChart').getContext('2d');
    var departemenChart = new Chart(departemenCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($departemen_labels) ?>,
            datasets: [{
                data: <?= json_encode($departemen_data) ?>,
                backgroundColor: [
                    '#0d47a1',   
                    '#e74c3c',   
                    '#2196f3',   
                    '#1abc9c',   
                    '#f39c12'    
                ],
                hoverBackgroundColor: [
                    '#1565c0',   
                    '#c0392b',   
                    '#2980b9',   
                    '#16a085',   
                    '#d35400' 
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        font: {
                            size: 10
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Pegawai per Departemen',
                    font: {
                        size: 14
                    }
                }
            }
        }
    });
});
</script>

<?php include('view/footer.php'); ?>