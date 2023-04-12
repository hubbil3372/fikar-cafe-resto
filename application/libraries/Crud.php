<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Akses
{
  protected $ci;
  function __construct()
  {
    $this->ci = &get_instance();
  }

  /**----------------------------------------------------
   * Cek apakah HALAMAN pada menu tertentu dengan 
   * grup user yang sedang login ada dalam hak access 
  -------------------------------------------------------**/
  function access_menu($menuId)
  {
    $this->ci->load->model('Menu_model', 'menu');

    $access = $this->ci->menu->get([
      'menuId !=' => '0',
      'grupMenuGrupId' => $this->ci->ion_auth->get_group_id(),
      'grupMenuMenuId' => $menuId,
    ]);

    if ($access->num_rows() < 1) return false;
    return true;
  }

  /**----------------------------------------------------
   * Cek apakah HALAMAN pada menu tertentu dengan 
   * grup user yang sedang login ada dalam hak access 
  -------------------------------------------------------**/
  public function _uploadFile($url, $type, $size, $file_name, $name, $old = null)
  {
    // config image
    $config['upload_path']          = $url;
    $config['allowed_types']        = $type;
    $config['max_size']             = $size;
    $config['file_name']            = $file_name . date('YmdHis') . '_' . rand(1000, 9999);

    $this->ci->load->library('upload');
    $this->upload->initialize($config);

    if ($this->upload->do_upload($name)) {
      if ($old != null) {
        $file_gambar = $old;
        if ($file_gambar != 'default.png') {
          $dir_image = $url . $file_gambar;
          if (file_exists($dir_image)) {
            unlink($dir_image);
          }
        }
      }
      
      return [
        'status' => true,
        'data' => $this->upload->data('file_name')
      ];

    } else {
      $error_file = $this->upload->display_errors();

      return [
        'status' => false,
        'data' => strip_tags($error_file) . $name .  ' ' . $type
      ];
    }
  }
}
