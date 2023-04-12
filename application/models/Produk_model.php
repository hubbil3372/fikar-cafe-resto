<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_model extends CI_Model
{
  /* field yang ada di table user */
  var $column_order = [
    'produkNama',
    'produkNamaSingkat',
    'produkKeterangan',
    'produkHarga',
    'produkHargaGrosir',
    'produkHargaDiskon'
  ];
  /* field yang diizin untuk pencarian */
  var $column_search = [
    'produkNama',
    'produkNamaSingkat',
    'produkKode',
    'produkKeterangan',
    'produkHarga',
    'produkHargaGrosir',
    'produkHargaDiskon',
  ];
  var $order = ['produkDibuatPada' => 'DESC']; // default order 

  private function _get_datatables_query($where = null)
  {
    $this->db->from('produk');
    $this->db->join('produk_kategori', 'produk_kategori.pkId = produk.produkKategoriId', 'left');
    if ($where != null) $this->db->where($where);

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
    $this->db->from('produk');
    if ($where != null) $this->db->where($where);
    return $this->db->count_all_results();
  }

  function get($where = null, $order = null, $count = false, $like = null)
  {
    $this->db->from('produk');
    $this->db->join('produk_kategori', 'produk_kategori.pkId = produk.produkKategoriId', 'left');
    if ($where != null) $this->db->where($where);
    if ($order != null) $this->db->order_by($order);
    if ($like != null) $this->db->like("produkNama", $like);
    if ($count) return $this->db->count_all_results();
    return $this->db->get();
  }

  function create($data)
  {
    if (!isset($data['produkId'])) $data['produkId'] = $this->uuid->v4();
    $this->db->insert('produk', $data);
  }

  function update($data, $where)
  {
    $this->db->update('produk', $data, $where);
  }

  function destroy($where)
  {
    $this->db->delete('produk', $where);
  }
}
