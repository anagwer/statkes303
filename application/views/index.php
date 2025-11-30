<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Dashboard Statistika Kesehatan</h1>
<!-- Pesan Selamat Datang -->
<div class="alert alert-info">
    <h5><i class="fas fa-info-circle"></i> Selamat Datang, <?= htmlspecialchars($this->session->userdata('nama')) ?>!</h5>
    <p>Anda login sebagai <strong><?= $this->session->userdata('role') == 'admin' ? 'Admin' : 'Anggota' ?></strong>.</p>
</div>

<!-- Info Cards -->
<div class="row">
    <!-- Card untuk Admin: Total User -->
    <?php if ($role === 'admin'): ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_users ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Card untuk Admin: Obat -->
    <?php if ($role === 'admin'): ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Obat Tersedia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_obat ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Card untuk Admin: Alkes -->
    <?php if ($role === 'admin'): ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Alkes Tersedia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_alkes ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-syringe fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Card untuk Admin: Obat Kadaluarsa -->
    <?php if ($role === 'admin'): ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Obat Kadaluarsa dalam 30 Hari</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $obat_expired_soon ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Card untuk Semua Role: Total Donor -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Donor Darah</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_donor ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tint fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card untuk Semua Role: Total Sakit -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Riwayat Sakit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_sakit ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-notes-medical fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Pemeriksaan dan Riwayat -->
<div class="row">
    <!-- Grafik Pemeriksaan -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Pemeriksaan Kesehatan (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="pemeriksaanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Donor -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Grafik Donor Darah (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="donorChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Sakit -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">Grafik Riwayat Sakit (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="sakitChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Pemeriksaan
    const ctx1 = document.getElementById('pemeriksaanChart').getContext('2d');
    const chart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?= $chart_bulan ?>,
            datasets: [{
                label: 'Jumlah Pemeriksaan',
                data: <?= $chart_jumlah ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Grafik Donor
    const ctx2 = document.getElementById('donorChart').getContext('2d');
    const chart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= $chart_bulan_donor ?>,
            datasets: [{
                label: 'Jumlah Donor',
                data: <?= $chart_jumlah_donor ?>,
                backgroundColor: 'rgba(220, 53, 69, 0.6)', // Merah bootstrap danger
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Grafik Sakit
    const ctx3 = document.getElementById('sakitChart').getContext('2d');
    const chart3 = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?= $chart_bulan_sakit ?>,
            datasets: [{
                label: 'Jumlah Riwayat Sakit',
                data: <?= $chart_jumlah_sakit ?>,
                backgroundColor: 'rgba(108, 117, 125, 0.6)', // Abu-abu bootstrap secondary
                borderColor: 'rgba(108, 117, 125, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
