<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class LoadTime extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('grup_model', 'grup');
  }

  /**----------------------------------------------------
   * Daftar Load Time
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $data = [
      'title' => 'Load Time',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/load-time/index', $data);
  }

  /**----------------------------------------------------
   * grup
  -------------------------------------------------------**/
  public function get_grup()
  {
    $orang = $this->input->post('grup', true);

    $this->benchmark->mark('code_start');
    /**----------------------------------------------------
     * Isi kode testing
    -------------------------------------------------------**/
    for ($i = 0; $i < $orang; $i++) {
      $this->grup->get();
    }
    $this->benchmark->mark('my_mark_end');

    $this->session->set_userdata([
      'elapsed_time' => $this->benchmark->elapsed_time(),
      'memory_usage' => $this->benchmark->memory_usage(),
      'grup' => $orang,
    ]);

    return redirect(site_url('backoffice/load-time'));
  }

  /**----------------------------------------------------
   * grup
  -------------------------------------------------------**/
  public function get_grup_by()
  {
    $orang = $this->input->post('grup', true);

    $this->benchmark->mark('code_start');
    /**----------------------------------------------------
     * Isi kode testing
    -------------------------------------------------------**/
    for ($i = 0; $i < $orang; $i++) {
      $this->grup->get(['grupId' => 1]);
    }
    $this->benchmark->mark('my_mark_end');

    $this->session->set_userdata([
      'elapsed_time' => $this->benchmark->elapsed_time(),
      'memory_usage' => $this->benchmark->memory_usage(),
      'grup_by' => $orang,
    ]);

    return redirect(site_url('backoffice/load-time'));
  }

  /**----------------------------------------------------
   * grup
  -------------------------------------------------------**/
  public function create_grup()
  {
    $orang = $this->input->post('grup', true);

    $this->benchmark->mark('code_start');
    /**----------------------------------------------------
     * Isi kode testing
    -------------------------------------------------------**/
    for ($i = 0; $i < $orang; $i++) {
      $this->grup->create(
        [
          'grupNama' => 'Test',
          'grupDeskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Earum expedita reprehenderit quibusdam quo quis illum deserunt accusantium dolore rerum, veniam iure reiciendis aperiam minus asperiores repellendus rem maxime explicabo itaque ab at quisquam dolor vero animi? Animi inventore ipsam repellat.',
        ]
      );

      $this->grup->destroy(
        [
          'grupId' => $this->db->insert_id(),
        ]
      );
    }
    $this->benchmark->mark('my_mark_end');

    $this->session->set_userdata([
      'elapsed_time' => $this->benchmark->elapsed_time(),
      'memory_usage' => $this->benchmark->memory_usage(),
      'create_grup' => $orang,
    ]);

    return redirect(site_url('backoffice/load-time'));
  }
}
