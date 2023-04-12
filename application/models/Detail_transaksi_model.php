<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_transaksi_detail_model extends CI_Model
{

  function get($where = null, $order = null, $count = false)
  {
    $this->db->from('transaksi_detail');
    $this->db->join('pengguna', 'pengguna.pengId = transaksi_detail.transaksi_detailKasirId', 'left');
    if ($where != null) $this->db->where($where);
    if ($order != null) $this->db->order_by($order);
    if ($count) return $this->db->count_all_results();
    return $this->db->get();
  }

  function create($data)
  {
    if (!isset($data['transaksi_detailId'])) $data['transaksi_detailId'] = $this->uuid->v4();
    $this->db->insert('transaksi_detail', $data);
    return $data['transaksi_detailId'];
  }

  function update($data, $where)
  {
    $this->db->update('transaksi_detail', $data, $where);
  }

  function destroy($where)
  {
    $this->db->delete('transaksi_detail', $where);
  }
}
