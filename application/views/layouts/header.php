<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?> - Aplikasi Statkes</title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
		<!-- DataTables CSS -->
		<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php if ($this->session->userdata('logged_in')): ?>
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
								<div class="sidebar-brand-icon">
										<i class="fas fa-stethoscope"></i>
								</div>
								<div class="sidebar-brand-text mx-3">Statkes 303</div>
						</a>
            <hr class="sidebar-divider">
						<li class="nav-item">
								<a class="nav-link" href="<?= base_url('dashboard') ?>">
										<i class="fas fa-fw fa-tachometer-alt"></i>
										<span>Dashboard</span>
								</a>
						</li>
						<!-- Heading: Data Master -->
						<div class="sidebar-heading">
								Data Master
						</div>
						<li class="nav-item">
								<a class="nav-link" href="<?= base_url('obat') ?>">
										<i class="fas fa-fw fa-pills"></i>
										<span>Ketersediaan Obat</span>
								</a>
						</li>
						<li class="nav-item">
								<a class="nav-link" href="<?= base_url('alkes') ?>">
										<i class="fas fa-fw fa-syringe"></i>
										<span>Ketersediaan Alkes</span>
								</a>
						</li>
						<li class="nav-item">
								<a class="nav-link" href="<?= base_url('pemeriksaan') ?>">
										<i class="fas fa-fw fa-heartbeat"></i>
										<span>Data Medis Pegawai</span>
								</a>
						</li>
						<?php if ($this->session->userdata('role') == 'admin'): ?>
								<!-- Heading: Manajemen Pengguna -->
								<div class="sidebar-heading">
										Manajemen Pengguna
								</div>
								<li class="nav-item">
										<a class="nav-link" href="<?= base_url('user') ?>">
												<i class="fas fa-fw fa-users"></i>
												<span>Manajemen User</span>
										</a>
								</li>
						<?php endif; ?>
            <hr class="sidebar-divider d-none d-md-block">
        </ul>
        <!-- End Sidebar -->
        <?php endif; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <?php if ($this->session->userdata('logged_in')): ?>
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
									<ul class="navbar-nav ml-auto">
											<li class="nav-item dropdown no-arrow">
													<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
															<!-- Foto Profil -->
															<?php
															$foto = $this->session->userdata('foto');
															$foto_path = !empty($foto) && file_exists(FCPATH . 'assets/img/profil/' . $foto)
																	? base_url('assets/img/profil/' . $foto)
																	: base_url('assets/img/undraw_profile.svg');
															?>
															<img class="img-profile rounded-circle" src="<?= $foto_path ?>" style="width: 32px; height: 32px; object-fit: cover;">
															<span class="mr-2 d-none d-lg-inline text-gray-600 small">
																	<?= htmlspecialchars($this->session->userdata('nama')) ?>
															</span>
															<!-- Ikon Dropdown (panah bawah) -->
															<i class="fas fa-chevron-down fa-sm fa-fw text-gray-400"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
															<!-- Menu Profil -->
															<a class="dropdown-item" href="<?= base_url('profil') ?>">
																	<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
																	Profil
															</a>
															<div class="dropdown-divider"></div>
															<!-- Menu Logout -->
															<a class="dropdown-item" href="<?= base_url('auth/logout') ?>" data-toggle="modal" data-target="#logoutModal">
																	<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
																	Logout
															</a>
													</div>
											</li>
									</ul>
							</nav>
                <?php endif; ?>
                <!-- End Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>
