<div class="container-fluid">
  <div class="row">
    <div class="col-md-6">
      <div class="card card-body" style="min-height:20vh ;">
        <table class="table table-borderless">
          <tr>
            <td>Tanggal Pesanan</td>
            <td>
              <input type="date" class="form-control" name="transaksiTanggal" value="<?= date('Y-m-d') ?>">
            </td>
          </tr>
          <tr>
            <td>Kasir</td>
            <td>
              <input type="hidden" value="<?= $kasir->pengId ?>" name="transaksiKasirId" readonly required>
              <input type="text" class="form-control" value="<?= $kasir->pengNama ?>" readonly>
            </td>
          </tr>

        </table>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card" style="min-height:20vh ;">
        <div class="card-body text-end">
          <p class="fs-3 mb-0 fw-bold" id="faktur"><?= $faktur ?></p>
          <p class="fw-bold lh-sm" id="countingPrice" style="font-size: 50pt"><?= $counting ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card" style="height: 80vh;">
        <div class="card-header">
          <h3 class="card-title">Item Pesanan</h3>
          <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal"> <i class="fas fa-plus"></i> Tambah Pesanan</button>
        </div>
        <div class="card-body table-responsive">
          <?php if (!$produk) : ?>
            <div class="card card-body text-center text-danger">
              Pesanan Belum Dipilih!
            </div>
            <?php else : foreach ($produk as $produks) : ?>
              <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-3 bg-image-set bg-success rounded" style="background-image: url('<?= base_url("_uploads/produk/{$produks->produkGambar}") ?>');">
                  </div>
                  <div class="col-md-9">
                    <div class="card-body">
                      <p class="mb-0 h5 text-truncate"><?= $produks->produkNama ?></p>
                      <span class="card-text text-success fw-bold fs-4 mb-0"><?= rupiah($produks->produkHarga - $produks->produkHargaDiskon) ?></span>
                      <?php if ($produks->produkHargaDiskon != 0) : ?>
                        <div class="float-end">
                          <span class="card-text text-danger text-decoration-line-through"><?= rupiah($produks->produkHarga) ?></span>
                          <span class="bg-warning ms-1 fw-bold text-decoration-none badge"><?= round($produks->produkHargaDiskon / $produks->produkHarga * 100) . '%' ?></span>
                        </div>
                      <?php endif; ?>
                      <p class="card-text text-truncate mb-1 mt-2"><?= $produks->produkKeterangan ?></p>
                      <hr class="mt-0 mb-1">
                      <div class="d-flex justify-content-between">
                        <div class="note" style="max-width:20vw;">
                          <p class="mb-0 fw-bold">Catatan:</p>
                          <p class="note-text fst-italic text-truncate"><?= $produks->keranjangCatatanPembeli != "" ? $produks->keranjangCatatanPembeli : '-' ?></p>
                        </div>
                        <div class="btn-action align-self-end">
                          <p class="mb-3">Jumlah: <span class="note-text fw-bold text-truncate"><?= $produks->keranjangQty ?></span></p>
                          <button type="button" class="btn btn-outline-primary btn-sm update-cart" data-keranjangid="<?= $produks->keranjangId ?>" data-bs-toggle="modal" data-bs-target="#modalUpdateCart"><i class="fas fa-edit update-cart" data-keranjangid="<?= $produks->keranjangId ?>"></i></button>
                          <a href="<?= site_url("backoffice/keranjang/{$produks->keranjangId}/hapus") ?>" class="btn btn-outline-danger btn-sm confirm" data-message="item akan dihapus dari keranjang!"><i data-message="item akan dihapus dari keranjang!" class="fas fa-trash confirm" href="<?= site_url("backoffice/keranjang/{$produks->keranjangId}/hapus") ?>"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          <?php endforeach;
          endif; ?>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card card-body">
        <table class="table table-borderless">
          <tr>
            <td>Pelanggan</td>
            <td>
              <input type="text" class="form-control" id="transaksiNamaPembeli" name="transaksiNamaPembeli" required>
            </td>
          </tr>
          <tr>
            <td>Subtotal</td>
            <td>
              <input type="text" class="form-control" id="transaksiHarga" name="transaksiHarga" value="" readonly>
            </td>
          </tr>
          <tr>
            <td>Diskon</td>
            <td>
              <input type="text" class="form-control" id="transaksiDiskon" name="transaksiDiskon" value="0">
            </td>
          </tr>
          <tr>
            <td>Total Bayar</td>
            <td>
              <input type="text" class="form-control" id="transaksiHargaTotal" name="transaksiHargaTotal" value="0" readonly>
            </td>
          </tr>
          <tr>
            <td>Tunai</td>
            <td>
              <input type="text" class="form-control" id="transaksiTunai" name="transaksiTunai" value="0">
            </td>
          </tr>
          <tr>
            <td>Kembalian</td>
            <td>
              <input type="text" class="form-control" id="transaksiKembalian" name="transaksiKembalian" value="0" readonly>
            </td>
          </tr>
          <tr>
            <td>Catatan</td>
            <td>
              <textarea type="text" class="form-control" name="transaksiCatatan" id="transaksiCatatan" placeholder="Masukan catatan jika ada!" rows="4"></textarea>
            </td>
          </tr>
        </table>
      </div>
      <?php if ($produk) : ?>
        <div class="row">
          <div class="col-12">
            <a href="<?= site_url("backoffice/pesanan/cetak/") ?>" class="btn btn-success proses-transaksi"><i class="fas fa-check-circle"></i> Proses Transaksi</a>
            <a href="<?= site_url("backoffice/pesanan/{$kasir->pengId}/batalkan") ?>" data-message="Transaksi Akan dibatalkan" class="btn btn-warning float-end batalkan-transaksi confirm"><i data-message="Transaksi Akan dibatalkan" href="<?= site_url("backoffice/pesanan/{$kasir->pengId}/batalkan") ?>" class="fas fa-times confirm"></i> Batalkan Transaksi</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- modal produk -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <?= form_open(site_url("backoffice/keranjang/ubah"), ['method' => "post"]) ?>
        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <input type="hidden" name="keranjangId" id="keranjangId" required>
              <label for="keranjangCatatanPembeli" class="form-label">Catatan</label>
              <textarea class="form-control" id="keranjangCatatanPembeli" name="keranjangCatatanPembeli" rows="3"></textarea>
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <label for="keranjangQty" class="form-label">Jumlah</label>
              <input type="number" class="form-control" name="keranjangQty" id="keranjangQty" required>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary waitme">Simpan</button>
          </div>
        </div>
        <?= form_close() ?>
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



  let counting = $('#countingPrice');
  let harga = $('#transaksiHarga');
  let hargaDiskon = $('#transaksiDiskon');
  let hargaTotal = $('#transaksiHargaTotal');
  let tunai = $('#transaksiTunai');
  let kembalian = $('#transaksiKembalian');

  $(document).ready(function() {

    tunai.click(function() {
      tunai.select()
    })

    hargaDiskon.click(function() {
      hargaDiskon.select()
    })
    var tableProduk;
    tableProduk = $('#table-produk').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?= site_url("backoffice/pesanan/get_json_produk?tautan={$this->uri->segment(2)}") ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0, -1, -2],
        "orderable": false,
      }, ],
    });

    let selected = document.getElementsByClassName("select-items");
    $("body").on("click", function(e) {
      if ($(e.target).hasClass("select-items")) {
        e.preventDefault()
        let href = $(e.target).attr("href");
        $('body').waitMe({
          effect: 'bounce',
          text: '',
          bg: "rgba(255, 255, 255, 0.7)",
          color: "#000",
          maxSize: '',
          waitTime: -1,
          textPos: 'vertical',
          fontSize: '',
          source: '',
          onClose: function() {}
        });
        document.location.href = href;
      }


      /* ubah keranjang */
      if ($(e.target).hasClass("update-cart")) {
        e.preventDefault();
        let keranjangId = $(e.target).data("keranjangid");
        $("#keranjangId").val(keranjangId);
        $.ajax({
          type: 'POST',
          url: '<?= site_url("backoffice/keranjang/data") ?>',
          dataType: 'json',
          data: {
            'keranjangId': keranjangId
          },
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
            $("#keranjangQty").val(result.data.keranjangQty);
            $("#keranjangCatatanPembeli").val(result.data.keranjangCatatanPembeli);
          }
        })
      }
      var namaPembeli = "Umum";
      if ($(e.target).hasClass("proses-transaksi")) {
        e.preventDefault();
        let href = $(e.target).attr("href");
        console.log(href);
        Swal.fire({
          title: "Yakin proses transaksi?",
          text: "Transaksi akan diproses",
          icon: "warning",
          showCancelButton: !0,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya!"
        }).then(result => {
          if (result.isConfirmed) {
            $('body').waitMe({
              effect: 'bounce',
              text: '',
              bg: "rgba(255, 255, 255, 0.7)",
              color: "#000",
              maxSize: '',
              waitTime: -1,
              textPos: 'vertical',
              fontSize: '',
              source: '',
              onClose: function() {}
            });
            if ($("#transaksiNamaPembeli").val() != "") {
              // $('body').waitMe('hide');
              // return Swal.fire({
              //   title: "Peringatan",
              //   text: "Nama Pelanggan Masih Kosong!",
              //   icon: "warning",
              //   confirmButtonColor: "#3085d6",
              //   confirmButtonText: "Ya!"
              // })
              namaPembeli = $("#transaksiNamaPembeli").val();
            }

            if (cleanString(tunai.val()) == 0) {
              $('body').waitMe('hide');
              return Swal.fire({
                title: "Peringatan",
                text: "Silakan Masukan nominal pembayaran tunai!",
                icon: "warning",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya!"
              })
            }
            $.ajax({
              type: 'POST',
              url: '<?= site_url("backoffice/admin/pesanan/process") ?>',
              dataType: 'json',
              data: {
                'transaksiKasirId': '<?= $kasir->pengId ?>',
                'transaksiFaktur': '<?= $faktur ?>',
                'transaksiHarga': harga.val(),
                'transaksiDiskon': hargaDiskon.val(),
                'transaksiNamaPembeli': namaPembeli,
                'transaksiTunai': tunai.val(),
                'transaksiKembalian': kembalian.val(),
                'transaksiHargaTotal': hargaTotal.val(),
                'transaksiTanggal': $("#transaksiTanggal").val(),
                'transaksiCatatan': $("#transaksiCatatan").val(),
              },
              success: function(response) {
                $('body').waitMe('hide');
                if (!response.status) {
                  return Swal.fire({
                    title: "Peringatan",
                    text: response.message,
                    icon: "warning",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Oke"
                  })
                }
                // const ToastCustom = Swal.mixin({
                //   toast: true,
                //   position: 'top-end',
                //   showConfirmButton: false,
                //   timer: 3000,
                //   timerProgressBar: true,
                //   didOpen: (toast) => {
                //     toast.addEventListener('mouseenter', Swal.stopTimer)
                //     toast.addEventListener('mouseleave', Swal.resumeTimer)
                //   }
                // })

                // ToastCustom.fire({
                //   icon: 'success',
                //   title: `transaksi sukses`
                // })

                // return document.location.href = href + response.transaksiId


                Swal.fire({
                  title: "Berhasil",
                  text: response.message,
                  icon: "success",
                  confirmButtonColor: "#3085d6",
                  confirmButtonText: "Ya!"
                }).then(result => {
                  if (result.isConfirmed) {
                    $('body').waitMe({
                      effect: 'bounce',
                      text: '',
                      bg: "rgba(255, 255, 255, 0.7)",
                      color: "#000",
                      maxSize: '',
                      waitTime: -1,
                      textPos: 'vertical',
                      fontSize: '',
                      source: '',
                      onClose: function() {}
                    });
                    result.isConfirmed && (document.location.href = href + response.transaksiId)
                    // window.location = '<?= site_url('') ?>'
                  }
                })
              }
            })
          }
        })
      }
    })


    let countingAll;

    harga.val(counting.text());
    hargaDiskon.val(formatRupiah(hargaDiskon.val(), "Rp."));
    tunai.val(formatRupiah(tunai.val(), "Rp."));
    kembalian.val(formatRupiah(kembalian.val(), "Rp."));
    hitung();

    function hitung() {
      let intCounting = cleanString(counting.text());
      let intHarga = cleanString(harga.val());
      let intHargaDiskon = cleanString(hargaDiskon.val());
      if (hargaDiskon.val() == "") intHargaDiskon = 0;
      let total = intHarga - intHargaDiskon;
      if (intHargaDiskon > intHarga) total = 0;
      hargaTotal.val(formatRupiah(total.toString(), "Rp."));
    }

    function hitungTrans() {
      let intHarga = cleanString(harga.val());
      let intTunai = cleanString(tunai.val());
      let total = intTunai - intHarga;
      let format = formatRupiah(total.toString(), "Rp.");
      if (total < 0) format = "- " + format;
      kembalian.val(format);
    }

    hargaDiskon.on("keyup", function(e) {
      $(e.target).focus();
      e.target.value = formatRupiah(e.target.value, "Rp.");
      hitung()
    })

    tunai.on("keyup", function(e) {
      // e.target.value = formatRupiah(e.target.value, "Rp.");
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
</script>