<div class="col-md-6 d-flex justify-content-center">
	<div class="align-self-center w-100">
		<div class="text-center mb-5 mt-5 d-md-none">
			<img src="<?= base_url() ?>_assets/img/untirta.png" class="img-fluid w-25">
			<h4 class="text-uppercase fw-bold h4 font-color-page mt-3">sistem informasi inovasi dan hilirisasi</h4>
			<p class="font-color-page text-uppercase">Universitas sultan ageng tirtayasa</p>
		</div>
		<div class="row justify-content-center">
			<div class="col-10 col-sm-8 col-md-10 col-lg-7">
				<h4 class="fw-bold mb-5">Ubah Password</h4>
				<?= form_open('reset-kata-sandi/' . $code); ?>
				
				<?= form_input($user_id); ?>
				<div class="form-group mb-4">
					<div class="input-group">
						<div class="input-group-text bg-light border-0 ps-4 <?= form_error('new') ? 'invalid-input-l' : null; ?>">
							<svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path opacity="0.5" d="M7.16047 14.4328C8.12516 14.4328 8.91446 13.6435 8.91446 12.6788C8.91446 11.7141 8.12516 10.9248 7.16047 10.9248C6.19578 10.9248 5.40649 11.7141 5.40649 12.6788C5.40649 13.6435 6.19578 14.4328 7.16047 14.4328ZM12.4224 6.53983H11.5454V4.78584C11.5454 2.36534 9.58097 0.400879 7.16047 0.400879C4.73997 0.400879 2.77551 2.36534 2.77551 4.78584H4.4418C4.4418 3.28618 5.66082 2.06717 7.16047 2.06717C8.66013 2.06717 9.87915 3.28618 9.87915 4.78584V6.53983H1.89852C0.933825 6.53983 0.144531 7.32912 0.144531 8.29381V17.0637C0.144531 18.0284 0.933825 18.8177 1.89852 18.8177H12.4224C13.3871 18.8177 14.1764 18.0284 14.1764 17.0637V8.29381C14.1764 7.32912 13.3871 6.53983 12.4224 6.53983ZM12.4224 17.0637H1.89852V8.29381H12.4224V17.0637Z" fill="#03014C" />
							</svg>
						</div>
						<input type="password" class="form-control border-0 bg-light form-control-lg <?= form_error('new') ? 'is-invalid invalid-input-r' : null; ?>" id="new" name="new" placeholder="Password Baru (Minimal 8 karakter)">
						<?= form_error('new'); ?>
					</div>
				</div>
				<div class="form-group mb-4">
					<div class="input-group">
						<div class="input-group-text bg-light border-0 ps-4 <?= form_error('new_confirm') ? 'invalid-input-l' : null; ?>">
							<svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path opacity="0.5" d="M7.16047 14.4328C8.12516 14.4328 8.91446 13.6435 8.91446 12.6788C8.91446 11.7141 8.12516 10.9248 7.16047 10.9248C6.19578 10.9248 5.40649 11.7141 5.40649 12.6788C5.40649 13.6435 6.19578 14.4328 7.16047 14.4328ZM12.4224 6.53983H11.5454V4.78584C11.5454 2.36534 9.58097 0.400879 7.16047 0.400879C4.73997 0.400879 2.77551 2.36534 2.77551 4.78584H4.4418C4.4418 3.28618 5.66082 2.06717 7.16047 2.06717C8.66013 2.06717 9.87915 3.28618 9.87915 4.78584V6.53983H1.89852C0.933825 6.53983 0.144531 7.32912 0.144531 8.29381V17.0637C0.144531 18.0284 0.933825 18.8177 1.89852 18.8177H12.4224C13.3871 18.8177 14.1764 18.0284 14.1764 17.0637V8.29381C14.1764 7.32912 13.3871 6.53983 12.4224 6.53983ZM12.4224 17.0637H1.89852V8.29381H12.4224V17.0637Z" fill="#03014C" />
							</svg>
						</div>
						<input type="password" class="form-control border-0 bg-light form-control-lg <?= form_error('new_confirm') ? 'is-invalid invalid-input-r' : null; ?>" id="new_confirm" name="new_confirm" placeholder="Konfirmasi Password">
						<?= form_error('new_confirm'); ?>
					</div>
				</div>
				<div class="d-grid gap-2 mt-5">
					<button type="submit" class="btn btn-login  text-white">Submit</button>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-12 pb-5 pb-md-0">
				<p class="text-center mb-1 font-color-page fw-bold">&copy; 2021 Universitas Sultan Ageng Tirtayasa</p>
				<p class="text-center mb-1 font-color-page">Developed by Pusdainfo Untirta</p>
			</div>
		</div>
	</div>
	<!-- <div class="card-img-overlay d-flex align-items-end text-center">
                </div> -->
</div>