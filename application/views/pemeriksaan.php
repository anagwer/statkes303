<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if ($this->session->userdata('role') == 'admin'): ?>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#tambahPemeriksaanModal">
                Tambah Pemeriksaan
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
                        <th>Tanggal</th>
                        <th>TB (cm)</th> <!-- Tambahkan kolom ini -->
                        <th>BB (kg)</th> <!-- Tambahkan kolom ini -->
                        <th>Gula</th>
                        <th>Kolestrol</th>
                        <th>Asam</th>
                        <th>Tekanan</th>
                        <th>Saturasi</th>
                        <th>RR</th>
                        <th>Suhu</th>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($items as $p): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>
                            <?php if (!empty($p->foto) && file_exists(FCPATH . 'assets/img/profil/' . $p->foto)): ?>
                                <img src="<?= base_url('assets/img/profil/' . $p->foto) ?>" width="80" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">–</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p->nip ?? '–') ?> <br> <?= htmlspecialchars($p->nama_anggota ?? '–') ?></td>
                        <td><?= htmlspecialchars($p->jabatan ?? '–') ?></td>
                        <td><?= $p->created_at ? date('d-m-Y', strtotime($p->created_at)) : '–' ?></td>
                        <td><?= htmlspecialchars($p->tb ?: '–') ?></td> <!-- Tambahkan kolom ini -->
                        <td><?= htmlspecialchars($p->bb ?: '–') ?></td> <!-- Tambahkan kolom ini -->
                        <td><?= htmlspecialchars($p->gula ?: '–') ?></td>
                        <td><?= htmlspecialchars($p->kolestrol ?: '–') ?></td>
                        <td><?= htmlspecialchars($p->asam ?: '–') ?></td>
                        <td><?= htmlspecialchars($p->tekanan ?: '–') ?></td>
                        <td><?= htmlspecialchars($p->saturasi ?: '–') ?></td>
                        <td><?= htmlspecialchars($p->rr ?: '–') ?></td>
                        <td><?= htmlspecialchars($p->suhu ?: '–') ?></td>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $p->id ?>">Edit</button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?= $p->id ?>">Hapus</button>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <?php if ($this->session->userdata('role') == 'admin'): ?>
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $p->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pemeriksaan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url('pemeriksaan/edit/' . $p->id) ?>" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Anggota *</label>
                                            <select name="anggota" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <?php foreach ($users as $u): ?>
                                                    <option value="<?= $u->id ?>" <?= $p->anggota == $u->id ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($u->nama) ?> (<?= $u->nip ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group"> <!-- Tambahkan div ini -->
                                            <label>Tinggi Badan (cm)</label>
                                            <input type="number" name="tb" class="form-control" value="<?= htmlspecialchars($p->tb) ?>" min="0" step="0.1"> <!-- Tambahkan input ini -->
                                        </div>
                                        <div class="form-group"> <!-- Tambahkan div ini -->
                                            <label>Berat Badan (kg)</label>
                                            <input type="number" name="bb" class="form-control" value="<?= htmlspecialchars($p->bb) ?>" min="0" step="0.1"> <!-- Tambahkan input ini -->
                                        </div>
                                        <div class="form-group">
                                            <label>Gula Darah</label>
                                            <input type="text" name="gula" class="form-control" value="<?= htmlspecialchars($p->gula) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Kolestrol</label>
                                            <input type="text" name="kolestrol" class="form-control" value="<?= htmlspecialchars($p->kolestrol) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Asam Urat</label>
                                            <input type="text" name="asam" class="form-control" value="<?= htmlspecialchars($p->asam) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Tekanan Darah</label>
                                            <input type="text" name="tekanan" class="form-control" value="<?= htmlspecialchars($p->tekanan) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Nadi</label>
                                            <input type="text" name="nadi" class="form-control" value="<?= htmlspecialchars($p->nadi) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Saturasi O2</label>
                                            <input type="text" name="saturasi" class="form-control" value="<?= htmlspecialchars($p->saturasi) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>RR (Respiratory Rate)</label>
                                            <input type="text" name="rr" class="form-control" value="<?= htmlspecialchars($p->rr) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Suhu (°C)</label>
                                            <input type="text" name="suhu" class="form-control" value="<?= htmlspecialchars($p->suhu) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control"><?= htmlspecialchars($p->keterangan) ?></textarea>
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
                    <div class="modal fade" id="deleteModal<?= $p->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus Data Pemeriksaan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin hapus data pemeriksaan untuk <strong><?= htmlspecialchars($p->nama_anggota ?? '–') ?></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <a href="<?= base_url('pemeriksaan/delete/' . $p->id) ?>" class="btn btn-danger">Hapus</a>
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

<!-- Modal Tambah Pemeriksaan -->
<?php if ($this->session->userdata('role') == 'admin'): ?>
<div class="modal fade" id="tambahPemeriksaanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pemeriksaan Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('pemeriksaan/create') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Anggota *</label>
                        <select name="anggota" class="form-control" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u->id ?>">
                                    <?= htmlspecialchars($u->nama) ?> (<?= $u->nip ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- Tambahkan div ini -->
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="tb" class="form-control" placeholder="Contoh: 170" min="0" step="0.1"> <!-- Tambahkan input ini -->
                    </div>
                    <div class="form-group"> <!-- Tambahkan div ini -->
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="bb" class="form-control" placeholder="Contoh: 65.5" min="0" step="0.1"> <!-- Tambahkan input ini -->
                    </div>
                    <div class="form-group">
                        <label>Gula Darah</label>
                        <input type="text" name="gula" class="form-control" placeholder="Contoh: 120">
                    </div>
                    <div class="form-group">
                        <label>Kolestrol</label>
                        <input type="text" name="kolestrol" class="form-control" placeholder="Contoh: 200">
                    </div>
                    <div class="form-group">
                        <label>Asam Urat</label>
                        <input type="text" name="asam" class="form-control" placeholder="Contoh: 6.5">
                    </div>
                    <div class="form-group">
                        <label>Tekanan Darah</label>
                        <input type="text" name="tekanan" class="form-control" placeholder="Contoh: 120/80">
                    </div>
                    <div class="form-group">
                        <label>Nadi</label>
                        <input type="text" name="nadi" class="form-control" placeholder="Contoh: 72">
                    </div>
                    <div class="form-group">
                        <label>Saturasi O2</label>
                        <input type="text" name="saturasi" class="form-control" placeholder="Contoh: 98">
                    </div>
                    <div class="form-group">
                        <label>RR (x/menit)</label>
                        <input type="text" name="rr" class="form-control" placeholder="Contoh: 16">
                    </div>
                    <div class="form-group">
                        <label>Suhu (°C)</label>
                        <input type="text" name="suhu" class="form-control" placeholder="Contoh: 36.5">
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
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
                <h5 class="modal-title">Export Data Pemeriksaan ke Excel</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('pemeriksaan/export_excel') ?>" method="get">
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
