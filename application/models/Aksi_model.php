<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aksi_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();

    $this->load->library('uuid');
  }

  function get($where = null, $order = null)
  {
    $this->db->from('aksi');
    if ($where != null) $this->db->where($where);
    if ($order != null) $this->db->order_by($order);

    return $this->db->get();
  }

  function create($data)
  {
    $data['aksiId'] = $this->uuid->v4();
    $this->db->insert('aksi', $data);
  }

  function update($data, $where)
  {
    $this->db->update('aksi', $data, $where);
  }

  function destroy($where)
  {
    $this->db->delete('aksi', $where);
  }
}
