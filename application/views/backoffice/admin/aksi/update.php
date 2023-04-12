<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?> pada menu <?= ucfirst($menu->menuLabel); ?></h3>
        </div>
        <div class="card-body">
          <?= form_open('', 'class="align-self-center"'); ?>
            <div class="row">
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="aksiLabel">Label</label>
                  <input class="form-control <?= form_error('aksiLabel') ? 'is-invalid' : null; ?>" id="aksiLabel" name="aksiLabel" type="text" value="<?= $this->input->post('aksiLabel') ?? $unit->aksiLabel; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('aksiLabel') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="aksiTautan">Tautan</label>
                  <input class="form-control <?= form_error('aksiTautan') ? 'is-invalid' : null; ?>" id="aksiTautan" name="aksiTautan" type="text" value="<?= $this->input->post('aksiTautan') ?? $unit->aksiTautan; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('aksiTautan') ?>
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

<script>
  $(document).ready(function() {
    $("#aksiLabel").keyup(function() {
      let aksiLabel = $("#aksiLabel").val();

      let menu = `<?= $menu->menuTautan; ?>`
      let slug = aksiLabel.toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');

      $("#aksiTautan").val(`backoffice/${menu.split("/")[1]}/${slug}`)
    })
  });
</script>