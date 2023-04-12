<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title"><?= $title; ?></h3>
        </div>
        <div class="card-body">
          <?php foreach ($menus as $key => $menu) : ?>
            <?php $is_menu_access = $this->akses->check_menu($group->grupId, $menu->menuId); ?>
            <?php $is_check = $is_menu_access != null; ?>
            <div class="card">
              <div class="card-body">
                <i class="me-2 <?= $menu->menuIkon; ?>"></i>
                <?= $menu->menuLabel; ?>
                <div class="form-check form-switch float-end">
                  <input class="form-check-input form-check-input-crud" type="checkbox" data-grupmenugrupid="<?= $group->grupId; ?>" data-grupmenumenuid="<?= $menu->menuId; ?>" <?= $is_check ? 'checked' : ''; ?>>
                </div>
              </div>
            </div>
            <div class="ms-5">
              <div class="card">
                <div class="card-body">
                  Tambah
                  <div class="form-check form-switch float-end">
                    <input class="form-check-input form-check-input-crud" type="checkbox" data-grupmenugrupid="<?= $group->grupId; ?>" data-grupmenumenuid="<?= $menu->menuId; ?>" data-type="grupMenuTambah" data-access="<?= ($is_menu_access->grupMenuTambah ?? 0) == 0 ? 1 : 0; ?>" <?= !$is_check ? 'disabled' : ''; ?> <?= $this->akses->is_check($is_check, $is_menu_access->grupMenuTambah ?? false); ?>>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  Ubah
                  <div class="form-check form-switch float-end">
                    <input class="form-check-input form-check-input-crud" type="checkbox" data-grupmenugrupid="<?= $group->grupId; ?>" data-grupmenumenuid="<?= $menu->menuId; ?>" data-type="grupMenuUbah" data-access="<?= ($is_menu_access->grupMenuUbah ?? 0) == 0 ? 1 : 0; ?>" <?= !$is_check ? 'disabled' : ''; ?> <?= $this->akses->is_check($is_check, $is_menu_access->grupMenuUbah ?? false); ?>>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  Hapus
                  <div class="form-check form-switch float-end">
                    <input class="form-check-input form-check-input-crud" type="checkbox" data-grupmenugrupid="<?= $group->grupId; ?>" data-grupmenumenuid="<?= $menu->menuId; ?>" data-type="grupMenuHapus" data-access="<?= ($is_menu_access->grupMenuHapus ?? 0) == 0 ? 1 : 0; ?>" <?= !$is_check ? 'disabled' : ''; ?> <?= $this->akses->is_check($is_check, $is_menu_access->grupMenuHapus ?? false); ?>>
                  </div>
                </div>
              </div>
              <?php foreach ($menu->actions as $actions) : ?>
                <div class="card">
                  <div class="card-body">
                    <?= ucfirst($actions->aksiLabel); ?>
                    <a class="text-primary ms-3 me-2 waitme" href="<?= site_url("backoffice/hak-akses/{$group->grupId}/grup/{$menu->menuId}/menu/{$actions->aksiId}/menu-grup/ubah") ?>"><i class="fas fa-edit"></i></a>
                    <a class="text-danger destroy" href="<?= site_url("backoffice/hak-akses/{$group->grupId}/grup/{$actions->aksiId}/menu-grup/hapus"); ?>"><i class="fas fa-trash destroy" href="<?= site_url("backoffice/hak-akses/{$group->grupId}/grup/{$actions->aksiId}/menu-grup/hapus"); ?>"></i></a>
                    <div class="form-check form-switch float-end">
                      <input class="form-check-input form-check-input-uk" type="checkbox" data-agaksiid="<?= $actions->aksiId; ?>" data-aggrupid="<?= $group->grupId; ?>" <?= !$is_check ? 'disabled' : ''; ?> <?= $this->akses->is_check_aksi($actions->aksiId, $group->grupId); ?>>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="card">
                <div class="card-body">
                  <i class="fas fa-plus text-center w-100"></i>
                  <a class="stretched-link" href="<?= site_url("backoffice/hak-akses/{$group->grupId}/grup/{$menu->menuId}/menu/tambah"); ?>"></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // --------------------------------------
    // CSRF TOKEN
    // --------------------------------------
    var csfrData = {};
    csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
    $.ajaxSetup({
      data: csfrData
    });

    // CHECK ACCESS SECTION
    $('.form-check-input-crud').on('click', function() {
      const grupMenuGrupId = $(this).data('grupmenugrupid');
      const grupMenuMenuId = $(this).data('grupmenumenuid');
      const type = $(this).data('type');
      const access = $(this).data('access');

      $.ajax({
        data: {
          grupMenuGrupId: grupMenuGrupId,
          grupMenuMenuId: grupMenuMenuId,
          type: type,
          access: access
        },
        url: `<?= site_url() ?>backoffice/admin/HakAkses/change_access`,
        method: 'post',
        success: function(test) {
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
          location.reload();
        }
      })
    })

    $('.form-check-input-uk').on('click', function() {
      const agAksiId = $(this).data('agaksiid');
      const agGrupId = $(this).data('aggrupid');

      $.ajax({
        data: {
          agAksiId: agAksiId,
          agGrupId: agGrupId,
        },
        url: `<?= site_url() ?>backoffice/admin/Aksi/change_access`,
        method: 'post',
        success: function(test) {
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
          location.reload();
        }
      })
    })

    $('#table').DataTable();
  })
</script>