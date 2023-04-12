<?php $user = $this->ion_auth->user()->row(); ?>
<nav class="main-header navbar navbar-expand navbar-light">
  <div class="container-fluid">
    <!-- Start navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar-full" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a href="<?= site_url(); ?>backoffice/dasbor" class="nav-link waitme">Dasbor</a>
      </li>
    </ul>

    <!-- End navbar links -->
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown user-menu">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
          <?php $image = $user->pengFoto == 'default.jpg' ? "https://avatar.oxro.io/avatar.svg?name={$user->pengNama}&background=157347&caps=3&bold=true": base_url("_assets/images/profile/{$user->pengFoto}"); ?>
          <img class="user-image img-circle shadow object-fit-cover" src="<?= $image; ?>" alt="User Image">
          <span class="d-none d-md-inline"><?= $user->pengNama ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <!-- Menu Footer-->
          <li>
            <a href="<?= site_url() ?>backoffice/data-diri" class="btn btn-default btn-flat w-100 text-start waitme">Data Diri</a>
          </li>
          <li>
            <a href="<?= site_url() ?>backoffice/ganti-password" class="btn btn-default btn-flat w-100 text-start waitme">Ganti Password</a>
          </li>
          <li>
            <a href="<?= site_url(); ?>keluar" class="btn btn-default btn-flat w-100 text-start waitme">Keluar</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </div>
</nav>