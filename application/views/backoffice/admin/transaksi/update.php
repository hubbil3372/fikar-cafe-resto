<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?></h3>
        </div>
        <div class="card-body">
          <form action="" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="row">
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="transaksiNamaPembeli">Pelanggan (Pesanan Atasnama)</label>
                  <input class="form-control <?= form_error('transaksiNamaPembeli') ? 'is-invalid' : null; ?>" id="transaksiNamaPembeli" name="transaksiNamaPembeli" type="text" value="<?= $this->input->post('transaksiNamaPembeli') ?? $transaksi->transaksiNamaPembeli; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('transaksiNamaPembeli') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="transaksiFaktur">Faktur</label>
                  <input class="form-control <?= form_error('transaksiFaktur') ? 'is-invalid' : null; ?>" id="transaksiFaktur" name="transaksiFaktur" type="text" value="<?= $this->input->post('transaksiFaktur') ?? $transaksi->transaksiFaktur; ?>" readonly>
                  <div class="invalid-feedback">
                    <?= form_error('transaksiFaktur') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="transaksiCatatan">Catatan Pembeli</label>
                  <input class="form-control <?= form_error('transaksiCatatan') ? 'is-invalid' : null; ?>" id="transaksiCatatan" name="transaksiCatatan" type="text" value="<?= $this->input->post('transaksiCatatan') ?? $transaksi->transaksiCatatan; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('transaksiCatatan') ?>
                  </div>
                </div>
              </div>
              <!-- detail pemebelian items -->
              <div class="col-md-12">
                <div class="mb-4">
                  <div class="card card-primary card-outline">
                    <div class="card-header d-flex justify-content-between">
                      <h3 class="card-title">Daftar Item</h3>
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalListProduk"><i class="fas fa-plus"></i> Tambah Item</button>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                          <thead>
                            <tr>
                              <th style="width:2%;">No.</th>
                              <th>Nama Item</th>
                              <th>Kategori</th>
                              <th>Qty</th>
                              <th>Harga</th>
                              <th>Diskon</th>
                              <th>Harga Net</th>
                              <th>Catatan</th>
                              <th style="width:8%;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!$detail_transaksi) : ?>
                              <tr>
                                <td colspan="7" class="text-center text-danger">Item tidak tersedia!</td>
                              </tr>
                              <?php else : foreach ($detail_transaksi as $key => $details) : ?>
                                <tr>
                                  <td><?= ++$key ?></td>
                                  <td><?= $details->tdetailProdukNamaProduk ?></td>
                                  <td><?= $details->tdetailProdukKategori ?></td>
                                  <td><?= $details->tdetailQty ?></td>
                                  <td><?= rupiah($details->tdetailProdukHarga) ?></td>
                                  <td class="text-danger"><?= rupiah($details->tdetailProdukHargaDiskon) ?></td>
                                  <td><?= rupiah($details->tdetailProdukHarga - $details->tdetailProdukHargaDiskon) ?></td>
                                  <td><?= $details->tdetailCatatanPembeli ?></td>
                                  <td class="text-center">
                                    <?php if ($this->akses->access_rights_aksi("backoffice/transaksi/hapus-item")) : ?>
                                      <button type="button" class="btn btn-outline-primary btn-sm update-cart" data-tdetailid="<?= $details->tdetailId ?>" data-bs-toggle="modal" data-bs-target="#modalUpdateCart"><i class="fas fa-edit update-cart" data-tdetailid="<?= $details->tdetailId ?>"></i></button>
                                      <a href="<?= site_url("backoffice/transaksi/hapus-item/{$details->tdetailId}") ?>" class="btn btn-danger btn-sm destroy"><i class="fas fa-trash destroy" href="<?= site_url("backoffice/transaksi/hapus-item/{$details->tdetailId}") ?>"></i></a>
                                    <?php endif; ?>
                                  </td>
                                </tr>
                            <?php endforeach;
                            endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- akhir detail pemebelian items -->
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="transaksiStatus">Status</label>
                  <select name="transaksiStatus" id="transaksiStatus" class="form-control <?= form_error('transaksiStatus') ? 'is-invalid' : null; ?>">
                    <option value="0">Pending</option>
                    <option value="1" <?= $this->input->post('transaksiAtasNama') != null ? ($this->input->post('transaksiAtasNama') == '1' ? 'selected' : null) : ($transaksi->transaksiStatus == '1' ? 'selected' : null); ?>>Selesai</option>
                  </select>
                  <div class="invalid-feedback">
                    <?= form_error('transaksiStatus') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="transaksiHarga">Harga</label>
                  <input class="form-control <?= form_error('transaksiHarga') ? 'is-invalid' : null; ?>" id="transaksiHarga" name="transaksiHarga" type="text" value="<?= $this->input->post('transaksiHarga') ?? $transaksi->transaksiHarga; ?>" readonly>
                  <div class="invalid-feedback">
                    <?= form_error('transaksiHarga') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="transaksiDiskon">Diskon</label>
                  <input class="form-control <?= form_error('transaksiDiskon') ? 'is-invalid' : null; ?>" id="transaksiDiskon" name="transaksiDiskon" type="text" value="<?= $this->input->post('transaksiDiskon') ?? $transaksi->transaksiDiskon; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('transaksiDiskon') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="transaksiHargaTotal">Subtotal</label>
                  <input class="form-control <?= form_error('transaksiHargaTotal') ? 'is-invalid' : null; ?>" id="transaksiHargaTotal" name="transaksiHargaTotal" type="text" value="<?= $this->input->post('transaksiHargaTotal') ?? $transaksi->transaksiHargaTotal; ?>" readonly>
                  <div class="invalid-feedback">
                    <?= form_error('transaksiHargaTotal') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="transaksiTunai">Tunai</label>
                  <input class="form-control <?= form_error('transaksiTunai') ? 'is-invalid' : null; ?>" id="transaksiTunai" name="transaksiTunai" type="text" value="<?= $this->input->post('transaksiTunai') ?? $transaksi->transaksiTunai; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('transaksiTunai') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="transaksiKembalian">Kembalian</label>
                  <input class="form-control <?= form_error('transaksiKembalian') ? 'is-invalid' : null; ?>" id="transaksiKembalian" name="transaksiKembalian" type="text" value="<?= $this->input->post('transaksiKembalian') ?? $transaksi->transaksiKembalian; ?>" readonly>
                  <div class="invalid-feedback">
                    <?= form_error('transaksiKembalian') ?>
                  </div>
                </div>
              </div>
            </div>

            <button class="btn btn-success waitme" type="submit"><i class="fas fa-check-circle"></i> Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal produk -->
<div class="modal fade" id="modalListProduk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pilih Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="table-produk" style="width: 100%;">
            <thead>
              <tr>
                <th class="text-center" style="width: 1%;">No</th>
                <th class="text-truncate">Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Tersedia</th>
                <th class="text-center" style="min-width: 8%;">Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- akhir modal produk -->

<!-- modal ubah keranjang -->
<div class="modal fade" id="modalUpdateCart" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Keranjang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= site_url("backoffice/transaksi/ubah-item") ?>" method="post">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="tdetailId" id="tdetailId" required>
          <input type="hidden" name="tdetailTransaksiId" id="tdetailTransaksiId" required>
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label for="tdetailQty" class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="tdetailQty" id="tdetailQty" required>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-3">
                <label for="tdetailCatatanPembeli" class="form-label">Catatan</label>
                <textarea class="form-control" id="tdetailCatatanPembeli" name="tdetailCatatanPembeli" rows="3"></textarea>
              </div>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary waitme">Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- akhir modal ubah keranjang -->

<script>
  // --------------------------------------
  // CSRF TOKEN
  // --------------------------------------
  var csfrData = {};
  csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
  $.ajaxSetup({
    data: csfrData
  });


  $(document).ready(function() {
    var tableProduk;
    tableProduk = $('#table-produk').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?= site_url("backoffice/transaksi/get-json-produk/{$transaksi->transaksiId}?tautan={$this->uri->segment(2)}") ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0, -1, -2],
        "orderable": false,
      }, ],
    });

    let harga = document.getElementById("transaksiHarga");
    let diskon = document.getElementById("transaksiDiskon");
    let subtotal = document.getElementById("transaksiHargaTotal");
    let tunai = document.getElementById("transaksiTunai");
    let kembalian = document.getElementById("transaksiKembalian");
    // console.log($(diskon).val());
    $(diskon).val(formatRupiah($(diskon).val(), "Rp."));
    $(harga).val(formatRupiah($(harga).val(), "Rp."));
    $(subtotal).val(formatRupiah($(subtotal).val(), "Rp."));
    $(tunai).val(formatRupiah($(tunai).val(), "Rp."));
    $(kembalian).val(formatRupiah($(kembalian).val(), "Rp."));

    function hitung() {
      let intHarga = cleanString(harga.value);
      let intHargaDiskon = cleanString(diskon.value);
      if (diskon.value == "") intHargaDiskon = 0;
      let total = intHarga - intHargaDiskon;
      if (intHargaDiskon > intHarga) total = 0;
      subtotal.value = formatRupiah(total.toString(), "Rp.");
    }

    function hitungTrans() {
      let intHarga = cleanString(harga.value);
      let intTunai = cleanString(tunai.value);
      let total = intTunai - intHarga;
      let format = formatRupiah(total.toString(), "Rp.");
      if (total < 0) format = "- " + format;
      kembalian.value = format;
    }

    diskon.addEventListener("keyup", function(e) {
      e.target.value = formatRupiah(e.target.value, "Rp. ");
      hitung();
    })

    tunai.addEventListener("keyup", function(e) {
      e.target.value = formatRupiah(e.target.value, "Rp. ");
      hitungTrans();
    })
  })

  /* Format rupiah */
  function formatRupiah(angka, prefix) {
    let number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
  }
  /* format rupiah */
  function cleanString(angka) {
    let number_string = angka.replace(/[^,\d]/g, "");
    return parseInt(number_string);
  }

  $("body").on("click", function(e) {
    /* ubah keranjang */
    if ($(e.target).hasClass("update-cart")) {
      e.preventDefault();
      let tdetailId = $(e.target).data("tdetailid");
      $("#tdetailId").val(tdetailId);
      $.ajax({
        type: 'GET',
        url: '<?= site_url("backoffice/transaksi/detail-item/") ?>' + tdetailId,
        dataType: 'json',
        success: function(result) {
          console.log(result);
          if (!result.status) {
            return Swal.fire({
              title: "Peringatan",
              text: result.message,
              icon: "warning",
              confirmButtonColor: "#3085d6",
              confirmButtonText: "Ya!"
            })
          }
          $("#tdetailQty").val(result.data.tdetailQty);
          $("#tdetailCatatanPembeli").val(result.data.tdetailCatatanPembeli);
          $("#tdetailTransaksiId").val(result.data.tdetailTransaksiId);
        }
      })
    }

  })
</script>