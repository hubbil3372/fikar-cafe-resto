<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function __construct()
    {
      parent::__construct();
  
      $this->load->library('uuid');
    }

    function get($where = null, $order = null)
    {
        $this->db->from('menu');
        $this->db->join('grup_menu', 'grup_menu.grupMenuMenuId = menu.menuId', 'left');
        $this->db->join('grup', 'grup.grupId = grup_menu.grupMenuGrupId', 'left');
        $this->db->where('menuId !=', '0');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order); 

        return $this->db->get();
    }

    function get_menu($where = null, $order = null)
    {
        $this->db->from('menu');
        $this->db->where('menuId !=', '0');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order); 

        return $this->db->get();
    }

    function create($data)
    {
        $data['menuId'] = $this->uuid->v4();
        $this->db->insert('menu', $data);
    }
    
    function update($data, $where)
    {
        $this->db->update('menu', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('menu', $where);
    }
}
