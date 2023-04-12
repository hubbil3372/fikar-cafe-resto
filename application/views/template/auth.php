<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | <?= SITE_NAME; ?></title>
  <!-- Bootstraps 5 -->
  <link href="<?= base_url() ?>_assets/vendor/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <!-- WaitMe -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/waitme/css/waitMe.min.css">
  <!-- Custom Style -->
  <link rel="stylesheet" href="<?= base_url() ?>_assets/css/login/style.css">
  <!-- Jquery -->
  <script src="<?= base_url(); ?>_assets/vendor/jquery/js/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="container-fluid h-100">
    <div class="row h-100">
      <div class="col-md-6 bg-login h-min-100 justify-content-center d-none d-md-flex">
        <div class="align-self-center text-center">
          <img class="img-fluid mb-5 w-50 rounded-circle bg-white" src="<?= base_url() ?>_assets/images/logo-all.png" alt="">
          <div class="d-flex justify-content-center align-items-center">
            <!-- <img style="width: 80px; height: 80px;" src="<?= base_url() ?>_assets/images/logo.png" alt="Logo UNTIRTA"> -->
            <div>
              <small class="text-white"><?= COPYRIGHT; ?></small>
              <h1 class="fw-bold text-uppercase mb-0 text-white"><?= SITE_NAME; ?></h1>
            </div>
          </div>
        </div>
      </div>

      <!-------------------------------------- 
        CONTENT
      --------------------------------------->
      <?= $contents ?>
      <!-------------------------------------- 
        CONTENT
      --------------------------------------->

    </div>
  </div>

  <!-- Bootstrap -->
  <script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- sweetalert JS -->
  <script src="<?= base_url() ?>_assets/vendor/sweetalert/js/sweetalert2.all.min.js"></script>
  <!-- WaitMe -->
  <script src="<?= base_url(); ?>_assets/vendor/waitme/js/waitMe.min.js"></script>
  <!-- Script -->
  <script>
    $(document).ready(function() {
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
        // CONFIRM DESTROY
        // -------------------------------------- 
        if ($(e.target).hasClass("destroy")) {
          e.preventDefault();
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
    })
  </script>
</body>

</html>