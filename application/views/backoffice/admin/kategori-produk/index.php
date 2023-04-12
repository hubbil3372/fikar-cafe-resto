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
            <a class="btn btn-success waitme" href="<?= site_url(); ?>backoffice/kategori-produk/tambah"> <i class="fas fa-plus"></i> Tambah Data</a>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="table">
              <thead>
                <tr>
                  <th class="text-center" style="width: 3%;">No</th>
                  <th>Kategori Nama</th>
                  <th>Deskripsi</th>
                  <th class="text-center" style="width: 10%;">Aksi</th>
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
        "url": "<?= site_url("backoffice/kategori-produk/get_json?tautan={$this->uri->segment(2)}") ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0, -1, -2],
        "orderable": false,
      }, ],
    });
  });
</script>