<!-- Produk Terbaru -->
<div class="container my-5 pt-5">
  <div class="row">
    <div class="col-md-12 text-center mb-5 pb-3">
      <h1 class="fw-bold">Semua Menu</h1>
    </div>
    <div class="col-12 mb-5">
      <form action="" method="get" accept-charset="utf-8">
        <div class="d-flex">
          <div class="input-group">
            <input type="search" class="form-control form-control-lg" placeholder="Cari Menu.." aria-label="Pencarian" aria-describedby="basic-addon2" name="cari" value="<?= $this->input->get("cari") ?>">
            <button type="submit" class="input-group-text waitme" id="basic-addon2" style="cursor: pointer;">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12 mb-5">
          <h5 class="fw-bold mb-3">Filter</h5>
          <div class="card shadow">
            <div class="card-body p-0">
              <div class="accordion" id="accordionKategori">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="kategori-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#kategori-collapseOne" aria-expanded="true" aria-controls="kategori-collapseOne">
                      Kategori
                    </button>
                  </h2>
                  <div id="kategori-collapseOne" class="accordion-collapse collapse show" aria-labelledby="kategori-headingOne">
                    <div class="accordion-body p-0">
                      <div class="list-group list-group-flush">
                        <a href="<?= base_url() ?>daftar-menu?kategori=&amp;" class="list-group-item list-group-item-action border-0 waitme <?= $this->uri->segment(2) == "" ? 'active' : null; ?>">Lihat Semua</a>
                        <?php foreach ($kategori as $kategoris) : ?>
                          <a href="<?= base_url("daftar-menu/{$kategoris->pkId}") ?>" class="list-group-item list-group-item-action border-0 waitme <?= $kategoris->pkId == $this->uri->segment(2) ? 'active' : null; ?>"><?= $kategoris->pkNama ?></a>
                        <?php endforeach; ?>
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
    <div class="col-md-9">
      <h5 class="fw-bold mb-3">Daftar Menu Kami</h5>
      <div class="row">
        <?php if (!$produk) : ?>
          <div class="col">
            <div class="alert alert-danger text-center">
              <?php if ($this->input->get('cari')) : ?>
                <p class="mb-0">Menu tidak Tersedia, untuk kata kunci <span class="fw-bold"><?= $this->input->get('cari') ?></span>!</p>
              <?php else : ?>
                <p class="mb-0 fw-bold">Menu tidak Tersedia!</p>
              <?php endif; ?>
            </div>
          </div>
          <?php else : foreach ($produk as $produks) : ?>
            <div class="col-6 col-lg-3">
              <div class="card shadow mb-5 bg-body rounded border-0">
                <div class="card-body text-center">
                  <a class="text-decoration-none text-dark" href="<?= site_url("produk/{$produks->produkId}/lihat") ?>">
                    <div class="image-slider ratio ratio-4x3 rounded" style="background-image: url('<?= base_url("_uploads/produk/{$produks->produkGambar}") ?>') ;">
                    </div>
                    <span class="d-block mt-4">
                      <?= $produks->produkNama ?> </span>
                    <span class="d-block h4 fw-bold mt-2">
                      <?= rupiah($produks->produkHarga - $produks->produkHargaDiskon) ?> </span>
                    <?php if ($produks->produkHargaDiskon != 0) : ?>
                      <span class="text-decoration-line-through text-secondary">
                        <?= rupiah($produks->produkHarga) ?> </span>
                      <span class="text-danger ms-2">
                        <?= round(($produks->produkHargaDiskon / $produks->produkHarga) * 100) ?>% </span>
                    <?php endif; ?>
                  </a>
                </div>
                <div class="card-footer text-center">
                  <a class="text-decoration-none waitme" href="<?= base_url("order") ?>">Pesan Sekarang</a>
                </div>
              </div>
            </div>
        <?php endforeach;
        endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- /Produk Terbaru -->