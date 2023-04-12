<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Grup extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Grup_model', 'grup');
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
      'title' => 'Grup',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/grup/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->grup->get_datatables(['grupId !=' => 1]);
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
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/grup/{$field->grupId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/grup/{$field->grupId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/grup/{$field->grupId}/hapus") . "'></i></a>";

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/grup/example')) $button .= "<a class='btn btn-sm btn-warning ms-1' href='" . site_url("backoffice/grup/example/{$field->grupId}") . "'>Example</a>";
      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      /**----------------------------------------------------
       * Cek apakah data tersebut merupakan Admin
      -------------------------------------------------------**/
      if ($field->grupId == 1) $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->grupNama;
      $row[] = $field->grupDeskripsi;
      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->grup->count_all(['grupId !=' => 1]),
      "recordsFiltered" => $this->grup->count_filtered(['grupId !=' => 1]),
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
        'field' => 'grupNama',
        'label' => 'Grup',
        'rules' => 'required'
      ],
      [
        'field' => 'grupDeskripsi',
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
        'title' => 'Tambah Grup'
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/grup/create', $data);
    } else {
      $post = $this->input->post(null, true);

      $this->grup->create($post);
      if ($this->db->affected_rows() == 1) {
        activity_log('grup', 'tambah', $post['grupNama']);

        $this->session->set_flashdata('success', 'Berhasil tambah grup!');
        return redirect(site_url('backoffice/grup'));
      }

      activity_log('grup', 'gagal tambah', $post['grupNama']);
      $this->session->set_flashdata('error', 'Gagal tambah grup!');
      return redirect(site_url('backoffice/grup'));
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
        'field' => 'grupNama',
        'label' => 'Grup',
        'rules' => 'required'
      ],
      [
        'field' => 'grupDeskripsi',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $group = $this->grup->get(['grupId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/grup'));
    }

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah Grup',
        'group' => $group->row()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/grup/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);

      $this->grup->update($put, ['grupId' => $group->row()->grupId]);
      if ($this->db->affected_rows() > 0) {
        activity_log('grup', 'ubah', "data {$put['grupNama']}");

        $this->session->set_flashdata('success', 'Berhasil ubah grup');
        return redirect(site_url('backoffice/grup'));
      }

      activity_log('grup', 'gagal ubah', "data {$put['grupNama']}");
      $this->session->set_flashdata('error', 'Gagal ubah grup');
      return redirect(site_url('backoffice/grup'));
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
    $group = $this->grup->get(['grupId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/grup'));
    }

    $this->grup->destroy(['grupId' => $group->row()->grupId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('grup', 'hapus', $group->row()->grupNama);

      $this->session->set_flashdata('success', 'Berhasil hapus grup!');
      return redirect(site_url('backoffice/grup'));
    }

    activity_log('grup', 'gagal hapus', $group->row()->grupNama);
    $this->session->set_flashdata('error', 'Gagal hapus grup!');
    return redirect(site_url('backoffice/grup'));
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
