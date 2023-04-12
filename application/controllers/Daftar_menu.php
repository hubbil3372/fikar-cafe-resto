<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Daftar_menu extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
  }


  public function index($kategori = null)
  {

    $where = ['produkStatus' => 1];
    $like = null;
    if ($kategori != null) $where['pkId'] = $kategori;
    if ($this->input->get('cari')) $like = $this->input->get('cari');
    $data = [
      'title' => 'Daftar Menu',
      'produk' => $this->produk->get($where, null, false, $like)->result(),
      'kategori' => $this->db->get_where("produk_kategori")->result()
    ];

    // print_r($data['produk']);
    // die;

    $this->template->load('template/frontend', 'frontend/daftar-menu/index', $data);
  }
}
