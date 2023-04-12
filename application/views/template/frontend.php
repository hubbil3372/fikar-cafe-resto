<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= @$title ?? SITE_NAME ?></title>

  <!-- Bootstraps 5 -->
  <link href="<?= base_url() ?>_assets/vendor/bootstrap5/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome JS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- Owl Carousel -->
  <link rel="stylesheet" href="<?= base_url() ?>_assets/vendor/owlcarousel/css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>_assets/vendor/owlcarousel/css/owl.theme.default.min.css">
  <!-- WaitMe -->
  <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/waitme/css/waitMe.min.css">

  <!-- Custom Style -->
  <link rel="stylesheet" href="<?= base_url() ?>_assets/frontend/css/style.css">
  <!-- Jquery 3.5.1 -->
  <script src="<?= base_url() ?>_assets/vendor/jquery/js/jquery-3.6.0.min.js"></script>
  <!-- Select2 -->
  <!-- Styles -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
</head>

<body>
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-orange shadow-sm py-2">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center waitme" href="<?= site_url('beranda') ?>">
          <img class="d-inline-block align-top me-3 bg-white rounded-circle" src="<?= base_url() ?>_assets/images/logo-all.png" width="auto" height="40" alt="#">
          <span class="fs-4 fw-bold text-uppercase"><?= COPYRIGHT ?></span>
        </a>
        <button class="navbar-toggler bg-p-sinovhi" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item mx-0 mx-lg-2">
              <a class="nav-link waitme <?= $this->uri->segment(1) == '' || $this->uri->segment(1) == 'beranda' ? 'active' : null ?>" aria-current="page" href="<?= site_url('beranda') ?>">
                <i class="fas fa-mail" aria-hidden="true"></i> Beranda
              </a>
            </li>
            <li class="nav-item mx-0 mx-lg-2">
              <a class="nav-link waitme <?= $this->uri->segment(1) == 'daftar-menu' ? 'active' : null ?>" aria-current="page" href="<?= site_url('daftar-menu') ?>">
                <i class="fas fa-mail" aria-hidden="true"></i> Daftar Menu
              </a>
            </li>
            <li class="nav-item mx-0 mx-lg-2">
              <a class="nav-link waitme <?= $this->uri->segment(1) == 'order' ? 'active' : null ?>" aria-current="page" href="<?= site_url('order') ?>">
                <i class="fas fa-mail" aria-hidden="true"></i> Order
              </a>
            </li>
          </ul>
          <!-- <ul class="navbar-nav">
            <li class="nav-item mx-0 mx-lg-2">
              <a class="nav-link waitme <?= $this->uri->segment(1) == 'transaksi' ? 'active' : null ?>" aria-current="page" href="<?= site_url('transaksi') ?>">
                <i class="fas fa-exchange-alt fa-lg" aria-hidden="true"></i>
              </a>
            </li>
            <li class="nav-item mx-0 mx-lg-2">
              <a class="nav-link waitme <?= $this->uri->segment(1) == 'keranjang' ? 'active' : null ?>" aria-current="page" href="<?= site_url('keranjang') ?>">
                <i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i>
              </a>
            </li>
            <?php if ($this->session->userdata('customerId')) { ?>
              <li class="nav-item mx-0 mx-lg-2 ps-3 pe-3">
                <div class="dropdown">
                  <button class="btn dropdown-toggle <?= $this->uri->segment(1) == 'profil' ? 'active' : null ?>" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if ($this->session->userdata('customerFoto') != 'default.png') { ?>
                      <img class="img img-fluid" width="34px" height="34px" src="<?= base_url('_uploads/profil/' . $this->session->userdata('customerFoto')) ?>">
                    <?php } else { ?>
                      <?php } ?>
                      <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.7936 0.603279C13.6813 0.804777 11.9191 1.30682 9.9536 2.2682C6.74248 3.83913 4.14711 6.4363 2.57841 9.64851C1.74168 11.3493 1.31136 12.7137 1.03302 14.5511C0.836648 15.8454 0.836648 17.968 1.03302 19.2794C1.36461 21.5394 2.16552 23.705 3.38424 25.6369C4.60296 27.5688 6.2125 29.2242 8.10937 30.4968C10.7968 32.3247 13.978 33.2891 17.228 33.2614C21.55 33.2614 25.5185 31.6596 28.5905 28.673C30.5525 26.7673 31.8384 24.7404 32.728 22.1517C33.3308 20.3979 33.5648 18.9106 33.5614 16.8546C33.5579 14.1447 33.0747 12.0819 31.8776 9.64851C30.5918 7.03415 28.6981 4.8911 26.2101 3.22959C25.4212 2.70194 23.7767 1.87716 22.8119 1.52368C20.6603 0.734765 18.0374 0.389827 15.7936 0.603279ZM18.9356 2.67803C23.1688 3.19031 26.8982 5.50755 29.2684 9.10207C30.404 10.8251 31.2339 13.1201 31.5003 15.2819C31.6044 16.1221 31.5874 17.886 31.4678 18.7996C31.1435 21.2566 30.1868 23.5873 28.6912 25.5635C28.0355 26.4309 27.8067 26.6768 27.7657 26.5624C27.6359 26.2158 26.7941 25.1178 26.2118 24.5389C24.7603 23.0908 22.7505 21.9826 20.5835 21.4345C18.63 20.9392 15.8961 20.9273 13.9836 21.4037C11.68 21.9792 9.6855 23.0755 8.17426 24.6004C7.65344 25.1263 6.81159 26.2397 6.69035 26.5624C6.64936 26.6751 6.42908 26.4395 5.7614 25.5635C4.61447 24.0596 3.78541 22.3381 3.32464 20.5038C3.02006 19.339 2.8719 18.1388 2.88408 16.9349C2.88408 15.2324 3.08387 13.9944 3.61493 12.3892C4.47097 9.81922 6.03683 7.54393 8.13152 5.82636C10.2262 4.10879 12.7642 3.01906 15.4521 2.68315C16.4118 2.56362 17.9708 2.56191 18.9356 2.67803ZM16.4169 8.45659C14.1424 8.86301 12.4348 10.6611 12.1786 12.9203C12.0935 13.632 12.1609 14.3537 12.3763 15.0374C12.5916 15.721 12.95 16.351 13.4276 16.8855C13.9053 17.42 14.4912 17.8467 15.1464 18.1373C15.8017 18.4278 16.5113 18.5756 17.228 18.5708C18.478 18.581 19.6873 18.1264 20.621 17.2953C21.5547 16.4641 22.1462 15.3157 22.2809 14.0729C22.4687 12.4165 21.871 10.8541 20.6057 9.69461C20.1054 9.23527 19.1781 8.73835 18.4917 8.55905C17.976 8.42415 16.8985 8.37121 16.4169 8.45659ZM18.6249 23.1421C19.5179 23.2445 20.1754 23.3897 20.9848 23.6629C23.1893 24.4091 25.0677 25.8623 26.0137 27.5562L26.2681 28.0088L26.0683 28.1727C25.6158 28.5415 24.289 29.3185 23.5069 29.672C21.4066 30.6214 19.5384 31.0244 17.228 31.0244C14.9176 31.0244 13.0495 30.6214 10.9491 29.672C10.167 29.3185 8.84023 28.5415 8.38771 28.1727L8.18792 28.0088L8.44236 27.5562C9.73161 25.2493 12.6226 23.5092 15.7714 23.1421C16.7209 23.0618 17.6754 23.0618 18.6249 23.1421Z" fill="black" />
                      </svg>
                  </button>
                  <ul class="dropdown-menu rounded-12px mt-3 shadow border-0 dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item waitme" href="<?= site_url('profil') ?>">Profil</a></li>
                    <li><a class="dropdown-item waitme" href="<?= site_url('logout') ?>">Keluar</a></li>
                  </ul>
                </div>
              </li>
            <?php } else { ?>
              <li class="nav-item mx-0 mx-lg-2 ps-3 pe-3 d-flex align-items-center">
                <a class="btn btn-outline-primary btn-sm waitme <?= $this->uri->segment(1) == 'login' ? 'active' : null ?>" aria-current="page" href="<?= site_url('login') ?>">
                  <i class="fa fa-sign-in" aria-hidden="true"></i> Masuk
                </a>
              </li>
            <?php } ?>
          </ul> -->
        </div>
      </div>
    </nav>
  </header>
  <!-- /Header -->

  <!-- Main -->
  <main class="mb-4">
    <?= $contents ?>
  </main>
  <!-- /Main -->

  <!-- Footer -->
  <footer class="fixed-bottom">
    <div class="container-fluid shadow-ea-top bg-white">
      <div class="row">
        <div class="col-md-12 my-3 text-center">
          <span class="fs-5 fw-bold">
            © <?= date('Y') ?> <?= SITE_NAME ?>
          </span>
        </div>
      </div>
    </div>
  </footer>
  <!-- /Footer -->

  <!-- Bootstrap -->
  <script src="<?= base_url() ?>_assets/vendor/bootstrap5/js/bootstrap.bundle.min.js"></script>
  <!-- Owl Carousel -->
  <script src="<?= base_url() ?>_assets/vendor/owlcarousel/js/owl.carousel.min.js"></script>
  <!-- sweetalert JS -->
  <script src="<?= base_url() ?>_assets/vendor/sweetalert/js/sweetalert2.all.min.js"></script>
  <!-- WaitMe -->
  <script src="<?= base_url(); ?>_assets/vendor/waitme/js/waitMe.min.js"></script>
  <script>
    $(document).ready(function() {
      // -------------------------------------- 
      // NAVBAR
      // -------------------------------------- 
      var $nav = $(".navbar-fixed-top");
      // navbar scroll
      $(document).scroll(function() {
        $nav.toggleClass('scrolled', $(this).scrollTop() > 0);
        if ($(this).scrollTop() > 0) {
          $nav.removeClass("navbar-dark").addClass("navbar-light");
        } else {
          $nav.removeClass("navbar-light").addClass("navbar-dark");
        }
      });
      // jika reload di tengah halaman
      if ($(document).scroll().scrollTop() > 0) {
        $nav.removeClass("navbar-dark").addClass("navbar-light");
      } else {
        $nav.removeClass("navbar-light").addClass("navbar-dark");
      }


      $(".navbar-toggler").click(function() {
        if ($(document).scroll().scrollTop() == 0) {
          $nav.toggleClass('scrolled');
          $nav.removeClass("navbar-dark").addClass("navbar-light");
        }
      });
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
          Swal.fire({
            title: "Apakah yakin?",
            text: "Data akan berubah seperti yang sudah didefinisikan!",
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
    })

    $('#banner').owlCarousel({
      loop: true,
      margin: 10,
      nav: false,
      stagePadding: 50,
      dots: false,
      lazyLoad: true,
      autoplay: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 2
        },
        1000: {
          items: 2
        }
      }
    })

    $('#produk').owlCarousel({
      loop: true,
      margin: 10,
      nav: false,
      stagePadding: 50,
      dots: true,
      lazyLoad: true,
      autoplay: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 1
        },
        1000: {
          items: 1
        }
      }
    })

    $('#terkait').owlCarousel({
      loop: false,
      margin: 10,
      nav: false,
      stagePadding: 0,
      dots: false,
      lazyLoad: true,
      autoplay: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 2
        },
        600: {
          items: 4
        },
        1000: {
          items: 5
        }
      }
    })
  </script>
</body>

</html>