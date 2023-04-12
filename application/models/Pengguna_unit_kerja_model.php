<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna_unit_kerja_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('uuid');
    }

    function get($where = null, $order = null)
    {
        $this->db->from('pengguna_unit_kerja');
        $this->db->join('unit_kerja', 'unit_kerja.ukId=pengguna_unit_kerja.pengukUkId');
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);

        return $this->db->get();
    }

    function create($data)
    {
        $data['pengukId'] = $this->uuid->v4();
        $this->db->insert('pengguna_unit_kerja', $data);
    }

    function update($data, $where)
    {
        $this->db->update('pengguna_unit_kerja', $data, $where);
    }

    function destroy($where)
    {
        $this->db->delete('pengguna_unit_kerja', $where);
    }
}
