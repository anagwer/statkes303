                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Statkes <?= date('Y') ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" jika ingin mengakhiri sesi.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

	<!-- DataTables JS -->
	<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

	<!-- Inisialisasi DataTables -->
	<script>
	$(document).ready(function() {
		$('#dataTable').DataTable();
	});
	</script>
</body>
</html>
