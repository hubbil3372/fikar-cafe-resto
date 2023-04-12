<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class HakAkses extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Hak_akses_model', 'hak_akses');
    $this->load->model('Aksi_model', 'aksi');
    $this->load->model('Grup_model', 'grup');
    $this->load->model('Menu_model', 'menu');
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
      'title' => 'Hak Akses',
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/hak-akses/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->grup->get_datatables();

    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $field) {
      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->grupNama;
      $row[] = $field->grupDeskripsi;
      $row[] = "<div class='text-center'>
                  <a class='btn btn-sm btn-info text-white waitme' href='" . site_url("backoffice/hak-akses/{$field->grupId}/grup") . "'><i class='fas fa-eye'></i></a>
                </div>";
      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->grup->count_all(),
      "recordsFiltered" => $this->grup->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Detail Hak Akses Grup
  -------------------------------------------------------**/
  public function show($id)
  {
    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $group = $this->grup->get(['grupId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/grup'));
    }

    $menu_data = [];
    $all_menus = $this->menu->get_menu(null, 'menuUrutan ASC')->result();
    foreach ($all_menus as $key => $all_menu) {
      $group_menu = $this->menu->get(['grupMenuGrupId' => $id,'grupMenuMenuId' => $all_menu->menuId ], 'menuUrutan ASC');
      $actions = $this->aksi->get(['aksiMenuId' => $all_menu->menuId])->result();
      
      if($group_menu->num_rows() > 0) {
        $temp = $group_menu->row();
        $temp->actions = $actions;
        $menu_data[] = $temp; 
      } else {
        $all_menu->actions = $actions;
        $menu_data[] = $all_menu;
      }
    }

    $users = $this->ion_auth->users($group->row()->grupId)->result();

    $data = [
      'title' => "Hak Akses {$group->row()->grupNama}",
      'menus' => $menu_data,
      'group' => $group->row(),
      'users' => $users,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/hak-akses/show', $data);
  }

  /**----------------------------------------------------
   * Ubah Hak Akses Grup
  -------------------------------------------------------**/
  public function change_access()
  {
    $data = [
      'grupMenuGrupId' => $this->input->post('grupMenuGrupId'),
      'grupMenuMenuId' => $this->input->post('grupMenuMenuId'),
    ];

    $type = $this->input->post('type');
    $access = $this->input->post('access');

    $result = $this->hak_akses->get(
      [
        'grupMenuGrupId' => $data['grupMenuGrupId'],
        'grupMenuMenuId' => $data['grupMenuMenuId']
      ]
    );

    if ($type != null) {
      $this->hak_akses->update([$type => $access], $data);
    } else {
      if ($result->num_rows() < 1) {
        $this->hak_akses->create($data);
      } else {
        $this->hak_akses->destroy($data);
      }
    }
  }
}
