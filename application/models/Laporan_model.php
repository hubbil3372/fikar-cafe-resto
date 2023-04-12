<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
  /* field yang ada di table user */
  var $column_order = [
    'transaksiFaktur',
    'transaksiHarga',
    'transaksiDiskon',
    'transaksiStatus',
    'transaksiPembayaran',
    'transaksiNamaPembeli',
    'transaksiHargaTotal',
    'transaksiTanggal',
  ];
  /* field yang diizin untuk pencarian */
  var $column_search = [
    'transaksiFaktur',
    'transaksiHarga',
    'transaksiDiskon',
    'transaksiStatus',
    'transaksiPembayaran',
    'transaksiNamaPembeli',
    'transaksiHargaTotal',
  ];
  var $order = ['transaksiTanggal' => 'DESC']; // default order 

  private function _get_datatables_query($where = null, $from = null, $to = null)
  {
    $this->db->from('transaksi');
    $this->db->join('pengguna', 'pengguna.pengId = transaksi.transaksiKasirId', 'left');
    if ($where != null) $this->db->where($where);
    // if ($from == null && $to == null) {
    //   $this->db->where('transaksiId', '112');
    // }
    $this->db->where('transaksiTanggal >=', date($from . ' 00:00:00'));
    $this->db->where('transaksiTanggal <=', date($to . ' 23:59:59'));

    $i = 0;
    foreach ($this->column_search as $item) // looping awal
    {
      if (@$_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
      {
        // looping awal
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, strtolower($_POST['search']['value']));
        } else {
          $this->db->or_like($item, strtolower($_POST['search']['value']));
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }
    if (isset($_POST['order'])) {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables($where = null, $from = null, $to = null)
  {
    $this->_get_datatables_query($where, $from, $to);
    if (@$_POST['length'] != -1)
      $this->db->limit(@$_POST['length'], @$_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered($where = null, $from = null, $to = null)
  {
    $this->_get_datatables_query($where, $from, $to);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($where = null, $from = null, $to = null)
  {
    $this->db->from('transaksi');
    $this->db->join('pengguna', 'pengguna.pengId = transaksi.transaksiKasirId', 'left');
    if ($where != null) $this->db->where($where);
    $this->db->where('transaksiTanggal >=', date($from . ' 00:00:00'));
    $this->db->where('transaksiTanggal <=', date($to . ' 23:59:59'));
    return $this->db->count_all_results();
  }

  function get($where = null, $order = null, $count = false)
  {
    $this->db->from('transaksi');
    $this->db->join('pengguna', 'pengguna.pengId = transaksi.transaksiKasirId', 'left');
    if ($where != null) $this->db->where($where);
    if ($order != null) $this->db->order_by($order);
    if ($count) return $this->db->count_all_results();
    return $this->db->get();
  }

  function create($data)
  {
    if (!isset($data['transaksiId'])) $data['transaksiId'] = $this->uuid->v4();
    $this->db->insert('transaksi', $data);
    return $data['transaksiId'];
  }

  function update($data, $where)
  {
    $this->db->update('transaksi', $data, $where);
  }

  function destroy($where)
  {
    $this->db->delete('transaksi', $where);
  }
}
