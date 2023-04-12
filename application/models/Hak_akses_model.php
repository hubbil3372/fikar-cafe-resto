<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hak_akses_model extends CI_Model
{
    public function __construct()
    {
      parent::__construct();
  
      $this->load->library('uuid');
    }

    function get($where = null, $order = null)
    {
        $this->db->from('grup_menu');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order); 

        return $this->db->get();
    }

    function create($data)
    {
        $data['grupMenuId'] = $this->uuid->v4();
        $this->db->insert('grup_menu', $data);
    }
    
    function update($data, $where)
    {
        $this->db->update('grup_menu', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('grup_menu', $where);
    }
}
