<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title">Data <?= $title; ?></h3>
          <!-- ------------------------------------------------ -->
          <!-- Cek apakah pengguna dapat akses menu -->
          <!-- ------------------------------------------------ -->
          <?php if ($this->input->get('from') && $this->input->get('to')) : ?>
            <a class="btn btn-success" target="_blank" href="<?= site_url("backoffice/laporan/print?from={$this->input->get('from')}&to={$this->input->get('to')}"); ?>"> <i class="fas fa-plus"></i> Export Excel</a>
          <?php endif; ?>
          <?php if ($this->akses->access_rights($menu_id, 'grupMenuTambah')) : ?>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="alert alert-secondary mb-3">
            <form action="" method="get">
              <div class="row">
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-md-3">
                      <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                      <input type="date" class="form-control" name="from" id="from" value="<?= $this->input->get('from') ?>">
                    </div>
                    <div class="col-md-3">
                      <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                      <input type="date" class="form-control" name="to" id="to" value="<?= $this->input->get('to') ?>">
                    </div>
                  </div>
                </div>
                <div class="col-md-2 d-flex justify-content-end mt-2">
                  <div class="d-flex align-items-end">
                    <button type="submit" class="btn btn-success waitme px-4"><i class="fas fa-search"></i> Filter</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered" id="table">
              <thead>
                <tr>
                  <th class="text-center" style="width: 2%;">No</th>
                  <th>Faktur</th>
                  <th>Pelanggan</th>
                  <th>Kasir</th>
                  <th>Subtotal</th>
                  <th>Diskon</th>
                  <th>Total</th>
                  <th>Tunai</th>
                  <th>Kembali</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <!-- <th>Catatan</th> -->
                  <th class="text-center" style="min-width: 8%;">Aksi</th>
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
      "ordering": false,
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?= site_url("backoffice/laporan/get_json?tautan={$this->uri->segment(2)}{$url}") ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0, -1, -2],
        "orderable": false,
      }, ],
    });
  });
</script>