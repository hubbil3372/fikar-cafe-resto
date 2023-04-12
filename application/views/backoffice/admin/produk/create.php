<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?></h3>
        </div>
        <div class="card-body">
          <?= form_open_multipart('', 'class="align-self-center"'); ?>
          <div class="row">
            <div class="col-md-12">
              <div class="mb-4">
                <label class="form-label" for="produkNama">Nama Produk</label>
                <input class="form-control <?= form_error('produkNama') ? 'is-invalid' : null; ?>" id="produkNama" name="produkNama" type="text" value="<?= set_value('produkNama'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('produkNama') ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label class="form-label" for="produkKeterangan">Deskripsi</label>
                <input class="form-control <?= form_error('produkKeterangan') ? 'is-invalid' : null; ?>" id="produkKeterangan" name="produkKeterangan" type="text" value="<?= set_value('produkKeterangan'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('produkKeterangan') ?>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label" for="produkHarga">Harga</label>
                <input class="form-control <?= form_error('produkHarga') ? 'is-invalid' : null; ?>" id="produkHarga" name="produkHarga" type="text" value="<?= set_value('produkHarga'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('produkHarga') ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label" for="produkHargaGrosir">Harga Grosir <small style="font-size:12px" class="text-danger">Harga Untuk Partai besar!</small></label>
                <input class="form-control <?= form_error('produkHargaGrosir') ? 'is-invalid' : null; ?>" id="produkHargaGrosir" name="produkHargaGrosir" type="text" value="<?= set_value('produkHargaGrosir'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('produkHargaGrosir') ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label" for="produkHargaDiskon">Diskon <small style="font-size:12px" class="text-danger">Masukan potongan harga!</small></label>
                <input class="form-control <?= form_error('produkHargaDiskon') ? 'is-invalid' : null; ?>" id="produkHargaDiskon" name="produkHargaDiskon" type="text" value="<?= set_value('produkHargaDiskon'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('produkHargaDiskon') ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label" for="produkStatus">Status</label>
                <select name="produkStatus" id="produkStatus" class="form-control <?= form_error('produkStatus') ? 'is-invalid' : null; ?>">
                  <option value="0">Tidak Aktif</option>
                  <option value="1" <?= set_value('produkStatus') == '1' ? 'selected' : null; ?>>Aktif</option>
                </select>
                <div class="invalid-feedback">
                  <?= form_error('produkStatus') ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label" for="produkKategoriId">Kategori</label>
                <select name="produkKategoriId" id="produkKategoriId" class="form-control <?= form_error('produkKategoriId') ? 'is-invalid' : null; ?>">
                  <option value="">Pilih Kategori Produk</option>
                  <?php foreach ($kategori as $kategories) : ?>
                    <option value="<?= $kategories->pkId ?>" <?= set_value('produkKategoriId') == $kategories->pkId ? 'selected' : null; ?>><?= $kategories->pkNama ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                  <?= form_error('produkKategoriId') ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label" for="produkGambar">Gambar</label>
                <input class="form-control <?= form_error('produkGambar') ? 'is-invalid' : null; ?>" id="produkGambar" name="produkGambar" type="file" value="<?= set_value('produkGambar'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('produkGambar') ?>
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

<script>
  $(document).ready(function() {
    let namaSingkat = document.getElementById("produkNamaSingkat");
    let harga = document.getElementById("produkHarga");
    let grosir = document.getElementById("produkHargaGrosir");
    let diskon = document.getElementById("produkHargaDiskon");
    $(diskon).val(formatRupiah("0", "Rp."));

    // namaSingkat.addEventListener("keyup", function(s) {
    //   s.target.value = s.target.value.toUpperCase();
    // })

    harga.addEventListener("keyup", function(e) {
      e.target.value = formatRupiah(e.target.value, "Rp. ");
      $(grosir).val(e.target.value);
    })

    grosir.addEventListener("keyup", function(e) {
      e.target.value = formatRupiah(e.target.value, "Rp. ");
    })

    diskon.addEventListener("keyup", function(e) {
      e.target.value = formatRupiah(e.target.value, "Rp. ");
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
</script>