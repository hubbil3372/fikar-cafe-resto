<div class="bg-muted py-5">
  <div class="container px-5 px-md-0 text-center text-md-start">
    <div class="row mb-5 justify-content-between">
      <div class="col-md-7">
        <h2 class="display-4">Selamat Datang </h2>
        <h3 class="display-6 text-orange fw-bold">Di <?= COPYRIGHT ?></h3>
        <p class="text-muted fs-5 text-justify">Restoran sekaligus Cafe yang akan memberikan pelayanan terbaik dan pengalaman santap yang berkesan. ayo datang dan pesan sekarang dan cobain berbagai menu pilihan dari kami
          dari mulai khas luar negeri dan dalam negeri tentunya. kami juga menyediakan pesanan online. tunggu apalagi pesan sekrang juga.
        </p>
      </div>
      <div class="col-md-3 d-none d-md-block">
        <img src="<?= base_url() ?>_assets/images/logo-all.png" class="img-fluid" alt="image lorem">
      </div>
    </div>
  </div>
</div>
<?php if ($produk_banner) : ?>
  <!-- Banner Promo -->
  <div class="container p-0 mt-4">
    <div class="card card-body">
      <div id="carouselExampleCaptions" class="carousel slide carousel-dark" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <?php foreach ($produk_banner as $key => $bannerIndicators) : ?>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="<?= $key ?>" <?= $key == 0 ? 'class="active" class="active" aria-current="true"' : null ?> aria-label="Slide <?= ($key + 1) ?>"></button>
          <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
          <?php foreach ($produk_banner as $key => $banners) : ?>
            <div class="carousel-item <?= $key == 0 ? 'active' : null ?>">
              <div class="container py-5 px-5">
                <div class="row">
                  <div class="col-md-3">
                    <div class="image-slider d-none d-md-block ratio ratio-1x1 rounded" style="background-image: url('<?= base_url("_uploads/produk/{$banners->produkGambar}") ?>') ;">
                    </div>
                  </div>
                  <div class="col-md-9 d-flex align-items-center">
                    <div class="text-start ms-5">
                      <h4 class=""><?= $banners->produkNama ?></h4>
                      <h3 class="fw-bold"><?= rupiah($banners->produkHarga) ?></h3>
                      <p><?= $banners->produkKeterangan ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>
  <!-- /Banner Promo -->
<?php endif; ?>


<!-- Produk Terbaru -->
<div class="container mt-5 pt-5">
  <div class="row">
    <div class="col-md-12 text-center mb-5 pb-3">
      <h1 class="fw-bold">Menu Teratas</h1>
    </div>
    <?php foreach ($produk as $produks) : ?>
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
    <?php endforeach; ?>
    <div class="col-md-12 text-center mb-5 pb-3">
      <a class="btn btn-lg btn-primary px-3 w-50 waitme" href="<?= base_url("daftar-menu") ?>">Lihat Menu lainnya...</a>
    </div>
  </div>
</div>
<!-- /Produk Terbaru -->

<!-- maps -->
<div class="bg-orange py-5 mb-5">
  <div class="container mb-5">
    <div class="row">
      <div class="col-md-12 text-center mb-3">
        <h1 class="fw-bold text-white">Temukan Kami</h1>
      </div>
      <div class="col-12">
        <div class="embed-google-maps">
          <iframe class="rounded" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.742997646754!2d106.35178031476948!3d-6.297464895442203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xd45cb6811b4b8cf4!2zNsKwMTcnNTAuOSJTIDEwNsKwMjEnMTQuMyJF!5e0!3m2!1sid!2sid!4v1668952108954!5m2!1sid!2sid" width="100%" style="height:50vh;" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>