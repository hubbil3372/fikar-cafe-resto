<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Page extends CI_Controller
{
  /**----------------------------------------------------
   * 404
  -------------------------------------------------------**/
  public function not_found_404()
  {
    $this->load->view('not_found_404');
  }
}
