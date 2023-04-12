<!-- <script>
  $(document).ready(function() {
    // fapicker
    $('.icp-auto').iconpicker();

    var updateOutput = function(e) {
      var list = e.length ? e : $(e.target),
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
      var target = $(e.target),
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

      var dataString = {
        label: $("#label").val(),
        link: $("#link").val(),
        id: $("#id").val(),
        icon: $("#icon").val()
      };
      $.ajax({
        type: "POST",
        url: `<?= site_url() ?>backoffice/admin/Menu/save_menu`,
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

      var dataString = {
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

      var dataString = {
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
          var id = $(this).attr('id');

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
              id: id
            },
            cache: false,
            success: function(data) {
              $("li[data-id='" + id + "']").remove();
              location.reload();
            },
            error: function(xhr, status, error) {
              alert(error);
            },
          });
        }
      })

      if (x) {
        // Wait Me

      }
    });

    $(document).on("click", ".edit-button", function() {
      var id = $(this).attr('id');
      var label = $(`#label_show${id}`).html();
      var link = $(`#link_show${id}`).html();
      var icon = $(this).attr('icon');

      $("#id").val(id);
      $("#label").val(label);
      $("#link").val(link);
      $("#icon").val(icon);
    });

    $(document).on("click", "#reset", function() {
      $('#label').val('');
      $('#link').val('');
      $('#id').val('');
    });

    $("#label").keyup(function() {
      let label = $("#label").val();

      let slug = label.toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');

      $("#link").val(slug)
    })
  });
</script> -->