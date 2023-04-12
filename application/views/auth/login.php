<div class="col-md-6 d-flex justify-content-center">
  <div class="align-self-center w-100">
    <div class="text-center mb-5 mt-5 d-md-none">
      <img src="<?= base_url() ?>_assets/images/logo.png" class="img-fluid w-25">
      <h4 class="text-uppercase fw-bold h4 font-color-page mt-3"><?= SITE_NAME; ?></h4>
      <p class="font-color-page text-uppercase">Cafe and Resto</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-10 col-sm-8 col-md-10 col-lg-7">
        <h4 class="fw-bold mb-5 text-center">Silahkan Login</h4>
        <?= form_open('', 'class="align-self-center"'); ?>
        <div class="form-group mb-4">
          <div class="input-group">
            <div class="input-group-text bg-light border-0 ps-4 <?= form_error('identity') ? 'invalid-input-l' : null; ?>">
              <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.5" d="M16.4789 14.7769C16.0588 13.7819 15.4493 12.8782 14.6841 12.116C13.9213 11.3515 13.0177 10.7421 12.0232 10.3212C12.0143 10.3168 12.0054 10.3145 11.9964 10.3101C13.3837 9.30804 14.2855 7.67584 14.2855 5.83433C14.2855 2.78369 11.8139 0.312012 8.76322 0.312012C5.71259 0.312012 3.24091 2.78369 3.24091 5.83433C3.24091 7.67584 4.14274 9.30804 5.52999 10.3123C5.52109 10.3168 5.51218 10.319 5.50327 10.3234C4.5057 10.7443 3.61055 11.3477 2.84232 12.1182C2.0779 12.881 1.46843 13.7846 1.04757 14.7791C0.634113 15.7528 0.411126 16.7967 0.390681 17.8543C0.390086 17.878 0.394255 17.9017 0.402941 17.9238C0.411627 17.9459 0.424654 17.9661 0.441255 17.9831C0.457857 18.0001 0.477696 18.0137 0.499604 18.0229C0.521513 18.0321 0.545047 18.0369 0.56882 18.0369H1.90486C2.00284 18.0369 2.08078 17.9589 2.083 17.8632C2.12754 16.1441 2.81783 14.5342 4.03808 13.3139C5.30064 12.0514 6.97738 11.3566 8.76322 11.3566C10.5491 11.3566 12.2258 12.0514 13.4884 13.3139C14.7086 14.5342 15.3989 16.1441 15.4434 17.8632C15.4457 17.9611 15.5236 18.0369 15.6216 18.0369H16.9576C16.9814 18.0369 17.0049 18.0321 17.0268 18.0229C17.0487 18.0137 17.0686 18.0001 17.0852 17.9831C17.1018 17.9661 17.1148 17.9459 17.1235 17.9238C17.1322 17.9017 17.1364 17.878 17.1358 17.8543C17.1135 16.7899 16.893 15.7544 16.4789 14.7769ZM8.76322 9.66432C7.74115 9.66432 6.7792 9.26573 6.05551 8.54204C5.33182 7.81835 4.93323 6.8564 4.93323 5.83433C4.93323 4.81225 5.33182 3.8503 6.05551 3.12661C6.7792 2.40292 7.74115 2.00433 8.76322 2.00433C9.78529 2.00433 10.7472 2.40292 11.4709 3.12661C12.1946 3.8503 12.5932 4.81225 12.5932 5.83433C12.5932 6.8564 12.1946 7.81835 11.4709 8.54204C10.7472 9.26573 9.78529 9.66432 8.76322 9.66432Z" fill="#03014C" />
              </svg>
            </div>
            <input type="email" class="form-control bg-light border-0 form-control-lg <?= form_error('identity') ? 'is-invalid invalid-input-r' : null; ?>" id="identity" name="identity" placeholder="Alamat Email" value="<?= set_value('identity') ?>">
            <div id="identityError" class="invalid-feedback">
              <?= strip_tags(form_error('identity')); ?>
            </div>
          </div>
        </div>
        <div class="form-group mb-4">
          <div class="input-group">
            <div class="input-group-text bg-light border-0 ps-4 <?= form_error('password') ? 'invalid-input-l' : null; ?>">
              <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.5" d="M7.16047 14.4328C8.12516 14.4328 8.91446 13.6435 8.91446 12.6788C8.91446 11.7141 8.12516 10.9248 7.16047 10.9248C6.19578 10.9248 5.40649 11.7141 5.40649 12.6788C5.40649 13.6435 6.19578 14.4328 7.16047 14.4328ZM12.4224 6.53983H11.5454V4.78584C11.5454 2.36534 9.58097 0.400879 7.16047 0.400879C4.73997 0.400879 2.77551 2.36534 2.77551 4.78584H4.4418C4.4418 3.28618 5.66082 2.06717 7.16047 2.06717C8.66013 2.06717 9.87915 3.28618 9.87915 4.78584V6.53983H1.89852C0.933825 6.53983 0.144531 7.32912 0.144531 8.29381V17.0637C0.144531 18.0284 0.933825 18.8177 1.89852 18.8177H12.4224C13.3871 18.8177 14.1764 18.0284 14.1764 17.0637V8.29381C14.1764 7.32912 13.3871 6.53983 12.4224 6.53983ZM12.4224 17.0637H1.89852V8.29381H12.4224V17.0637Z" fill="#03014C" />
              </svg>
            </div>
            <input type="password" class="form-control border-0 bg-light form-control-lg <?= form_error('password') ? 'is-invalid invalid-input-r' : null; ?>" id="password" name="password" placeholder="Password" value="<?= set_value('password') ?>">
            <div id="passwordError" class="invalid-feedback">
              <?= strip_tags(form_error('password')); ?>
            </div>
          </div>
        </div>
        <div class="form-group d-flex mb-3 justify-content-between">
          <div class="form-control border-0 bg-light form-control-lg w-75">
            <?= $captcha['image'] ?>
          </div>
          <div class="text-center justify-content-center d-flex align-items-center justify-content-center form-control border-0 bg-light form-control-lg" style="width: 20%;">
            <a class="text-muted" href="<?= site_url('masuk') ?>"> <i class="fas fa-undo"></i></a>
          </div>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text bg-light border-0 ps-4 <?= form_error('captcha') ? 'invalid-input-l' : null; ?>">
            <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path opacity="0.5" d="M7.16047 14.4328C8.12516 14.4328 8.91446 13.6435 8.91446 12.6788C8.91446 11.7141 8.12516 10.9248 7.16047 10.9248C6.19578 10.9248 5.40649 11.7141 5.40649 12.6788C5.40649 13.6435 6.19578 14.4328 7.16047 14.4328ZM12.4224 6.53983H11.5454V4.78584C11.5454 2.36534 9.58097 0.400879 7.16047 0.400879C4.73997 0.400879 2.77551 2.36534 2.77551 4.78584H4.4418C4.4418 3.28618 5.66082 2.06717 7.16047 2.06717C8.66013 2.06717 9.87915 3.28618 9.87915 4.78584V6.53983H1.89852C0.933825 6.53983 0.144531 7.32912 0.144531 8.29381V17.0637C0.144531 18.0284 0.933825 18.8177 1.89852 18.8177H12.4224C13.3871 18.8177 14.1764 18.0284 14.1764 17.0637V8.29381C14.1764 7.32912 13.3871 6.53983 12.4224 6.53983ZM12.4224 17.0637H1.89852V8.29381H12.4224V17.0637Z" fill="#03014C" />
            </svg>
          </div>
          <input class="form-control border-0 bg-light form-control-lg <?= form_error('captcha') ? 'is-invalid invalid-input-r' : null ?>" type="text" placeholder="Masukan Kode Captcha" name="captcha" id="captcha" value="<?= set_value('captcha') ?>">
          <?= form_error('captcha', '<div class="invalid-feedback">', '</div>') ?>
        </div>
        <div class="col-md-12">
          <div class="d-grid gap-2 mt-5">
            <button type="submit" class="btn btn-login text-white mb-3 waitme">Login</button>
            <a class="text-decoration-none text-muted waitme" href="<?= site_url('lupa-password') ?>">Lupa Password?</a>
          </div>
          <?= form_close() ?>
        </div>
      </div>
      <div class="row mt-5 justify-content-center">
        <div class="col-10 col-sm-8 col-md-10 col-lg-7">
          <!-- <div class="text-center">
            <p class="mb-0 fw-bold">Belum Punya Akun?</p>
            <a href="<?= site_url('daftar'); ?>" class="text-orange fw-bold text-decoration-none">Daftar Disini</a>
          </div> -->
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-12 pb-5 pb-md-0">
          <p class="text-center mb-1 font-color-page fw-bold">&copy; <?= date('Y') ?> <?= COPYRIGHT ?></p>
          <p class="text-center mb-1 font-color-page">Developed by <?= COPYRIGHT ?></p>
        </div>
      </div>
    </div>
  </div>