<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model
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
    'pengNama',
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
    'pengNama',
  ];
  var $order = ['transaksiTanggal' => 'DESC']; // default order 

  private function _get_datatables_query($where = null)
  {
    $this->db->from('transaksi');
    $this->db->join('pengguna', 'pengguna.pengId = transaksi.transaksiKasirId', 'left');
    if ($where != null) $this->db->where($where);

    $i = 0;
    foreach ($this->column_search as $item) // looping awal
    {
      if (@$_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
      {
        // looping awal
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like('LOWER("' . $item . '")', strtolower($_POST['search']['value']));
        } else {
          $this->db->or_like('LOWER("' . $item . '")', strtolower($_POST['search']['value']));
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

  function get_datatables($where = null)
  {
    $this->_get_datatables_query($where);
    if (@$_POST['length'] != -1)
      $this->db->limit(@$_POST['length'], @$_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered($where = null)
  {
    $this->_get_datatables_query($where);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($where = null)
  {
    $this->db->from('transaksi');
    if ($where != null) $this->db->where($where);
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
