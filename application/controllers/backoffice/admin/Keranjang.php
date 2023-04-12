<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Keranjang extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Keranjang_model', 'keranjang');
    $this->load->model('Produk_model', 'produk');

    $this->pengguna = $this->ion_auth->user()->row();
  }
  /**----------------------------------------------------
   * Tambah Keranjang
  -------------------------------------------------------**/
  public function create($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    // if (!$this->akses->access_rights_aksi('backoffice/pesanan/keranjang')) redirect('404_override', 'refresh');

    $produk = $this->produk->get(['produkId' => $id]);
    if ($produk->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Produk Tidak Ditemukan!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }

    if ($produk->row()->produkTersedia == 0) {
      $this->session->set_flashdata('warning', 'Menu Sudah habis untuk hari ini!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }

    $save = [
      'keranjangProdukId' => $produk->row()->produkId,
      'keranjangKasirId' => $this->pengguna->pengId,
      'keranjangQty' => 1,
    ];

    $this->keranjang->create($save);
    if ($this->db->affected_rows() > 0) {
      activity_log('Keranjang', 'Tambah', "data {$produk->row()->produkNama}");
      $this->session->set_flashdata('success', 'Berhasil Tambah Keranjang!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }
    activity_log('Keranjang', 'gagal Tambah', "data {$produk->row()->produkNama}");
    $this->session->set_flashdata('error', 'Gagal Tambah Keranjang!');
    return redirect(site_url('backoffice/pesanan/tambah'));
  }


  /**----------------------------------------------------
   * Tambah Keranjang
  -------------------------------------------------------**/
  public function update()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    // if (!$this->akses->access_rights_aksi('backoffice/pesanan/keranjang')) redirect('404_override', 'refresh');
    $config_form = [
      [
        'field' => 'keranjangId',
        'label' => 'keranjangId',
        'rules' => 'required'
      ],
      [
        'field' => 'keranjangCatatanPembeli',
        'label' => 'keranjangCatatanPembeli',
        'rules' => 'required'
      ],
      [
        'field' => 'keranjangQty',
        'label' => 'keranjangQty',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      if (form_error("keranjangId")) {
        $this->session->set_flashdata('warning', strip_tags(form_error("keranjangId")));
        return redirect(site_url('backoffice/pesanan/tambah'));
      }
    }
    $post = $this->input->post(null, TRUE);
    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $keranjang = $this->keranjang->get(['keranjangId' => $post['keranjangId']]);
    if ($keranjang->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Produk Tidak Ditemukan!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }
    unset($post['keranjangId']);
    $this->keranjang->update($post, ['keranjangId' => $keranjang->row()->keranjangId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('Keranjang', 'Ubah', "data {$keranjang->row()->produkNama}");

      $this->session->set_flashdata('success', 'Berhasil Ubah Keranjang!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }
    activity_log('Keranjang', 'gagal Ubah', "data {$keranjang->row()->produkNama}");
    $this->session->set_flashdata('error', 'Gagal Ubah Keranjang!');
    return redirect(site_url('backoffice/pesanan/tambah'));
  }

  public function data()
  {
    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'keranjangId',
        'label' => 'keranjangId',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      if (form_error("keranjangId")) {
        $response['status'] = false;
        $response['message'] = strip_tags(form_error("keranjangId"));
        $response['data'] = '';
        echo json_encode($response);
        return;
      }
    }
    $keranjang = $this->keranjang->get(['keranjangId' => $this->input->post("keranjangId")]);
    if ($keranjang->num_rows() > 0) {
      $response['status'] = true;
      $response['message'] = 'data tersedia';
      $response['data'] = $keranjang->row();
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
   * Hapus Grup
  -------------------------------------------------------**/
  public function destroy($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    // $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    // if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di hapus ada dalam database
    -------------------------------------------------------**/
    $keranjang = $this->keranjang->get(['keranjangId' => $id]);
    if ($keranjang->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }

    $this->keranjang->destroy(['keranjangId' => $keranjang->row()->keranjangId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('Hapus Keranjang', 'hapus', $keranjang->row()->produkNama);

      $this->session->set_flashdata('success', 'Berhasil hapus Hapus Keranjang!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }

    activity_log('Hapus Keranjang', 'gagal hapus', $keranjang->row()->produkNama);
    $this->session->set_flashdata('error', 'Gagal hapus Hapus Keranjang!');
    return redirect(site_url('backoffice/pesanan/tambah'));
  }

  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
  public function example($id)
  {
    echo $id;
  }
  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
}
