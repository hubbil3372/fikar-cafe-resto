<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title"><?= $title; ?></h3>
          <!-- ------------------------------------------------ -->
          <!-- Cek apakah pengguna dapat akses menu -->
          <!-- ------------------------------------------------ -->
          <?php if ($this->akses->access_rights($menu_id, 'grupMenuTambah')) : ?>
            <!-- <a class="btn btn-success waitme" href="<?= site_url(); ?>backoffice/pesanan/tambah"> <i class="fas fa-plus"></i> Tambah Data</a> -->
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="alert alert-primary">
                <form class="row gx-3 gy-2 align-items-center">
                  <div class="col-sm-5">
                    <label class="visually-hidden" for="q">Kategori</label>
                    <select class="form-control" id="kategori" name="kategori" value="<?= $this->input->get('kategori') ?>">
                      <option value="">Pilih Kategori</option>
                      <?php foreach ($kategori as $kategoris) { ?>
                        <option value="<?= $kategoris->pkId ?>" <?= $this->input->get('kategori') == $kategoris->pkId ? 'selected' : null; ?>><?= $kategoris->pkNama ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-5">
                    <label class="visually-hidden" for="q">Name</label>
                    <input type="text" class="form-control" id="q" name="q" value="<?= $this->input->get('q') ?>" placeholder="Cari Menu">
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                  </div>
                </form>
              </div>
              <div class="card card-body" style="height: 100vh;">
                <div class="row table-responsive">
                  <?php foreach ($produk as $produks) : ?>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-img-top bg-image-set" style="min-height:20vh;background-image: url('<?= base_url("_uploads/produk/{$produks->produkGambar}") ?>');"></div>
                        <div class="card-body">
                          <h5 class="card-title"><?= $produks->produkNama ?></h5>
                          <p class="card-text text-success fs-5 mb-0"><?= rupiah($produks->produkHarga - $produks->produkHargaDiskon) ?></p>
                          <?php if ($produks->produkHargaDiskon != 0) : ?>
                            <span class="card-text text-decoration-line-through"><?= rupiah($produks->produkHarga) ?></span>
                            <span class="bg-warning ms-2 fw-bold text-decoration-none badge"><?= round($produks->produkHargaDiskon / $produks->produkHarga * 100) . '%' ?></span>
                          <?php endif; ?>
                          <?php if ($produks->produkTersedia != 1) : ?>
                            <!-- <span class="card-text text-decoration-line-through"><?= rupiah($produks->produkHarga) ?></span> -->
                            <span class="bg-warning fw-bold text-decoration-none badge">Tidak Tersedia</span>
                          <?php endif; ?>
                          <hr>
                          <p class="card-text text-truncate" style="font-size: 14px;"><?= $produks->produkKeterangan ?></p>
                          <div class="d-grid">
                            <!-- <a href="#" class="btn btn-sm btn-primary waitme mb-1"><i class="fas fa-plus"></i> Lihat</a> -->
                            <a href="<?= site_url("backoffice/admin/pesanan/tambah_keranjang/{$produks->produkId}") ?>" class="btn btn-sm btn-primary waitme"><i class="fas fa-plus"></i> Tambah</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width:3%;">No.</th>
                      <th>Pesanan</th>
                      <th style="width: 10%;" class="text-center">Jumlah</th>
                      <th style="width: 10%;">#</th>
                    </tr>
                    <?php if (!$keranjang) : ?>
                      <tr>
                        <td colspan="3" class="text-danger text-center">Keranjang Kosong!</td>
                      </tr>
                      <?php else : foreach ($keranjang as $key => $cart) { ?>
                        <tr>
                          <td class="text-center"><?= ++$key ?></td>
                          <td><?= $cart->produkNama ?></td>
                          <td class="text-center">
                            <?= $cart->keranjangQty ?>
                          </td>
                          <td>
                            <a href="<?= site_url("backoffice/admin/pesanan/hapus_keranjang/{$cart->keranjangId}") ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                          </td>
                        </tr>
                    <?php }
                    endif; ?>
                  </table>
                </div>
                <div class="d-grid">
                  <a class="btn btn-success waitme" href="<?= site_url(); ?>backoffice/pesanan/tambah"> <i class="fas fa-plus"></i> Buat Pesanan</a>
                </div>
              </div>
            </div>
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
        "url": "<?= site_url("backoffice/pesanan/get_json?tautan={$this->uri->segment(2)}") ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0, -1, -2],
        "orderable": false,
      }, ],
    });
  });
</script>