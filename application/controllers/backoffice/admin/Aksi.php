<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Aksi extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');
    $this->load->model('Aksi_model', 'aksi');
    $this->load->model('Aksi_grup_model', 'aksi_grup');
    $this->load->model('Grup_model', 'grup');
    $this->load->model('Menu_model', 'menu');
  }

  /**----------------------------------------------------
   * Tambah UnitKerja
  -------------------------------------------------------**/
  public function create($grupId, $menuId)
  {
    /**----------------------------------------------------
     * Cek apakah admin
    -------------------------------------------------------**/
    if (!$this->ion_auth->is_admin()) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data grup ada dalam database
    -------------------------------------------------------**/
    $group = $this->grup->get(['grupId' => $grupId]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/hak-akses'));
    }

    /**----------------------------------------------------
     * Cek apakah data menu ada dalam database
    -------------------------------------------------------**/
    $menu = $this->menu->get(['menuId' => $menuId]);
    if ($menu->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'aksiLabel',
        'label' => 'Label',
        'rules' => 'required'
      ],
      [
        'field' => 'aksiTautan',
        'label' => 'Tautan',
        'rules' => 'required|is_unique[menu.menuTautan]|is_unique[aksi.aksiTautan]',
        'errors' => [
          'is_unique' => 'Tautan sudah digunakan!',
        ]
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == false) {
      $data = [
        'title' => 'Tambah Unit Kerja',
        'menu' => $menu->row()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/aksi/create', $data);
    } else {
      $post = $this->input->post(null, true);
      $post['aksiMenuId'] = $menuId;

      $this->aksi->create($post);
      if ($this->db->affected_rows() == 1) {
        $this->session->set_flashdata('success', 'Berhasil tambah unit kerja!');
        return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
      }

      $this->session->set_flashdata('error', 'Gagal tambah unit kerja!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }
  }

  /**----------------------------------------------------
   * Ubah Unit Kerja
  -------------------------------------------------------**/
  public function update($grupId, $menuId, $aksiId)
  {
    /**----------------------------------------------------
     * Cek apakah admin
    -------------------------------------------------------**/
    if (!$this->ion_auth->is_admin()) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data grup ada dalam database
    -------------------------------------------------------**/
    $group = $this->grup->get(['grupId' => $grupId]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/hak-akses'));
    }

    /**----------------------------------------------------
     * Cek apakah data menu ada dalam database
    -------------------------------------------------------**/
    $menu = $this->menu->get(['menuId' => $menuId]);
    if ($menu->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    /**----------------------------------------------------
     * Cek apakah data unit kerja ada dalam database
    -------------------------------------------------------**/
    $unit = $this->aksi->get(['aksiId' => $aksiId]);
    if ($unit->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    if ($this->input->post('aksiTautan') != $unit->row()->aksiTautan) {
      $is_unique =  '|is_unique[aksi.aksiTautan]';
    } else {
      $is_unique =  '';
    }

    $config_form = [
      [
        'field' => 'aksiLabel',
        'label' => 'Label',
        'rules' => 'required'
      ],
      [
        'field' => 'aksiTautan',
        'label' => 'Tautan',
        'rules' => "required|is_unique[menu.menuTautan]{$is_unique}",
        'errors' => [
          'is_unique' => 'Tautan sudah digunakan!',
        ]
      ],
    ];

    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah Unit Kerja',
        'unit' => $unit->row(),
        'menu' => $menu->row()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/aksi/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);

      $this->aksi->update($put, ['aksiId' => $unit->row()->aksiId]);
      if ($this->db->affected_rows() == 1) {
        $this->session->set_flashdata('success', 'Berhasil ubah unit kerja!');
        return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
      }

      $this->session->set_flashdata('error', 'Gagal ubah unit kerja!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }
  }

  /**----------------------------------------------------
   * Hapus Unit Kerja
  -------------------------------------------------------**/
  public function destroy($grupId, $aksiId)
  {
    /**----------------------------------------------------
     * Cek apakah admin
    -------------------------------------------------------**/
    if (!$this->ion_auth->is_admin()) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data unit kerja ada dalam database
    -------------------------------------------------------**/
    $unit = $this->aksi->get(['aksiId' => $aksiId]);
    if ($unit->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    $this->aksi->destroy(['aksiId' => $unit->row()->aksiId]);
    if ($this->db->affected_rows() == 1) {
      $this->session->set_flashdata('success', 'Berhasil hapus unit kerja!');
      return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
    }

    $this->session->set_flashdata('error', 'Gagal hapus unit kerja!');
    return redirect(site_url("backoffice/hak-akses/{$grupId}/grup"));
  }

  /**----------------------------------------------------
   * Ubah Hak Akses Grup
  -------------------------------------------------------**/
  public function change_access()
  {
    $data = [
      'agAksiId' => $this->input->post('agAksiId'),
      'agGrupId' => $this->input->post('agGrupId'),
    ];

    $result = $this->aksi_grup->get($data);

    if ($result->num_rows() < 1) {
      $this->aksi_grup->create($data);
    } else {
      $this->aksi_grup->destroy($data);
    }
  }
}
