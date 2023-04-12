<!DOCTYPE html>
<html lang="en">
<!-- For RTL verison -->
<!-- <html lang="en" dir="rtl"> -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Primary Meta Tags -->
  <title><?= $title; ?> | <?= SITE_NAME; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="title" content="AdminLTE 4 | Dashboard">
  <meta name="author" content="ColorlibHQ">
  <meta name="description" content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!-- By adding ./css/dark/adminlte-dark-addon.css then the page supports both dark color schemes, and the page author prefers / default is light. -->
  <meta name="color-scheme" content="light dark">
  <!-- By adding ./css/dark/adminlte-dark-addon.css then the page supports both dark color schemes, and the page author prefers / default is dark. -->
  <!-- <meta name="color-scheme" content="dark light"> -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/adminlte/vendor/@fortawesome/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/adminlte/css/adminlte.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/adminlte/vendor/overlayscrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- WaitMe -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/waitme/css/waitMe.min.css">
  <!-- Style -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/css/style.css">
  <!-- Datatables BS 5 -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/datatables-bs5/css/dataTables.bootstrap5.min.css">
  <!-- Dropify -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/summernote/css/summernote.min.css">
  <!-- Jquery -->
  <script src="<?= base_url(); ?>_assets/vendor/jquery/js/jquery-3.6.0.min.js"></script>
</head>

<body class="layout-fixed">
  <div class="wrapper">
    <!-------------------------------------- 
      NAVBAR
    --------------------------------------->
    <?php $this->load->view('template/partials/dashboard/navbar'); ?>
    <!-------------------------------------- 
      NAVBAR
    --------------------------------------->

    <!-------------------------------------- 
      SIDEBAR
    --------------------------------------->
    <?php $this->load->view('template/partials/dashboard/sidebar'); ?>
    <!-------------------------------------- 
      SIDEBAR
    --------------------------------------->

    <!-------------------------------------- 
      MAIN CONTENT
    --------------------------------------->
    <main class="content-wrapper">
      <div class="content-header <?= substr($this->uri->segment(2), 0, 7) == "pesanan" ? "d-none mt-5" : substr($this->uri->segment(3), 0, 7) ?>">
        <div class="container-fluid">
          <section class="row mb-2">
            <div class="col-sm-6">
              <h1 class="fs-3 m-0"> Halaman <?= $title; ?> </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                  <a class="waitme" href="#" onclick="history.back()">
                    <i class="fas fa-arrow-left me-2">
                    </i> Kembali</a>
                </li>
              </ol>
            </div>
          </section>
        </div>
      </div>
      <div class="content <?= substr($this->uri->segment(2), 0, 7) == "pesanan" ? "mt-2" : substr($this->uri->segment(3), 0, 7) ?>">
        <!-------------------------------------- 
          CONTENT
        --------------------------------------->
        <?= $contents; ?>
        <!-------------------------------------- 
          CONTENT
        --------------------------------------->
      </div>
    </main>
    <!-------------------------------------- 
      MAIN CONTENT
    --------------------------------------->
  </div>

  <!--REQUIRED SCRIPTS-->
  <!--overlayScrollbars-->
  <script script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/overlayscrollbars/js/OverlayScrollbars.min.js"></script>
  <!-- Bootstrap 5 -->
  <script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>_assets/vendor/adminlte/js/adminlte.js"></script>
  <!-- sweetalert JS -->
  <script src="<?= base_url() ?>_assets/vendor/sweetalert/js/sweetalert2.all.min.js"></script>
  <!-- ChartJS -->
  <script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/chart.js/dist/chart.js"></script>
  <!-- Datatables -->
  <script src="<?= base_url(); ?>_assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url(); ?>_assets/vendor/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
  <!-- WaitMe -->
  <script src="<?= base_url(); ?>_assets/vendor/waitme/js/waitMe.min.js"></script>
  <!-- Dropify -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
  <!-- Summernote -->
  <script src="<?= base_url(); ?>_assets/vendor/summernote/js/summernote.min.js"></script>
  <!-- Script -->
  <script>
    $(document).ready(function() {

      // -------------------------------------- 
      // ALERT SUCCESS
      // -------------------------------------- 
      <?php if ($this->session->flashdata('success')) : ?>
        Swal.fire({
          title: 'Berhasil',
          text: `<?= strip_tags($this->session->flashdata('success')); ?>`,
          icon: 'success',
        })
      <?php endif ?>
      // -------------------------------------- 
      // ALERT SUCCESS
      // -------------------------------------- 

      // -------------------------------------- 
      // ALERT WARNING
      // -------------------------------------- 
      <?php if ($this->session->flashdata('warning')) : ?>
        Swal.fire({
          title: 'Peringatan',
          text: `<?= strip_tags($this->session->flashdata('warning')); ?>`,
          icon: 'warning',
        })
      <?php endif ?>
      // -------------------------------------- 
      // ALERT WARNING
      // -------------------------------------- 

      // -------------------------------------- 
      // ALERT FAILED
      // -------------------------------------- 
      <?php if ($this->session->flashdata('error')) : ?>
        Swal.fire({
          title: 'Gagal',
          text: `<?= strip_tags($this->session->flashdata('error')); ?>`,
          icon: 'error',
        })
      <?php endif ?>
      // -------------------------------------- 
      // ALERT SUCCESS
      // -------------------------------------- 

      // -------------------------------------- 
      // OPTION TOAST
      // -------------------------------------- 
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })

      // -------------------------------------- 
      // ALERT SUCCESS
      // -------------------------------------- 
      <?php if ($this->session->flashdata('success')) : ?>
        Toast.fire({
          icon: 'success',
          title: `<?= strip_tags($this->session->flashdata('success')); ?>`
        })
      <?php endif ?>
      // -------------------------------------- 
      // ALERT SUCCESS
      // -------------------------------------- 

      // -------------------------------------- 
      // ALERT WARNING
      // -------------------------------------- 
      <?php if ($this->session->flashdata('warning')) : ?>
        Toast.fire({
          icon: 'warning',
          title: `<?= strip_tags($this->session->flashdata('warning')); ?>`
        })
      <?php endif ?>
      // -------------------------------------- 
      // ALERT WARNING
      // -------------------------------------- 

      // -------------------------------------- 
      // ALERT FAILED
      // -------------------------------------- 
      <?php if ($this->session->flashdata('error')) : ?>
        Toast.fire({
          icon: 'error',
          title: `<?= strip_tags($this->session->flashdata('error')); ?>`
        })
      <?php endif ?>
      // -------------------------------------- 
      // ALERT SUCCESS
      // -------------------------------------- 

      $("body").on("click", (function(e) {
        // -------------------------------------- 
        // CONFIRM
        // -------------------------------------- 
        if ($(e.target).hasClass("confirm")) {
          e.preventDefault();
          const href = $(e.target).attr("href");
          let message = "Data akan berubah seperti yang sudah didefinisikan!";
          let textMessage = $(e.target).data("message");
          if (textMessage != "") message = textMessage;
          Swal.fire({
            title: "Anda yakin?",
            text: message,
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
              result.isConfirmed && (document.location.href = href)
            }
          })
        }
        // -------------------------------------- 
        // CONFIRM
        // -------------------------------------- 

        // -------------------------------------- 
        // CONFIRM DESTROY
        // -------------------------------------- 
        if ($(e.target).hasClass("destroy")) {
          e.preventDefault();
          e.stopPropagation();
          const href = $(e.target).attr("href");
          Swal.fire({
            title: "Yakin ingin mengapus data ini?",
            text: "Data yang dihapus tidak dapat dikembalikan lagi!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus ini!"
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
              result.isConfirmed && (document.location.href = href)
            }
          })
        }
        // -------------------------------------- 
        // CONFIRM DESTROY
        // -------------------------------------- 

        // -------------------------------------- 
        // PRELOADER
        // -------------------------------------- 
        if ($(e.target).hasClass("waitme")) {
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
        }
        // -------------------------------------- 
        // PRELOADER
        // -------------------------------------- 
      }));

      // -------------------------------------- 
      // MENU ACTIVE
      // -------------------------------------- 
      var url = window.location;
      $('a.nav-link').filter(function() {
        // console.log(this.href);
        return this.href.split("/")[4] == url.href.split("/")[4];
      }).addClass('active').parent().parent().parent().addClass('menu-open menu-is-open').children().addClass('active').parent().parent().parent().addClass('menu-open menu-is-open').children().addClass('active');
      $('ul.treeview-menu a').filter(function() {
        return this.href == url;
      }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
      // -------------------------------------- 
      // MENU ACTIVE
      // -------------------------------------- 
    })
  </script>
</body>

</html>