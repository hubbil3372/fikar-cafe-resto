<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Transaksi extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Transaksi_model', 'transaksi');
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Keranjang_model', 'keranjang');
    $this->load->model('Pesanan_model', 'pesanan');
  }

  /**----------------------------------------------------
   * Daftar Grup
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $data = [
      'title' => 'Transaksi',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/transaksi/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->transaksi->get_datatables();
    /**----------------------------------------------------
     * Ambil id menu untuk cek akses Update dan Destroy
    -------------------------------------------------------**/
    $menu_id = $this->menus->get_menu_id("backoffice/{$this->input->get('tautan')}");

    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $field) {
      /**----------------------------------------------------
       * Cek apakah role yang sedang login dapat melakukan Update dan Destroy
      -------------------------------------------------------**/
      $button = '';
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/transaksi/{$field->transaksiId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/transaksi/{$field->transaksiId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/transaksi/{$field->transaksiId}/hapus") . "'></i></a>";

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/transaksi/detail')) $button .= "<a class='btn btn-sm btn-warning ms-1' href='" . site_url("backoffice/transaksi/detail/{$field->transaksiId}") . "'><i class='fas fa-eye waitme'></i></a>";
      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";;
      $row[] = $field->transaksiFaktur;
      $row[] = $field->transaksiNamaPembeli;
      $row[] = $field->pengNama;
      $row[] = $this->_get_details('transaksi_detail', ['tdetailTransaksiId' => $field->transaksiId], 'tdetailProdukNamaProduk');
      $row[] = $field->transaksiStatus == 1 ? "<span class=\"badge bg-success\">success</span>" : "<span class=\"badge bg-warning\">Gagal</span>";
      $row[] = rupiah($field->transaksiHarga);
      $row[] = rupiah($field->transaksiDiskon);
      $row[] = rupiah($field->transaksiHargaTotal);
      $row[] = Date("d/m/Y H:i", strtotime($field->transaksiTanggal));


      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->transaksi->count_all(),
      "recordsFiltered" => $this->transaksi->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }


  function _get_details($tables, $where, $field)
  {
    $array = "";
    $datas = $this->db->from($tables)->where($where)->get();
    if ($datas->num_rows() == 0) {
      return false;
    }
    foreach ($datas->result() as $key => $value) {
      $values = (array)$value;
      $array .= $values[$field] . ", ";
    }
    return $array;
  }


  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json_produk($transaksi_id = null)
  {
    $list = $this->pesanan->get_datatables();

    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $field) {
      /**----------------------------------------------------
       * Cek apakah role yang sedang login dapat melakukan Update dan Destroy
      -------------------------------------------------------**/
      $button = '';
      /**----------------------------------------------------
       * aksi untuk mengambil produk
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/transaksi/tambah-item')) $button .= "<a href='" . site_url("backoffice/transaksi/tambah-item/{$field->produkId}/{$transaksi_id}") . "' class='btn btn-sm btn-secondary select-items ms-1'>Pilih</a>";
      /**----------------------------------------------------
       * aksi untuk cek produk sudah diambil
      -------------------------------------------------------**/
      $where_transaksi_detail = ['tdetailProdukId' => $field->produkId];
      if ($transaksi_id != null) $where_transaksi_detail['tdetailTransaksiId'] = $transaksi_id;
      $keranjang = $this->db->get_where('transaksi_detail', $where_transaksi_detail);
      if ($keranjang->num_rows() > 0) $button = '<i class="fas fa-check-circle text-success"></i>';

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->produkNama;
      $row[] = $field->pkNama;
      $row[] = rupiah($field->produkHarga);
      $row[] = $field->produkTersedia == 1 ? "<span class=\"badge bg-primary\">Tersedia</span>" : "<span class=\"badge bg-warning\">Tidak Tersedia</span>";

      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->pesanan->count_all(),
      "recordsFiltered" => $this->pesanan->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Detail Transaksi
  -------------------------------------------------------**/
  public function show($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    if (!$this->akses->access_rights_aksi('backoffice/transaksi/detail')) redirect('404_override', 'refresh');

    $transaksi = $this->transaksi->get(['transaksiId' => $id]);
    if ($transaksi->num_rows() < 1) {
      $this->session->set_flashdata('error', 'Data tidak ditemukan!');
      return redirect(site_url("backoffice/transaksi"));
    }

    $detrans = $this->db->get_where('transaksi_detail', ['tdetailTransaksiId' => $transaksi->row()->transaksiId]);

    $data = [
      'title' => 'Detail Transaksi',
      'transaksi' => $transaksi->row(),
      'transaksi_detail' => $detrans->result(),
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/transaksi/show', $data);
  }

  /**----------------------------------------------------
   * Ubah Grup
  -------------------------------------------------------**/
  public function update($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'transaksiNamaPembeli',
        'label' => 'Nama Pembeli',
        'rules' => 'required'
      ],
      [
        'field' => 'transaksiStatus',
        'label' => 'Status Transaksi',
        'rules' => 'required'
      ],
      [
        'field' => 'transaksiDiskon',
        'label' => 'Diskon',
        'rules' => 'required'
      ],
      [
        'field' => 'transaksiTunai',
        'label' => 'Tunai',
        'rules' => 'required'
      ]
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $transaksi = $this->transaksi->get(['transaksiId' => $id]);
    if ($transaksi->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/transaksi'));
    }

    $detail_transaksi = $this->db->get_where('transaksi_detail', ['tdetailTransaksiId' => $transaksi->row()->transaksiId]);

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/

    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah transaksi',
        'transaksi' => $transaksi->row(),
        'detail_transaksi' => $detail_transaksi->result()
      ];
      $this->template->load('template/dasbor', 'backoffice/admin/transaksi/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);
      unset($put['transaksiFaktur']);
      if ($put['transaksiCatatan'] == null) unset($put['transaksiCatatan']);
      $put['transaksiHarga'] = filter_var($put['transaksiHarga'], FILTER_SANITIZE_NUMBER_INT);
      $put['transaksiDiskon'] = filter_var($put['transaksiDiskon'], FILTER_SANITIZE_NUMBER_INT);
      $put['transaksiHargaTotal'] = filter_var($put['transaksiHargaTotal'], FILTER_SANITIZE_NUMBER_INT);
      $put['transaksiTunai'] = filter_var($put['transaksiTunai'], FILTER_SANITIZE_NUMBER_INT);
      $put['transaksiKembalian'] = filter_var($put['transaksiKembalian'], FILTER_SANITIZE_NUMBER_INT);
      // return print_r($put);
      if ($put['transaksiTunai'] < $put['transaksiHargaTotal']) {
        $this->session->set_flashdata('warning', 'Pembayaran tidak cukup!');
        return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
      }

      $this->transaksi->update($put, ['transaksiId' => $transaksi->row()->transaksiId]);
      if ($this->db->affected_rows() > 0) {
        activity_log('Transaksi', 'ubah', "data {$put['transaksiNamaPembeli']}");

        $this->session->set_flashdata('success', 'Berhasil ubah Transaksi!');
        return redirect(site_url('backoffice/transaksi'));
      }

      activity_log('Transaksi', 'gagal ubah', "data {$put['transaksiNamaPembeli']}");
      $this->session->set_flashdata('error', 'Gagal ubah Transaksi!');
      return redirect(site_url('backoffice/transaksi'));
    }
  }

  /**----------------------------------------------------
   * Hapus Transaksi
  -------------------------------------------------------**/
  public function destroy($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di hapus ada dalam database
    -------------------------------------------------------**/

    $transaksi = $this->transaksi->get(['transaksiId' => $id]);
    if ($transaksi->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/transaksi'));
    }
    $this->transaksi->destroy(['transaksiId' => $transaksi->row()->transaksiId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('Transaksi', 'hapus', $transaksi->row()->transaksiFaktur);

      $this->session->set_flashdata('success', 'Berhasil hapus Transaksi!');
      return redirect(site_url('backoffice/transaksi'));
    }

    activity_log('Transaksi', 'gagal hapus', $transaksi->row()->transaksiFaktur);
    $this->session->set_flashdata('error', 'Gagal hapus Transaksi!');
    return redirect(site_url('backoffice/transaksi'));
  }


  /**----------------------------------------------------
   * Hapus Items Detail
  -------------------------------------------------------**/
  public function destroy_details($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di hapus ada dalam database
    -------------------------------------------------------**/
    $details = $this->db->get_where('transaksi_detail', ['tdetailId' => $id]);
    if ($details->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/transaksi'));
    }

    $transaksi = $this->transaksi->get(['transaksiId' => $details->row()->tdetailTransaksiId]);

    $this->db->delete('transaksi_detail', ['tdetailId' => $details->row()->tdetailId]);
    if ($this->db->affected_rows() > 0) {
      $hargatotal  = $this->_counter(['tdetailTransaksiId' => $transaksi->row()->transaksiId]);
      $subtotal =  $hargatotal - $transaksi->row()->transaksiDiskon;
      $this->transaksi->update(['transaksiHarga' => $hargatotal, 'transaksiHargaTotal' => $subtotal], ['transaksiId' => $transaksi->row()->transaksiId]);
      activity_log('Hapus Items Pembelian', 'hapus', $details->row()->tdetailProdukNamaProduk);
      // $this->session->set_flashdata('success', 'Berhasil hapus Transaksi!');
      return redirect(site_url("backoffice/transaksi/{$details->row()->tdetailTransaksiId}/ubah"));
    }

    activity_log('Hapus Items Pembelian', 'gagal hapus', $details->row()->tdetailProdukNamaProduk);
    $this->session->set_flashdata('error', 'Gagal hapus Transaksi!');
    return redirect(site_url("backoffice/transaksi/{$details->row()->tdetailTransaksiId}/ubah"));
  }


  /**----------------------------------------------------
   * Tambah Items Detail
  -------------------------------------------------------**/
  public function create_details($id, $transaksi_id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    if (!$this->akses->access_rights_aksi('backoffice/transaksi/tambah-item')) redirect('404_override', 'refresh');

    $transaksi = $this->transaksi->get(['transaksiId' => $transaksi_id]);
    if ($transaksi->num_rows() == 0) {
      $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
      return redirect(site_url("backoffice/transaksi"));
    }

    $produk = $this->produk->get(['produkId' => $id]);
    if ($produk->num_rows() == 0) {
      $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
      return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
    }
    $produk = $produk->row();
    if ($produk->produkTersedia == 0) {
      $this->session->set_flashdata('error', 'Produk Habis untuk hari ini!');
      return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
    }

    $t_details = [
      "tdetailId" => $this->uuid->v4(),
      "tdetailTransaksiId" => $transaksi->row()->transaksiId,
      "tdetailProdukId" => $produk->produkId,
      "tdetailQty" => 1,
      "tdetailCatatanPembeli" => "",
      "tdetailProdukNamaProduk" => $produk->produkNama,
      "tdetailProdukNamaSingkat" => $produk->produkNamaSingkat,
      "tdetailProdukKategori" => $produk->pkNama,
      "tdetailProdukKode" => $produk->produkKode,
      "tdetailProdukKeterangan" => $produk->produkKeterangan,
      "tdetailProdukHarga" => $produk->produkHarga,
      "tdetailProdukHargaGrosir" => $produk->produkHargaGrosir,
      "tdetailProdukHargaDiskon" => $produk->produkHargaDiskon,
      "tdetailProdukGambar" => $produk->produkGambar,
    ];

    // return print_r($subtotal);

    /**----------------------------------------------------
     * Insert ke database
     -------------------------------------------------------**/
    $this->db->insert("transaksi_detail", $t_details);
    if ($this->db->affected_rows() > 0) {
      $hargatotal  = $this->_counter(['tdetailTransaksiId' => $transaksi->row()->transaksiId]);
      $subtotal =  $hargatotal - $transaksi->row()->transaksiDiskon;
      $this->transaksi->update(['transaksiHarga' => $hargatotal, 'transaksiHargaTotal' => $subtotal], ['transaksiId' => $transaksi->row()->transaksiId]);
      activity_log('Tambah Items Pembelian', 'tambah', $produk->produkNama);
      $this->session->set_flashdata('success', 'Berhasil tambah Item!');
      return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
    }

    activity_log('Tambah Items Pembelian', 'gagal tambah', $produk->produkNama);
    $this->session->set_flashdata('error', 'Gagal tambah Item!');
    return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
  }

  /**----------------------------------------------------
   * tampilkan Items Detail
  -------------------------------------------------------**/
  public function show_details($id)
  {

    $detail = $this->db->get_where('transaksi_detail', ['tdetailId' => $id]);
    if ($detail->num_rows() == 0) {
      $this->session->set_flashdata('error', 'Detail transaksi tidak ditemukan!');
      return redirect(site_url("backoffice/transaksi"));
    }

    if ($detail->num_rows() > 0) {
      $response['status'] = true;
      $response['message'] = 'data tersedia';
      $response['data'] = $detail->row();
      echo json_encode($response);
      return;
    }
    $response['status'] = false;
    $response['message'] = 'data tidak tersedia';
    $response['data'] = '';
    echo json_encode($response);
    return;
  }


  /**----------------------------------------------------
   * ubah Items Detail
  -------------------------------------------------------**/
  public function update_details()
  {
    $config_form = [
      [
        'field' => 'tdetailId',
        'label' => 'tdetailId',
        'rules' => 'required'
      ],
      [
        'field' => 'tdetailCatatanPembeli',
        'label' => 'tdetailCatatanPembeli',
        'rules' => 'required'
      ],
      [
        'field' => 'tdetailQty',
        'label' => 'tdetailQty',
        'rules' => 'required'
      ],
      [
        'field' => 'tdetailTransaksiId',
        'label' => 'tdetailTransaksiId',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    $post = $this->input->post(null, TRUE);
    $transaksi = $this->transaksi->get(['transaksiId' => $post['tdetailTransaksiId']]);
    if ($transaksi->num_rows() == 0) {
      $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
      return redirect(site_url("backoffice/transaksi"));
    }

    if ($this->form_validation->run() == FALSE) {
      if (form_error("tdetailId")) {
        $this->session->set_flashdata('warning', strip_tags(form_error("tdetailId")));
        return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
      }

      if (form_error("tdetailQty")) {
        $this->session->set_flashdata('warning', strip_tags(form_error("tdetailQty")));
        return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
      }
    }
    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $detail_transaksi = $this->db->get_where('transaksi_detail', ['tdetailId' => $post['tdetailId']]);
    if ($detail_transaksi->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Detail Transaksi Tidak Ditemukan!');
      return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
    }
    unset($post['tdetailId']);
    unset($post['tdetailTransaksiId']);

    if ($post['tdetailQty'] == 0) $post['tdetailQty'] = 1;

    $this->db->update('transaksi_detail', $post, ['tdetailId' => $detail_transaksi->row()->tdetailId]);
    if ($this->db->affected_rows() > 0) {
      $hargatotal  = $this->_counter(['tdetailTransaksiId' => $transaksi->row()->transaksiId]);
      $subtotal =  $hargatotal - $transaksi->row()->transaksiDiskon;
      $this->transaksi->update(['transaksiHarga' => $hargatotal, 'transaksiHargaTotal' => $subtotal], ['transaksiId' => $transaksi->row()->transaksiId]);
      activity_log('Ubah items pembelian', 'Ubah', "data {$detail_transaksi->row()->tdetailProdukNamaProduk}");

      $this->session->set_flashdata('success', 'Berhasil Ubah items pembelian!');
      return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
    }
    activity_log('Ubah items pembelian', 'gagal Ubah', "data {$detail_transaksi->row()->tdetailProdukNamaProduk}");
    $this->session->set_flashdata('error', 'Gagal Ubah items pembelian!');
    return redirect(site_url("backoffice/transaksi/{$transaksi->row()->transaksiId}/ubah"));
  }

  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
  public function _generate_kode($kode)
  {
    $generate = $kode . date('YmdHi') . rand(1000, 9999);
    $check = $this->transaksi->get(['produkKode' => $generate]);
    if ($check->num_rows() > 0) {
      return $this->_generate_kode($kode);
    }
    return $generate;
  }


  public function _counter($where)
  {
    $counter = $this->db->get_where('transaksi_detail', $where);
    if ($counter->num_rows() < 1) return false;
    $subtotal = 0;
    foreach ($counter->result() as $counts) {
      $price = ($counts->tdetailProdukHarga - $counts->tdetailProdukHargaDiskon) * $counts->tdetailQty;
      $subtotal = $subtotal + $price;
    }
    return $subtotal;
  }



  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
}
