<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>

<head>
  <title><?= SITE_NAME; ?> - Struk</title>
  <style type="text/css">
    html {
      font-family: "Verdana";
    }

    .fs-14 {
      font-size: 14px !important;
    }

    .fw-bold {
      font-weight: bold !important;
    }

    .content {
      width: 60mm;
      font-size: 12px;
      padding: 0px;
    }

    .title {
      text-align: center;
      font-size: 12px;
      padding-bottom: 5px;
      border-bottom: 1px dashed;
    }

    .head {
      margin-top: 5px;
      margin-bottom: 5px;
      padding-bottom: 10px;
      border-bottom: 1px solid;
    }

    table {
      width: 100%;
      font-size: 12;
    }

    .thanks {
      margin-top: 10px;
      padding-top: 10px;
      text-align: center;
      border-top: 1px dashed;
    }

    .text-center {
      text-align: center !important;
    }

    .footer-struk {
      margin-top: 10px;
      padding-top: 10px;
      text-align: center;
    }

    @media print {
      body {
        zoom: 75% !important;
        margin: 0.5rem !important;
      }

      @page {
        width: 100mm !important;
        margin: 0 !important;
      }
    }

    .mt-5 {
      margin-top: 2rem !important;
    }

    .pb-5 {
      padding-bottom: 2rem !important;
    }

    .border-dash {
      border-top: 1px dashed;
    }
  </style>
</head>

<body onload="window.print()">
  <?php for ($i = 0; $i < 2; $i++) { ?>
    <div class="content mt-5">
      <center>
        <img src="<?= base_url("_assets/images/image-login.png") ?>" style="width:30%">
      </center>
      <div class="title">
        <b class="fs-14">FIKAR CAFFE N RESTO</b>
        <br>
        Jln. Raya Cikande-Rangkasbitung KM.13, Bojot, Serang-Banten
        HP/WA:085732506916
      </div>

      <div class="head">
        <table cellspacing="0" cellpadding="0">
          <tr>
            <td style="width: 200px">
              <?= Date("d/m/Y", strtotime($transaksi->transaksiTanggal)) . " " . Date("H:i", strtotime($transaksi->transaksiTanggal)); ?>
            </td>
            <td>Cashier</td>
            <td style="text-align: center; width: 10px;">:</td>
            <td style="text-align: right;">
              <?php $name_cashier = explode(" ", $transaksi->pengNama); ?>
              <?= ucfirst(substr($name_cashier[0], 0, 6)) ?>
            </td>
          </tr>
          <tr>
            <td>
              <?= $transaksi->transaksiFaktur ?>
            </td>
            <td>Customer</td>
            <td style="text-align: center;">:</td>
            <td style="text-align: right;">
              <?php $pembeli = explode(" ", $transaksi->transaksiNamaPembeli); ?>
              <?= $pembeli[0]; ?>
            </td>
          </tr>
        </table>
      </div>

      <div class="tansaction fs-14">
        <table class="tansaction-table" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding-bottom:6px"><b>Item</b></td>
            <td style="padding-bottom:6px"><b>Jml</b></td>
            <td style="text-align: right; padding-bottom:6px"><b>Harga</b></td>
            <td style="text-align: right; padding-bottom:6px"><b>Total</b></td>
          </tr>
          <tr>
            <td colspan="4" style="border-bottom: 1px dashed;"></td>
          </tr>
          <?php
          $arr_discount = array();
          foreach ($transaksi_detail as $key => $s) { ?>
            <tr>
              <td style="width: 145px"><?= $s->tdetailProdukNamaProduk ?></td>
              <td style="text-align:center ;"><?= $s->tdetailQty ?></td>
              <td style="text-align: right;width: 90px;"><?= number_format($s->tdetailProdukHarga, 0, ',', '.') ?></td>
              <td style="text-align: right;width: 90px;">
                <?= number_format(($s->tdetailProdukHarga - $s->tdetailProdukHargaDiskon) * $s->tdetailQty, 0, ',', '.') ?>
              </td>
            </tr>
            <?php
            if ($s->tdetailProdukHargaDiskon > 0) {
              $arr_discount[] = $s->tdetailProdukHargaDiskon;
            }
          }

          foreach ($arr_discount as $discount => $dis) { ?>
            <tr>
              <td></td>
              <td colspan="2" style="text-align:right;">Disc. <?= ($discount + 1) ?></td>
              <td style="text-align: right;"><?= number_format($dis, 0, ',', '.') ?></td>
            </tr>
          <?php
          } ?>

          <tr>
            <td colspan="4" style="border-bottom: 1px dashed; padding-top: 5px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right; padding-top: 5px">Sub Total :</td>
            <td style="text-align: right; padding-top: 5px">
              <?= number_format($transaksi->transaksiHarga, 0, ',', '.') ?>
            </td>
          </tr>
          <?php if ($transaksi->transaksiDiskon > 0) { ?>
            <tr>
              <td colspan="3" style="text-align: right; padding-bottom: 5px">Diskon :</td>
              <td style="text-align: right; padding-bottom: 5px">
                <?= number_format($transaksi->transaksiDiskon, 0, ',', '.') ?>
              </td>
            </tr>
          <?php
          } ?>
          <tr>
            <td colspan="3" style="border-top: 1px dashed; text-align: right; padding: 5px 0">Total Belanja :</td>
            <td style="border-top: 1px dashed; text-align: right; padding: 5px 0">
              <?= number_format($transaksi->transaksiHargaTotal, 0, ',', '.') ?>
            </td>
          </tr>
          <tr>
            <td colspan="3" style="border-top: 1px dashed; text-align: right; padding-top: 5px">Tunai :</td>
            <td style="border-top: 1px dashed; text-align: right; padding-top: 5px">
              <?= number_format($transaksi->transaksiTunai, 0, ',', '.') ?>
            </td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right;">Kembalian :</td>
            <td style="text-align: right;">
              <?= number_format($transaksi->transaksiKembalian, 0, ',', '.') ?>
            </td>
          </tr>
        </table>
      </div>
      <div class="thanks" style="border-bottom:1px dashed;padding:1px"></div>
      <div class="footer-struk">
        TERIMA KASIH ATAS KUNJUNGAN ANDA DI
        <br>
        FIKAR CAFFE N RESTO
        <br>
        <p style="font: size 10px;margin-bottom:0px" class="fw-bold text-uppercase">UNTUK PEMESANAN BISA WA KE NO BERIKUT: <br> 085732506916</p>
      </div>
      <div class="thanks" style="border-bottom:1px dashed;padding:1px"></div>
    </div>
  <?php } ?>
</body>

</html>