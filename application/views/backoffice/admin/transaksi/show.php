<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title">Data <?= $title; ?></h3>
          <!-- ------------------------------------------------ -->
          <!-- Cek apakah pengguna dapat akses menu -->
          <!-- ------------------------------------------------ -->
          <?php if ($this->akses->access_rights_aksi('backoffice/pesanan/cetak')) : ?>
            <a class="btn btn-success waitme" href="<?= site_url("backoffice/pesanan/cetak/{$transaksi->transaksiId}"); ?>"> <i class="fas fa-print waitme"></i> Cetak Struk</a>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-body">
                <table class="table table-borderless">
                  <tr>
                    <td>No Faktur</td>
                    <td class="fw-bold"><?= $transaksi->transaksiFaktur ?></td>
                  </tr>
                  <tr>
                    <td>Tanggal Transaksi</td>
                    <td class="fw-bold"><?= Date("d/m/Y H:i", strtotime($transaksi->transaksiTanggal)) ?></td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td class="fw-bold"><?= $transaksi->transaksiStatus == 1 ? "<span class=\"badge bg-success\">success</span>" : "<span class=\"badge bg-warning\">Gagal</span>" ?></td>
                  </tr>
                  <tr>
                    <td>Nama Pembeli</td>
                    <td class="fw-bold"><?= $transaksi->transaksiNamaPembeli ?></td>
                  </tr>
                  <tr>
                    <td>Catatan Transaksi</td>
                    <td class="fw-bold"><?= $transaksi->transaksiCatatan != null ? $transaksi->transaksiCatatan : '-'; ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-body">
                <table class="table table-borderless">
                  <tr>
                    <td>Subtotal</td>
                    <td class="fw-bold"><?= rupiah($transaksi->transaksiHarga) ?></td>
                  </tr>
                  <tr>
                    <td>Diskon</td>
                    <td class="fw-bold text-red"><?= rupiah($transaksi->transaksiDiskon) ?></td>
                  </tr>
                  <tr>
                    <td>Total Pembayaran</td>
                    <td class="fw-bold"><?= rupiah($transaksi->transaksiHargaTotal) ?></td>
                  </tr>
                  <tr>
                    <td>Tunai</td>
                    <td class="fw-bold"><?= rupiah($transaksi->transaksiTunai) ?></td>
                  </tr>
                  <tr>
                    <td>Kembalian</td>
                    <td class="fw-bold"><?= rupiah($transaksi->transaksiKembalian) ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Daftar Item Pesanan</h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 2%;">No.</th>
                          <th class="text-truncate">Nama Item</th>
                          <th>Kategori</th>
                          <th>Qty</th>
                          <th>Harga</th>
                          <th>Diskon</th>
                          <th>Catatan</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($transaksi_detail as $key => $produk) : ?>
                          <tr>
                            <td class="text-center"><?= ++$key ?></td>
                            <td><?= $produk->tdetailProdukNamaProduk ?></td>
                            <td><?= $produk->tdetailProdukKategori ?></td>
                            <td><?= $produk->tdetailQty ?></td>
                            <td><?= rupiah($produk->tdetailProdukHarga); ?></td>
                            <td><?= rupiah($produk->tdetailProdukHargaDiskon); ?></td>
                            <td><?= $produk->tdetailCatatanPembeli ?></td>
                            <td><?= rupiah(($produk->tdetailProdukHarga - $produk->tdetailProdukHargaDiskon) * $produk->tdetailQty) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
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
        "url": "<?= site_url("backoffice/transaksi/get_json?tautan={$this->uri->segment(2)}") ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0, -1, -2],
        "orderable": false,
      }, ],
    });
  });
</script>