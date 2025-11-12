<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if ($this->session->userdata('role') == 'admin'): ?>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahUserModal">
        Tambah User
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
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
						<th>Gol. Darah</th>
                        <th>Role</th>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($users as $u): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>
                            <?php if (!empty($u->foto) && file_exists(FCPATH . 'assets/img/profil/' . $u->foto)): ?>
                                <img src="<?= base_url('assets/img/profil/' . $u->foto) ?>" width="200" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">–</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u->nip) ?></td>
                        <td><?= htmlspecialchars($u->nama) ?></td>
                        <td><?= htmlspecialchars($u->jabatan) ?></td>
						<td><?= !empty($u->goldar) ? htmlspecialchars($u->goldar) : '–' ?></td>
                        <td><?= ucfirst($u->role) ?></td>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $u->id ?>">Edit</button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?= $u->id ?>">Hapus</button>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $u->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit User</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url('user/edit/' . $u->id) ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>NIP</label>
                                            <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($u->nip) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($u->nama) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($u->jabatan) ?>">
                                        </div>
										<div class="form-group">
											<label>Golongan Darah</label>
											<select name="goldar" class="form-control">
												<option value="">Pilih</option>
												<option value="A" <?= $u->goldar == 'A' ? 'selected' : '' ?>>A</option>
												<option value="B" <?= $u->goldar == 'B' ? 'selected' : '' ?>>B</option>
												<option value="AB" <?= $u->goldar == 'AB' ? 'selected' : '' ?>>AB</option>
												<option value="O" <?= $u->goldar == 'O' ? 'selected' : '' ?>>O</option>
											</select>
										</div>
                                        <div class="form-group">
                                            <label>Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($u->tempat_lahir) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control" value="<?= $u->tanggal_lahir ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="role" class="form-control" required>
                                                <option value="admin" <?= $u->role == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                <option value="user" <?= $u->role == 'user' ? 'selected' : '' ?>>User</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Password Baru (Kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Foto Saat Ini</label><br>
                                            <?php if (!empty($u->foto) && file_exists(FCPATH . 'assets/img/profil/' . $u->foto)): ?>
                                                <img src="<?= base_url('assets/img/profil/' . $u->foto) ?>" width="80" class="mb-2 rounded">
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada foto</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Ganti Foto (opsional, JPG/PNG, max 2MB)</label>
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
                    <div class="modal fade" id="deleteModal<?= $u->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin hapus user <strong><?= htmlspecialchars($u->nama) ?></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <a href="<?= base_url('user/delete/' . $u->id) ?>" class="btn btn-danger">Hapus</a>
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

<!-- Modal Tambah User -->
<?php if ($this->session->userdata('role') == 'admin'): ?>
<div class="modal fade" id="tambahUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('user/create') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>NIP *</label>
                        <input type="text" name="nip" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Nama *</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control">
                    </div>
					<div class="form-group">
						<label>Golongan Darah</label>
						<select name="goldar" class="form-control">
							<option value="">Pilih</option>
							<option value="A" <?= set_value('goldar') == 'A' ? 'selected' : '' ?>>A</option>
							<option value="B" <?= set_value('goldar') == 'B' ? 'selected' : '' ?>>B</option>
							<option value="AB" <?= set_value('goldar') == 'AB' ? 'selected' : '' ?>>AB</option>
							<option value="O" <?= set_value('goldar') == 'O' ? 'selected' : '' ?>>O</option>
						</select>
					</div>
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Foto (opsional, JPG/PNG, max 2MB)</label>
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
