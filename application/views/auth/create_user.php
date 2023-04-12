<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title"><?= $title; ?></h3>
				</div>
				<div class="card-body">
					<?= form_open('', 'class="align-self-center"'); ?>
					<div class="row">
						<div class="col-md-12">
							<div class="mb-4">
								<label class="form-label" for="pengNama">Nama Lengkap</label>
								<input class="form-control <?= form_error('pengNama') ? 'is-invalid' : null; ?>" id="pengNama" name="pengNama" type="text" value="<?= set_value('pengNama'); ?>">
								<div class="invalid-feedback">
									<?= form_error('pengNama') ?>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="mb-4">
								<label class="form-label" for="pengEmail">Alamat Email</label>
								<input class="form-control <?= form_error('pengEmail') ? 'is-invalid' : null; ?>" id="pengEmail" name="pengEmail" type="email" value="<?= set_value('pengEmail'); ?>">
								<div class="invalid-feedback">
									<?= form_error('pengEmail') ?>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="mb-4">
								<label class="form-label" for="pengJenisKelamin">Jenis Kelamin</label>
								<select class="form-select <?= form_error('pengJenisKelamin') ? 'is-invalid' : null; ?>" id="pengJenisKelamin" name="pengJenisKelamin">
									<option value="">Pilih Jenis Kelamin</option>
									<option value="Pria" <?= set_value('pengJenisKelamin') == 'Pria' ? 'selected' : ''; ?>>Pria</option>
									<option value="Wanita" <?= set_value('pengJenisKelamin') == 'Wanita' ? 'selected' : ''; ?>>Wanita</option>
								</select>
								<div class="invalid-feedback">
									<?= form_error('pengJenisKelamin') ?>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="mb-4">
								<label class="form-label" for="pengTlp">Nomor Telpon</label>
								<input class="form-control <?= form_error('pengTlp') ? 'is-invalid' : null; ?>" id="pengTlp" name="pengTlp" type="tel" value="<?= set_value('pengTlp'); ?>">
								<div class="invalid-feedback">
									<?= form_error('pengTlp') ?>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="mb-4">
								<label class="form-label" for="grupId">Grup</label>
								<select class="form-select <?= form_error('grupId') ? 'is-invalid' : null; ?>" id="grupId" name="grupId">
									<option value="">Pilih Grup</option>
									<?php foreach ($groups as $group) : ?>
										<option value="<?= $group['grupId']; ?>"><?= $group['grupNama']; ?></option>
									<?php endforeach ?>
								</select>
								<div class="invalid-feedback">
									<?= form_error('grupId') ?>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="mb-4">
								<label class="form-label" for="pengPass">Kata Sandi</label>
								<input class="form-control <?= form_error('pengPass') ? 'is-invalid' : null; ?>" id="pengPass" name="pengPass" type="password">
								<div class="invalid-feedback">
									<?= form_error('pengPass') ?>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="mb-4">
								<label class="form-label" for="password_confirm">Konfirmasi Kata Sandi</label>
								<input class="form-control <?= form_error('password_confirm') ? 'is-invalid' : null; ?>" id="password_confirm" name="password_confirm" type="password">
								<div class="invalid-feedback">
									<?= form_error('password_confirm') ?>
								</div>
							</div>
						</div>

					</div>

					<button class="btn btn-success waitme" type="submit">Simpan</button>
					<?= form_close() ?>

				</div>
			</div>
		</div>
	</div>
</div>