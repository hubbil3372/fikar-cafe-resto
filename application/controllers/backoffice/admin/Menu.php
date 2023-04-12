<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Menu extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Menu_model', 'menu');
  }

  /**----------------------------------------------------
   * Daftar Menu
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $data = [
      'title' => 'Menu Menejemen',
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/menu/index', $data);
  }

  /**----------------------------------------------------
   * Create atau Update Menu 
  -------------------------------------------------------**/
  public function save()
  {
    if ($this->input->post('menuId', true) != '') {
      $menu = $this->menu->get([
        'menuTautan' => $this->input->post('menuTautan', true),
        'menuId !=' => $this->input->post('menuId', true),
      ]);

      if ($menu->num_rows() < 1) {
        $put = [
          'menuLabel' => $this->input->post('menuLabel', true),
          'menuTautan' => $this->input->post('menuTautan', true),
          'menuIkon' => $this->input->post('menuIkon', true),
        ];

        $this->session->set_flashdata('success', 'Berhasil ubah menu!');
        $this->menu->update($put, ['menuId' => $this->input->post('menuId', true)]);
        $data['report']  = 'success edit';
      } else {
        $this->session->set_flashdata('warning', 'Tautan sudah digunakan!');
        $data['report'] = 'failed add';
      }
    } else {
      $menu = $this->menu->get([
        'menuTautan' => $this->input->post('menuTautan', true)
      ]);

      if ($menu->num_rows() < 1) {
        $post = [
          'menuLabel' => $this->input->post('menuLabel', true),
          'menuTautan' => $this->input->post('menuTautan', true),
          'menuIkon' => $this->input->post('menuIkon', true),
        ];

        $this->session->set_flashdata('success', 'Berhasil tambah menu!');
        $this->menu->create($post);
        $data['report'] = 'success add';
      } else {
        $this->session->set_flashdata('warning', 'Tautan sudah digunakan!');
        $data['report'] = 'failed add';
      }
    }

    print json_encode($data);
  }

  /**----------------------------------------------------
   * Ubah urutan menu
  -------------------------------------------------------**/
  public function change_order()
  {
    $data = json_decode($this->input->post('data', true));
    $readbleArray = $this->_parse_json_array($data);

    $i = 0;
    foreach ($readbleArray as $row) {
      $i++;
      $put = [
        'menuInduk' => $row['parentID'],
        'menuUrutan' => $i,
      ];

      $this->session->set_flashdata('success', 'Berhasil mengurutkan menu!');
      $this->menu->update($put, ['menuId' => $row['id']]);
    }
  }

  /**----------------------------------------------------
   * Hapus menu
  -------------------------------------------------------**/
  public function delete()
  {
    $this->session->set_flashdata('success', 'Berhasil hapus menu!');
    $this->menu->destroy(['menuId' => $this->input->post('menuId', true)]);
  }

  /**----------------------------------------------------
   * Utility meruah json menjadi array
  -------------------------------------------------------**/
  public function _parse_json_array($jsonArray, $parentID = 0)
  {

    $return = array();
    foreach ($jsonArray as $subArray) {
      $returnSubSubArray = array();
      if (isset($subArray->children)) {
        $returnSubSubArray = $this->_parse_json_array($subArray->children, $subArray->id);
      }

      $return[] = array('id' => $subArray->id, 'parentID' => $parentID);
      $return = array_merge($return, $returnSubSubArray);
    }
    return $return;
  }
}
