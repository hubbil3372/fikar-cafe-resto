<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Beranda extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
  }


  public function index()
  {
    $data = [
      'title' => 'Beranda',
      'produk_banner' => $this->db->limit(5, 0)->where(['produkStatus' => 1, 'produkTersedia' => 1])->order_by('produkDibuatPada', 'ASC')->get('produk')->result(),
      'produk' => $this->db->limit(12, 0)->where(['produkStatus' => 1])->order_by('produkDibuatPada', 'ASC')->get('produk')->result(),
    ];

    $this->template->load('template/frontend', 'frontend/beranda/index', $data);
  }
}
