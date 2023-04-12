<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Kategori_produk extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Kategori_produk_model', 'kategori_produk');
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
      'title' => 'Kategori Produk',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/kategori-produk/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->kategori_produk->get_datatables();
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
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/kategori-produk/{$field->pkId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/kategori-produk/{$field->pkId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/kategori-produk/{$field->pkId}/hapus") . "'></i></a>";

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/kategori-produk/example')) $button .= "<a class='btn btn-sm btn-warning ms-1' href='" . site_url("backoffice/kategori-produk/example/{$field->pkId}") . "'>Example</a>";
      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->pkNama;
      $row[] = $field->pkKeterangan;
      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->kategori_produk->count_all(),
      "recordsFiltered" => $this->kategori_produk->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Tambah Grup
  -------------------------------------------------------**/
  public function create()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuTambah')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'pkNama',
        'label' => 'Grup',
        'rules' => 'required'
      ],
      [
        'field' => 'pkKeterangan',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == false) {
      $data = [
        'title' => 'Tambah Kategori Produk'
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/kategori-produk/create', $data);
    } else {
      $post = $this->input->post(null, true);

      $this->kategori_produk->create($post);
      if ($this->db->affected_rows() == 1) {
        activity_log('Kategori Produk', 'tambah', $post['pkNama']);

        $this->session->set_flashdata('success', 'Berhasil tambah Kategori Produk!');
        return redirect(site_url('backoffice/kategori-produk'));
      }

      activity_log('Kategori Produk', 'gagal tambah', $post['pkNama']);
      $this->session->set_flashdata('error', 'Gagal tambah Kategori Produk!');
      return redirect(site_url('backoffice/kategori-produk'));
    }
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
        'field' => 'pkNama',
        'label' => 'Grup',
        'rules' => 'required'
      ],
      [
        'field' => 'pkKeterangan',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $group = $this->kategori_produk->get(['pkId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/kategori-produk'));
    }

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah Grup',
        'group' => $group->row()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/kategori-produk/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);

      $this->kategori_produk->update($put, ['pkId' => $group->row()->pkId]);
      if ($this->db->affected_rows() > 0) {
        activity_log('Kategori Produk', 'ubah', "data {$put['pkNama']}");

        $this->session->set_flashdata('success', 'Berhasil ubah grup');
        return redirect(site_url('backoffice/kategori-produk'));
      }

      activity_log('Kategori Produk', 'gagal ubah', "data {$put['pkNama']}");
      $this->session->set_flashdata('error', 'Gagal ubah grup');
      return redirect(site_url('backoffice/kategori-produk'));
    }
  }

  /**----------------------------------------------------
   * Hapus Grup
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
    $group = $this->kategori_produk->get(['pkId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/kategori-produk'));
    }

    $this->kategori_produk->destroy(['pkId' => $group->row()->pkId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('Kategori Produk', 'hapus', $group->row()->pkNama);

      $this->session->set_flashdata('success', 'Berhasil hapus Kategori Produk!');
      return redirect(site_url('backoffice/kategori-produk'));
    }

    activity_log('Kategori Produk', 'gagal hapus', $group->row()->pkNama);
    $this->session->set_flashdata('error', 'Gagal hapus Kategori Produk!');
    return redirect(site_url('backoffice/kategori-produk'));
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
