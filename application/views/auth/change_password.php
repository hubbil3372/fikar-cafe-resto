<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?></h3>
        </div>
        <div class="card-body">
          <?= form_open(); ?>

          <div class="row">
            <div class="col-md-12">
              <div class="mb-4">
                <label class="form-label" for="<?= $old_password['name']; ?>">Kata Sandi Lama</label>
                <input class="form-control <?= form_error($old_password['name']) ? 'is-invalid' : null; ?>" id="<?= $old_password['name']; ?>" name="<?= $old_password['name']; ?>" type="<?= $old_password['type']; ?>">
                <div class="invalid-feedback">
                  <?= form_error($old_password['name']) ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label class="form-label" for="<?= $new_password['name']; ?>">Kata Sandi Baru</label>
                <input class="form-control <?= form_error($new_password['name']) ? 'is-invalid' : null; ?>" id="<?= $new_password['name']; ?>" name="<?= $new_password['name']; ?>" type="<?= $new_password['type']; ?>">
                <div class="invalid-feedback">
                  <?= form_error($new_password['name']) ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label class="form-label" for="<?= $new_password_confirm['name']; ?>">Konfirmasi Kata Sandi</label>
                <input class="form-control <?= form_error($new_password_confirm['name']) ? 'is-invalid' : null; ?>" id="<?= $new_password_confirm['name']; ?>" name="<?= $new_password_confirm['name']; ?>" type="<?= $new_password_confirm['type']; ?>">
                <div class="invalid-feedback">
                  <?= form_error($new_password_confirm['name']) ?>
                </div>
              </div>
            </div>
          </div>

					<button class="btn btn-success waitme" type="submit">Simpan</button>
          <?= form_close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>