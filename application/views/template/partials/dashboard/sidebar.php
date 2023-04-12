<aside class="main-sidebar sidebar-bg-dark sidebar-color-dark shadow">
  <div class="brand-container">
    <a href="<?= site_url(); ?>backoffice/dasbor" class="brand-link waitme">
      <img src="<?= base_url(); ?>_assets/images/logo-all.png" alt="Logo" class="bg-white brand-image rounded-circle shadow">
      <span class="brand-text fw-light"><?= SITE_NAME; ?></span>
    </a>
    <a class="pushmenu mx-1" data-lte-toggle="sidebar-mini" href="<?= site_url(); ?>backoffice/dasbor" role="button"><i class="fas fa-angle-double-left"></i></a>
  </div>
  <!-- Sidebar -->
  <div class="sidebar m-0 p-0">
    <nav class="mt-2">
      <!-- Sidebar Menu -->
      <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <!-- Menu -->
        <?= $this->menus->menu_sidebar(); ?>
        <!-- /Menu -->
      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>