<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center w-100">
					<h3 class="card-title"><?= $title; ?></h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="table">
							<thead>
								<tr>
									<th class="text-center" style="width: 5%;">No</th>
									<th>Example</th>
									<th>Deskripsi</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="vertical-align: middle;" class="text-center">1</td>
									<td style="vertical-align: middle;">Export PDF</td>
									<td style="vertical-align: middle;" class="text-center">-</td>
									<td style="vertical-align: middle;"><a class="btn d-block w-100 btn-danger" href="<?= site_url('backoffice/example/pdf'); ?>" target="_blank" rel="noopener noreferrer">Export PDF</a></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;" class="text-center">2</td>
									<td style="vertical-align: middle;">Export Excel</td>
									<td style="vertical-align: middle;" class="text-center">-</td>
									<td style="vertical-align: middle;"><a class="btn d-block w-100 btn-success" href="<?= site_url('backoffice/example/export-excel'); ?>" target="_blank" rel="noopener noreferrer">Export Excel</a></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;" class="text-center">3</td>
									<td style="vertical-align: middle;">Import Excel</td>
									<td style="vertical-align: middle;" class="text-center">-</td>
									<td style="vertical-align: middle;">
										<a class="btn d-block w-100 btn-success mb-5" href="<?= base_url('_assets/excel/template.xlsx'); ?>" target="_blank" rel="noopener noreferrer">Example Excel</a>
										<?= form_open_multipart(site_url('backoffice/example/import-excel'), 'target="_blank"'); ?>
											<input class="form-control mb-2" type="file" name="excel" id="excel" required>
											<button class="btn d-block w-100 btn-primary" target="_blank" rel="noopener noreferrer">Import Excel</button>
										<?= form_close(); ?>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: middle;" class="text-center">4</td>
									<td style="vertical-align: middle;">API</td>
									<td style="vertical-align: middle;" class="text-center">-</td>
									<td style="vertical-align: middle;"><a class="btn d-block w-100 btn-danger" href="<?= site_url('backoffice/api/Example/users'); ?>" target="_blank" rel="noopener noreferrer">API</a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>