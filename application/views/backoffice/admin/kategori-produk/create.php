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
                <label class="form-label" for="pkNama">Kategori Nama</label>
                <input class="form-control <?= form_error('pkNama') ? 'is-invalid' : null; ?>" id="pkNama" name="pkNama" type="text" value="<?= set_value('pkNama'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('pkNama') ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label class="form-label" for="pkKeterangan">Deskripsi</label>
                <input class="form-control <?= form_error('pkKeterangan') ? 'is-invalid' : null; ?>" id="pkKeterangan" name="pkKeterangan" type="text" value="<?= set_value('pkKeterangan'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('pkKeterangan') ?>
                </div>
              </div>
            </div>
          </div>

          <button class="btn btn-success waitme" type="submit"><i class="fas fa-check-circle"></i> Simpan</button>
          <?= form_close() ?>

        </div>
      </div>
    </div>
  </div>
</div>