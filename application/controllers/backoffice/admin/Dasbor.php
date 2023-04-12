<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Dasbor extends CI_Controller
{
  /**----------------------------------------------------
   * Tampilan Dasbor
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $transaksi = $this->db->from("transaksi")->count_all_results();
    $transaksi_today = $this->db->from("transaksi")->where('transaksiTanggal >=', date("Y-m-d 00:00:00"))->where('transaksiTanggal <=', date("Y-m-d 23:59:59"))->count_all_results();
    $product_ready = $this->db->from("produk")->where(['produkTersedia' => 1])->count_all_results();
    $product = $this->db->from("produk")->count_all_results();

    $total_transaksi_today = $this->db->select_sum('transaksiHargaTotal')->from('transaksi')->where('transaksiTanggal >=', date("Y-m-d 00:00:00"))->where('transaksiTanggal <=', date("Y-m-d 23:59:59"))->get()->row()->transaksiHargaTotal;
    $count_transaksi_today = $this->db->select_sum('tdetailQty')->join('transaksi', 'transaksi.transaksiId = transaksi_detail.tdetailTransaksiId')->where('transaksiTanggal >=', date("Y-m-d 00:00:00"))->where('transaksiTanggal <=', date("Y-m-d 23:59:59"))->get("transaksi_detail")->row()->tdetailQty;

    $data = [
      'title' => 'Dasbor',
      'transaksi_today' => $transaksi_today,
      'transaksi_all' => $transaksi,
      'produk_all' => $product,
      'produk_ready' => $product_ready,
      'total_harga' => $total_transaksi_today,
      'total_transaksi' => $count_transaksi_today,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/dasbor/index', $data);
  }
}
