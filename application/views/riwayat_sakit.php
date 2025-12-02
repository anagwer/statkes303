<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if ($this->session->userdata('role') == 'admin'): ?>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#tambahSakitModal">
                Tambah Riwayat Sakit
            </button>
            <!-- Tombol Export dan Filter -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exportModal">
                Export Excel
            </button>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Anggota</th>
                        <th>Jabatan</th>
						<th>Penyakit</th> 
                        <th>Tanggal Sakit</th>
						<th>Bukti</th>          
						<th>Rekomendasi</th>
						<th>Obat</th>           
                        <th>Keterangan</th>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($riwayat as $r): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>
                            <?php if (!empty($r->foto) && file_exists(FCPATH . 'assets/img/profil/' . $r->foto)): ?>
                                <img src="<?= base_url('assets/img/profil/' . $r->foto) ?>" width="80" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">–</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r->nip ?? '–') ?> - <?= htmlspecialchars($r->nama_anggota ?? '–') ?></td>
						<td><?= htmlspecialchars($r->jabatan ?? '–') ?></td>
						<td><?= htmlspecialchars($r->sakit ?? '–') ?></td> <!-- BARU -->
						<td><?= $r->tanggal_sakit ? date('d-m-Y', strtotime($r->tanggal_sakit)) : '–' ?></td>
						<td>
							<?php if (!empty($r->bukti)): ?>
								<?php
								$ext = pathinfo($r->bukti, PATHINFO_EXTENSION);
								$filePath = FCPATH . 'assets/uploads/bukti_sakit/' . $r->bukti;
								if (file_exists($filePath)) {
									if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
										echo '<a href="' . base_url('assets/uploads/bukti_sakit/' . $r->bukti) . '" target="_blank" class="badge badge-primary">Lihat Gambar</a>';
									} else {
										echo '<a href="' . base_url('assets/uploads/bukti_sakit/' . $r->bukti) . '" target="_blank" class="badge badge-primary">Lihat PDF</a>';
									}
								} else {
									echo '<span class="text-muted">File tidak ditemukan</span>';
								}
								?>
							<?php else: ?>
								<span class="text-muted">–</span>
							<?php endif; ?>
						</td>
						<td><?= htmlspecialchars($r->rekomendasi ?? '–') ?></td> <!-- BARU -->
						<td><?= htmlspecialchars($r->obat ?? '–') ?></td> <!-- BARU -->
						<td><?= htmlspecialchars($r->keterangan ?? '–') ?></td>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $r->id ?>">Edit</button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?= $r->id ?>">Hapus</button>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <?php if ($this->session->userdata('role') == 'admin'): ?>
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $r->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Riwayat Sakit</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url('riwayat_sakit/edit/' . $r->id) ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Anggota *</label>
                                            <select name="id_user" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <?php foreach ($users as $u): ?>
                                                    <option value="<?= $u->id ?>" <?= $r->id_user == $u->id ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($u->nama) ?> (<?= $u->nip ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
										<div class="form-group">
											<label>Penyakit *</label>
											<input type="text" name="sakit" class="form-control" value="<?= htmlspecialchars($r->sakit ?? '') ?>" required>
										</div>
                                        <div class="form-group">
                                            <label>Tanggal Sakit *</label>
                                            <input type="date" name="tanggal_sakit" class="form-control" value="<?= $r->tanggal_sakit ?>" required>
                                        </div>
										<div class="form-group">
											<label>Bukti (PDF/Gambar)</label>
											<input type="file" name="bukti" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
											<?php if (isset($r->bukti) && !empty($r->bukti)): ?>
												<small class="form-text text-muted">
													File saat ini: 
													<?php
													$ext = pathinfo($r->bukti, PATHINFO_EXTENSION);
													if (in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) {
														echo '<a href="' . base_url('assets/uploads/bukti_sakit/' . $r->bukti) . '" target="_blank">Lihat</a>';
													} else {
														echo '<a href="' . base_url('assets/uploads/bukti_sakit/' . $r->bukti) . '" target="_blank">Download</a>';
													}
													?>
												</small>
											<?php endif; ?>
										</div>

										<!-- Rekomendasi -->
										<div class="form-group">
											<label>Rekomendasi</label>
											<textarea name="rekomendasi" class="form-control" rows="2"><?= htmlspecialchars($r->rekomendasi ?? '') ?></textarea>
										</div>

										<!-- Obat -->
										<div class="form-group">
											<label>Obat</label>
											<textarea name="obat" class="form-control" rows="2"><?= htmlspecialchars($r->obat ?? '') ?></textarea>
										</div>
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($r->keterangan) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteModal<?= $r->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin hapus riwayat sakit dari <strong><?= htmlspecialchars($r->nama_anggota ?? 'User Tidak Ditemukan') ?></strong> pada tanggal <strong><?= $r->tanggal_sakit ? date('d-m-Y', strtotime($r->tanggal_sakit)) : '–' ?></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <a href="<?= base_url('riwayat_sakit/delete/' . $r->id) ?>" class="btn btn-danger">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<?php if ($this->session->userdata('role') == 'admin'): ?>
<div class="modal fade" id="tambahSakitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Riwayat Sakit</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('riwayat_sakit/create') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Anggota *</label>
                        <select name="id_user" class="form-control" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u->id ?>">
                                    <?= htmlspecialchars($u->nama) ?> (<?= $u->nip ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
					<div class="form-group">
						<label>Penyakit *</label>
						<input type="text" name="sakit" class="form-control" placeholder="masukkan penyakit" required>
					</div>
                    <div class="form-group">
                        <label>Tanggal Sakit *</label>
                        <input type="date" name="tanggal_sakit" class="form-control" required>
                    </div>
					<div class="form-group">
						<label>Bukti (PDF/Gambar)</label>
						<input type="file" name="bukti" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
					</div>

					<!-- Rekomendasi -->
					<div class="form-group">
						<label>Rekomendasi</label>
						<textarea name="rekomendasi" class="form-control" rows="2"></textarea>
					</div>

					<!-- Obat -->
					<div class="form-group">
						<label>Obat</label>
						<textarea name="obat" class="form-control" rows="2"></textarea>
					</div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Export Excel -->
<?php if ($this->session->userdata('role') == 'admin'): ?>
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Riwayat Sakit ke Excel</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('riwayat_sakit/export_excel') ?>" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Anggota (Opsional)</label>
                        <select name="id_user" class="form-control">
                            <option value="">Semua Anggota</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u->id ?>">
                                    <?= htmlspecialchars($u->nama) ?> (<?= $u->nip ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Tanggal Awal</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
