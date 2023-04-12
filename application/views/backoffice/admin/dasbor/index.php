<div class="container-fluid">
  <!-- Small boxes (Stat box) -->
  <section class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          <h3><?= $transaksi_today ?></h3>

          <p>Transaksi Hari ini</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-bag"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3><?= $transaksi_all ?></h3>

          <p>Semua Transaksi</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3><?= $produk_all ?></h3>

          <p>Semua Produk</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-person-add"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3><?= $produk_ready ?></h3>

          <p>Menu Tersedia Hari ini</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-pie-graph"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <!-- ./col -->
    <div class="col-lg-6 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3><?= rupiah($total_harga) ?></h3>

          <p>Pendapatan Hari ini</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-pie-graph"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3><?= $total_transaksi ?></h3>

          <p>Produk Terjual Hari ini</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-pie-graph"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
  </section>
  <!-- /.row -->
</div><!-- /.container-fluid -->