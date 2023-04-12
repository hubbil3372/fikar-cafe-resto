<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<section class="row">
				<div class="col-6">
					<!-- small box -->
					<div class="small-box bg-light">
						<div class="inner">
							<h3><?= $this->session->userdata('elapsed_time') ?? 0; ?></h3>

							<p>Elapsed Time</p>
						</div>
						<div class="icon">
							<i class="far fa-clock" aria-hidden="true"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-6">
					<!-- small box -->
					<div class="small-box bg-light">
						<div class="inner">
							<h3><?= $this->session->userdata('memory_usage') ?? 0; ?></h3>

							<p>Memory Usage</p>
						</div>
						<div class="icon">
							<i class="fas fa-tachometer-alt" aria-hidden="true"></i>
						</div>
					</div>
				</div>
			</section>

			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center w-100">
					<h3 class="card-title">Data <?= $title; ?></h3>
					<!-- ------------------------------------------------ -->
					<!-- Cek apakah pengguna dapat akses menu -->
					<!-- ------------------------------------------------ -->
					<?php if ($this->akses->access_rights($menu_id, 'grupMenuTambah')) : ?>
						<a class="btn btn-success waitme" href="<?= site_url(); ?>backoffice/grup/tambah">Tambah Grup</a>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<td>Aksi</td>
								<td>By</td>
								<td>Database</td>
								<td>Orang / Data</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;">Get</td>
								<td style="vertical-align: middle;">Semua</td>
								<td style="vertical-align: middle;">Grup</td>
								<td style="vertical-align: middle;">
									<?= form_open(site_url('backoffice/admin/LoadTime/get_grup')) ?>
									<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" required>
									<div class="input-group">
										<input class="form-control" min="1" value="<?= $this->session->userdata('grup') ?? 1; ?>" type="number" name="grup">
										<button class="btn btn-outline-secondary" type="submit">Test</button>
									</div>
									<?= form_close() ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;">Get</td>
								<td style="vertical-align: middle;">By</td>
								<td style="vertical-align: middle;">Grup</td>
								<td style="vertical-align: middle;">
									<?= form_open(site_url('backoffice/admin/LoadTime/get_grup_by')) ?>
									<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" required>
									<div class="input-group">
										<input class="form-control" min="1" value="<?= $this->session->userdata('grup_by') ?? 1; ?>" type="number" name="grup">
										<button class="btn btn-outline-secondary" type="submit">Test</button>
									</div>
									<?= form_close() ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;">Create</td>
								<td style="vertical-align: middle;">-</td>
								<td style="vertical-align: middle;">Grup</td>
								<td style="vertical-align: middle;">
									<?= form_open(site_url('backoffice/admin/LoadTime/create_grup')) ?>
									<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" required>
									<div class="input-group">
										<input class="form-control" min="1" value="<?= $this->session->userdata('create_grup') ?? 1; ?>" type="number" name="grup">
										<button class="btn btn-outline-secondary" type="submit">Test</button>
									</div>
									<?= form_close() ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- ------------------------------------------------ -->
<!-- Hapus session ketika di test kembali -->
<!-- ------------------------------------------------ -->

<?php

$this->session->unset_userdata([
	'elapsed_time',
	'memory_usage',
	'grup',
	'grup_by',
	'create_grup',
]);

?>