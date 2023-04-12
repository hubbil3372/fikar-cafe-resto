<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center w-100">
					<h3 class="card-title">Data <?= $title; ?></h3>
					<!-- ------------------------------------------------ -->
					<!-- Cek apakah pengguna dapat akses menu -->
					<!-- ------------------------------------------------ -->
					<?php if ($this->akses->access_rights($menu_id, 'grupMenuTambah')) : ?>
						<a class="btn btn-success waitme" href="<?= site_url(); ?>backoffice/pengguna/tambah">Tambah Pengguna</a>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="table">
							<thead>
								<tr>
									<th class="text-center" style="width: 5%;">No</th>
									<th>Nama</th>
									<th>Alamat Email</th>
									<th>Nomor Telpon</th>
									<th>Grup</th>
									<th class="text-center">Status</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// --------------------------------------
	// CSRF TOKEN
	// --------------------------------------
	var csfrData = {};
	csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
	$.ajaxSetup({
		data: csfrData
	});

	var table;
	$(document).ready(function() {
		table = $('#table').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?= site_url("auth/get_json?tautan={$this->uri->segment(2)}") ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
		});
	});
</script>