<link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/fapicker/dist/css/fontawesome-iconpicker.min.css">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header bg-light">
          Tambah Menu
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label" for="menuLabel">Nama Menu</label>
            <input class="form-control" type="text" id="menuLabel" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="menuTautan">Tautan</label>
            <input class="form-control" type="text" id="menuTautan" required>
          </div>
          <div class="mb-3 iconpicker-container">
            <label class="form-label" for="menuIkon">Icon Menu</label>
            <input class="form-control icp icp-auto" data-input-search="true" value="fas fa-plane" type="text" id="menuIkon" required />
          </div>
          <button class="btn btn-sm btn-success" id="submit">Simpan</button>
          <button class="btn btn-sm btn-light" id="reset">Reset</button>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-header bg-light">
          Daftar Menu
        </div>
        <div class="card-body min-vh-100">
          <menu id="nestable-menu">
            <button class="btn btn-sm btn-success" type="button" data-action="expand-all">Expand All</button>
            <button class="btn btn-sm btn-light" type="button" data-action="collapse-all">Collapse All</button>
          </menu>
          <input type="hidden" id="menuId">
          <div class="cf nestable-lists">
            <div class="dd" id="nestable">
              <?= $this->menus->menu_content(); ?>
            </div>
          </div>
          <input type="hidden" id="nestable-output">
          <div class="w-100 d-flex justify-content-end mt-3">
            <!-- <button class="btn btn-sm btn-success px-5" id="save">Simpan Perubahan</button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url(); ?>_assets/vendor/fapicker/dist/js/fontawesome-iconpicker.min.js"></script>
<script src="<?= base_url(); ?>_assets/vendor/jquery/js/jquery.nestable.js"></script>
<script>
  // --------------------------------------
	// CSRF TOKEN
	// --------------------------------------
	let csfrData = {};
	csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
	$.ajaxSetup({
		data: csfrData
	});

  $(document).ready(function() {
    // fapicker
    $('.icp-auto').iconpicker();

    let updateOutput = function(e) {
      let list = e.length ? e : $(e.target),
        output = list.data('output');
      if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
      } else {
        output.val('JSON browser support required for this demo.');
      }
    };

    // nestable
    $('#nestable').nestable({
        group: 1
      })
      .on('change', updateOutput);

    updateOutput($('#nestable').data('output', $('#nestable-output')));

    $('#nestable-menu').on('click', function(e) {
      let target = $(e.target),
        action = target.data('action');
      if (action === 'expand-all') {
        $('.dd').nestable('expandAll');
      }
      if (action === 'collapse-all') {
        $('.dd').nestable('collapseAll');
      }
    });

    $("#submit").click(function() {
      // Wait Me
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

      let dataString = {
        menuLabel: $("#menuLabel").val(),
        menuTautan: $("#menuTautan").val(),
        menuIkon: $("#menuIkon").val(),
        menuId: $("#menuId").val(),
      };

      $.ajax({
        type: "POST",
        url: `<?= site_url() ?>backoffice/admin/Menu/save`,
        data: dataString,
        dataType: "json",
        cache: false,
        success: function(data) {
          if (data.type == 'add') {
            $("#menu-id").append(data.menu);
          } else if (data.type == 'edit') {
            $('#label_show' + data.id).html(data.label);
            $('#link_show' + data.id).html(data.link);
          }

          location.reload();
        },
        error: function(xhr, status, error) {
          alert(error);
        },
      });
    });

    $(".dd").on('change', function() {
      // Wait Me
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

      let dataString = {
        data: $("#nestable-output").val(),
      };

      $.ajax({
        type: "POST",
        url: `<?= site_url() ?>backoffice/admin/Menu/change_order`,
        data: dataString,
        cache: false,
        success: function(data) {
          location.reload();
        },
        error: function(xhr, status, error) {
          alert(error);
        },
      });
    });

    $("#save").click(function() {
      // Wait Me
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

      let dataString = {
        data: $("#nestable-output").val(),
      };

      $.ajax({
        type: "POST",
        url: `<?= site_url() ?>backoffice/admin/Menu/save`,
        data: dataString,
        cache: false,
        success: function(data) {
          location.reload();
        },
        error: function(xhr, status, error) {
          alert(error);
        },
      });
    });

    $(document).on("click", ".del-button", function() {
      Swal.fire({
        title: "Yakin ingin menhapus menu ini?",
        text: "Data yang dihapus tidak dapat dikembalikan lagi!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus ini!"
      }).then(result => {
        if (result.isConfirmed) {
          let menuId = $(this).attr('id');

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

          $.ajax({
            type: "POST",
            url: `<?= site_url() ?>backoffice/admin/Menu/delete`,
            data: {
              menuId: menuId
            },
            cache: false,
            success: function(data) {
              $("li[data-id='" + menuId + "']").remove();
              location.reload();
            },
            error: function(xhr, status, error) {
              alert(error);
            },
          });
        }
      })
    });

    $(document).on("click", ".edit-button", function() {
      let menuId = $(this).attr('id');
      let menuLabel = $(`#label_show${menuId}`).html();
      let menuTautan = $(`#link_show${menuId}`).html();
      let menuIkon = $(this).attr('icon');

      $("#menuId").val(menuId);
      $("#menuLabel").val(menuLabel);
      $("#menuTautan").val(menuTautan);
      $("#menuIkon").val(menuIkon);
    });

    $(document).on("click", "#reset", function() {
      $('#menuLabel').val('');
      $('#menuTautan').val('');
      $('#menuId').val('');
      $("#menuIkon").val('fas fa-plane');
    });

    $("#menuLabel").keyup(function() {
      let menuLabel = $("#menuLabel").val();

      let slug = menuLabel.toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');

      $("#menuTautan").val(`backoffice/${slug}`)
    })
  });
</script>