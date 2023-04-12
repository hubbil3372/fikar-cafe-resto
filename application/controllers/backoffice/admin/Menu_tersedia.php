<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Menu_tersedia extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');
    $this->load->model('Produk_model', 'produk');
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
      'title' => 'Menu Tersedia',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/menu-tersedia/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->produk->get_datatables();
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
      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/
      if ($field->produkTersedia == 1) if ($this->akses->access_rights_aksi('backoffice/menu-tersedia/update-menu')) $button .= "<a class='btn btn-sm btn-info waitme ms-1' href='" . site_url("backoffice/menu-tersedia/update-menu/{$field->produkId}/0") . "'><i class=\"waitme fas fa-toggle-on\"></i></a>";
      if ($field->produkTersedia == 0) if ($this->akses->access_rights_aksi('backoffice/menu-tersedia/update-menu')) $button .= "<a class='btn btn-sm btn-info waitme ms-1' href='" . site_url("backoffice/menu-tersedia/update-menu/{$field->produkId}/1") . "'><i class=\"waitme fas fa-toggle-off\"></i></a>";
      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->produkNama;
      $row[] = $field->pkNama;
      $row[] = $field->produkStatus == 1 ? "<span class=\"badge bg-success\">Aktif</span>" : "<span class=\"badge bg-danger\">Tidak Aktif</span>";
      $row[] = $field->produkTersedia == 1 ? "<span class=\"badge bg-primary\">Tersedia</span>" : "<span class=\"badge bg-warning\">Tidak Tersedia</span>";

      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->produk->count_all(),
      "recordsFiltered" => $this->produk->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Tambah Grup
  -------------------------------------------------------**/
  public function update_available($id, $params)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    if (!$this->akses->access_rights_aksi('backoffice/menu-tersedia/update-menu')) redirect('404_override', 'refresh');

    $produk = $this->produk->get(['produkId' => $id]);
    if ($produk->num_rows() == 0) {
      $this->session->set_flashdata('error', 'Data tidak ditemukan!');
      return redirect(site_url('backoffice/menu-tersedia'));
    }

    if (!in_array($params, [0, 1])) {
      $this->session->set_flashdata('warning', 'Url tidak diperkenankan!');
      return redirect(site_url('backoffice/menu-tersedia'));
    }

    $this->produk->update(['produkTersedia' => $params], ['produkId' => $produk->row()->produkId]);
    if ($this->db->affected_rows() == 1) {
      activity_log('Ketersediaan Menu', 'tambah', 'ketersediaan diperbarui');

      $this->session->set_flashdata('success', 'Berhasil ubah Ketersediaan Menu!');
      return redirect(site_url('backoffice/menu-tersedia'));
    }

    activity_log('Ketersediaan Menu', 'gagal tambah', 'ketersediaan gagal diperbarui');
    $this->session->set_flashdata('error', 'Gagal ubah Ketersediaan Menu!');
    return redirect(site_url('backoffice/menu-tersedia'));
  }
}
