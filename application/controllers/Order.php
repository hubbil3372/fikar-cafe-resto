<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Order extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }


  public function index()
  {
    $data = [
      'title' => 'Pesan Sekarang',
    ];

    $this->template->load('template/frontend', 'frontend/order/index', $data);
  }
}
