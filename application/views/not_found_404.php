<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstraps 5 -->
  <link href="<?= base_url() ?>_assets/vendor/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
  <title>404</title>

  <style>
    /*======================
        404 page
    =======================*/
    .page_404 {
      padding: 40px 0;
      background: #fff;
      font-family: 'Arvo', serif;
    }

    .page_404 img {
      width: 100%;
    }

    .four_zero_four_bg {

      background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
      height: 400px;
      background-position: center;
    }


    .four_zero_four_bg h1 {
      font-size: 80px;
    }

    .four_zero_four_bg h3 {
      font-size: 80px;
    }

    .link_404 {
      color: #fff !important;
      padding: 10px 20px;
      background: #39ac31;
      margin: 20px 0;
      display: inline-block;
    }

    .contant_box_404 {
      margin-top: -50px;
    }
  </style>
</head>

<body>
  <section class="page_404">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 d-flex justify-content-center align-items-center min-vh-100">
          <div class="col-sm-10 col-sm-offset-2 text-center">
            <div class="four_zero_four_bg">
              <h1 class="text-center ">404</h1>
            </div>
            <div class="contant_box_404">
              <h3 class="h2">
                Look like you're lost
              </h3>
              <p>the page you are looking for not avaible!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Bootstrap -->
  <script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>