<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keranjang_model extends CI_Model
{
  public function get($where = null, $like = null, $limit = null, $order = null, $count = false)
  {
    $this->db->from('keranjang');
    $this->db->join('produk', 'produk.produkId = keranjang.keranjangProdukId');
    $this->db->join('produk_kategori', 'produk_kategori.pkId = produk.produkKategoriId', 'left');
    if ($where != null) $this->db->where($where);
    if ($like != null) $this->db->like(key($like), $like[key($like)]);
    if ($limit != null) $this->db->limit(key($limit), $limit[key($limit)]);
    if ($order != null) $this->db->order_by(key($order), $order[key($order)]);
    if ($count) return $this->db->count_all_results();
    return $this->db->get();
  }

  function create($data)
  {
    if (!isset($data['keranjangId'])) $data['keranjangId'] = $this->uuid->v4();
    $this->db->insert('keranjang', $data);
  }

  function update($data, $where)
  {
    $this->db->update('keranjang', $data, $where);
  }

  function destroy($where)
  {
    $this->db->delete('keranjang', $where);
  }
}
