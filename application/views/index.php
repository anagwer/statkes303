<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Dashboard Statkes 303</h1>
<!-- Pesan Selamat Datang -->
<div class="alert alert-info">
    <h5><i class="fas fa-info-circle"></i> Selamat Datang, <?= htmlspecialchars($this->session->userdata('nama')) ?>!</h5>
    <p>Anda login sebagai <strong><?= $this->session->userdata('role') == 'admin' ? 'Admin' : 'Anggota' ?></strong>.</p>
</div>

<!-- Info Cards -->
<div class="row">
    <!-- Total User -->
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

    <!-- Obat -->
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

    <!-- Alkes -->
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

    <!-- Obat Kadaluarsa -->
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
</div>

<!-- Grafik Pemeriksaan -->
<div class="row">
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
</div>

<!-- Chart.js Script -->
<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('pemeriksaanChart').getContext('2d');
    const chart = new Chart(ctx, {
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
});
</script>
