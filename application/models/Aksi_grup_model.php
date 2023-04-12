<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aksi_grup_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();

    $this->load->library('uuid');
  }

  function get($where = null, $order = null)
  {
    $this->db->from('aksi_grup');
    if ($where != null) $this->db->where($where);
    if ($order != null) $this->db->order_by($order);

    return $this->db->get();
  }

  function create($data)
  {
    $data['agId'] = $this->uuid->v4();
    $this->db->insert('aksi_grup', $data);
  }

  function update($data, $where)
  {
    $this->db->update('aksi_grup', $data, $where);
  }

  function destroy($where)
  {
    $this->db->delete('aksi_grup', $where);
  }
}
