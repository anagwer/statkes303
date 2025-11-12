<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if ($this->session->userdata('role') == 'admin'): ?>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#tambahDonorModal">
                Tambah Riwayat Donor
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
                        <th>Tanggal Donor</th>
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
                        <td><?= $r->tanggal_donor ? date('d-m-Y', strtotime($r->tanggal_donor)) : '–' ?></td>
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
                                    <h5 class="modal-title">Edit Riwayat Donor</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url('riwayat_donor/edit/' . $r->id) ?>" method="post">
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
                                            <label>Tanggal Donor *</label>
                                            <input type="date" name="tanggal_donor" class="form-control" value="<?= $r->tanggal_donor ?>" required>
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
                                    <p>Yakin hapus riwayat donor dari <strong><?= htmlspecialchars($r->nama_anggota ?? 'User Tidak Ditemukan') ?></strong> pada tanggal <strong><?= $r->tanggal_donor ? date('d-m-Y', strtotime($r->tanggal_donor)) : '–' ?></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <a href="<?= base_url('riwayat_donor/delete/' . $r->id) ?>" class="btn btn-danger">Hapus</a>
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
<div class="modal fade" id="tambahDonorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Riwayat Donor</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('riwayat_donor/create') ?>" method="post">
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
                        <label>Tanggal Donor *</label>
                        <input type="date" name="tanggal_donor" class="form-control" required>
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
                <h5 class="modal-title">Export Riwayat Donor ke Excel</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('riwayat_donor/export_excel') ?>" method="get">
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
