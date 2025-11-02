<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if ($this->session->userdata('role') == 'admin'): ?>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahModal">
        Tambah <?= $jenis == 'obat' ? 'Obat' : 'Alkes' ?>
    </button>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($items as $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>
                            <?php if ($item->foto && file_exists(FCPATH . 'assets/img/alkes/' . $item->foto)): ?>
                                <img src="<?= base_url('assets/img/alkes/' . $item->foto) ?>" width="150" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">–</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item->nama) ?></td>
                        <td><?= $item->stok ?></td>
                        <td><?= htmlspecialchars($item->satuan) ?></td>
                        <td><?= htmlspecialchars($item->keterangan ?: '–') ?></td>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $item->id ?>">Edit</button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?= $item->id ?>">Hapus</button>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $item->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit <?= $jenis == 'obat' ? 'Obat' : 'Alkes' ?></h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url($jenis . '/edit/' . $item->id) ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Nama *</label>
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($item->nama) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Stok *</label>
                                            <input type="number" name="stok" class="form-control" value="<?= $item->stok ?>" required min="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Satuan *</label>
                                            <input type="text" name="satuan" class="form-control" value="<?= htmlspecialchars($item->satuan) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control"><?= htmlspecialchars($item->keterangan) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto Saat Ini</label><br>
                                            <?php if ($item->foto && file_exists(FCPATH . 'assets/img/alkes/' . $item->foto)): ?>
                                                <img src="<?= base_url('assets/img/alkes/' . $item->foto) ?>" width="80" class="mb-2 rounded">
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada foto</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Ganti Foto (opsional)</label>
                                            <input type="file" name="foto" class="form-control-file">
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
                    <div class="modal fade" id="deleteModal<?= $item->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus <?= $jenis == 'obat' ? 'Obat' : 'Alkes' ?></h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin hapus <strong><?= htmlspecialchars($item->nama) ?></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <a href="<?= base_url($jenis . '/delete/' . $item->id) ?>" class="btn btn-danger">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<?php if ($this->session->userdata('role') == 'admin'): ?>
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah <?= $jenis == 'obat' ? 'Obat' : 'Alkes' ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url($jenis . '/create') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama *</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stok *</label>
                        <input type="number" name="stok" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Satuan *</label>
                        <input type="text" name="satuan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto (opsional)</label>
                        <input type="file" name="foto" class="form-control-file">
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
