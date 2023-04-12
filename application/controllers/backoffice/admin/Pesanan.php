<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Pesanan extends CI_Controller
{
  private $pengguna;
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Transaksi_model', 'transaksi');
    $this->load->model('Pesanan_model', 'pesanan');
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Keranjang_model', 'keranjang');

    $this->pengguna = $this->ion_auth->user()->row();
  }

  /**----------------------------------------------------
   * Daftar Grup
  -------------------------------------------------------**/
  public function index()
  {
    // return redirect(site_url('backoffice/pesanan/tambah'));
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');
    $like = null;
    $where = ["produkStatus" => 1];
    $cari = $this->input->get('q');
    if ($this->input->get('kategori')) $where['pkId'] = $this->input->get('kategori');
    if ($this->input->get('q')) $like = $cari;
    $produk = $this->pesanan->get_pagination($where, $like, null, ['pkNama' => 'ASC']);

    $data = [
      'title' => 'Daftar Menu',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
      'produk' => $produk->result(),
      'kategori' => $this->db->get("produk_kategori")->result(),
      'keranjang' => $this->keranjang->get(['keranjangKasirId' => $this->pengguna->pengId])->result(),
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/pesanan/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json_produk()
  {
    $list = $this->pesanan->get_datatables();

    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $field) {
      /**----------------------------------------------------
       * Cek apakah role yang sedang login dapat melakukan Update dan Destroy
      -------------------------------------------------------**/
      $button = '';
      /**----------------------------------------------------
       * aksi untuk mengambil produk
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/pesanan/keranjang')) $button .= "<a href='" . site_url("backoffice/keranjang/tambah/{$field->produkId}") . "' class='btn btn-sm btn-secondary select-items ms-1'>Pilih</a>";
      /**----------------------------------------------------
       * aksi untuk cek produk sudah diambil
      -------------------------------------------------------**/
      $keranjang = $this->keranjang->get(['keranjangKasirId' => $this->pengguna->pengId, 'keranjangProdukId' => $field->produkId]);
      if ($keranjang->num_rows() > 0) $button = '<i class="fas fa-check-circle text-success"></i>';

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->produkNama;
      $row[] = $field->pkNama;
      $row[] = rupiah($field->produkHarga);
      $row[] = $field->produkTersedia == 1 ? "<span class=\"badge bg-primary\">Tersedia</span>" : "<span class=\"badge bg-warning\">Tidak Tersedia</span>";

      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->pesanan->count_all(),
      "recordsFiltered" => $this->pesanan->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Tambah Pesanan
  -------------------------------------------------------**/
  public function create()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuTambah')) redirect('404_override', 'refresh');

    $keranjang = $this->keranjang->get(['keranjangKasirId' => $this->pengguna->pengId]);
    $counting = 0;
    foreach ($keranjang->result() as $keranjangs) {
      $price = $keranjangs->produkHarga - $keranjangs->produkHargaDiskon;
      $items = $price * $keranjangs->keranjangQty;
      $counting = $counting + $items;
    }
    $data = [
      'title' => 'Buat Pesanan',
      'produk' => $keranjang->result(),
      'kasir' =>  $this->ion_auth->user()->row(),
      'faktur' => $this->pesanan->invoice_no(),
      'counting' => rupiah($counting),
    ];
    $this->template->load('template/dasbor', 'backoffice/admin/pesanan/create', $data);
  }

  public function process()
  {
    $post = $this->input->post(null, TRUE);
    $post['transaksiHarga'] = filter_var($post['transaksiHarga'], FILTER_SANITIZE_NUMBER_INT);
    $post['transaksiHargaTotal'] = filter_var($post['transaksiHargaTotal'], FILTER_SANITIZE_NUMBER_INT);
    $post['transaksiKembalian'] = filter_var($post['transaksiKembalian'], FILTER_SANITIZE_NUMBER_INT);
    $post['transaksiTunai'] = filter_var($post['transaksiTunai'], FILTER_SANITIZE_NUMBER_INT);
    $post['transaksiDiskon'] = filter_var($post['transaksiDiskon'], FILTER_SANITIZE_NUMBER_INT);
    $post['transaksiStatus'] = 1;
    $post['transaksiPembayaran'] = 'TUNAI';

    $transaksi_id = $this->transaksi->create($post);
    $keranjang = $this->keranjang->get(['keranjangKasirId' => $this->pengguna->pengId]);
    if ($keranjang->num_rows() < 1) {
      $this->transaksi->destroy(['transaksiId' => $transaksi_id]);
      $response = [
        'status' => false,
        'message' => 'Transaksi Gagal Diproses keranjang kosong!',
      ];
      echo json_encode($response);
      return;
    }

    $t_detail = [];
    foreach ($keranjang->result() as $keranjangs) {
      $t_detail[] = [
        "tdetailId" => $this->uuid->v4(),
        "tdetailTransaksiId" => $transaksi_id,
        "tdetailProdukId" => $keranjangs->produkId,
        "tdetailQty" => $keranjangs->keranjangQty,
        "tdetailCatatanPembeli" => $keranjangs->keranjangCatatanPembeli,
        "tdetailProdukNamaProduk" => $keranjangs->produkNama,
        "tdetailProdukNamaSingkat" => $keranjangs->produkNamaSingkat,
        "tdetailProdukKategori" => $keranjangs->pkNama,
        "tdetailProdukKode" => $keranjangs->produkKode,
        "tdetailProdukKeterangan" => $keranjangs->produkKeterangan,
        "tdetailProdukHarga" => $keranjangs->produkHarga,
        "tdetailProdukHargaGrosir" => $keranjangs->produkHargaGrosir,
        "tdetailProdukHargaDiskon" => $keranjangs->produkHargaDiskon,
        "tdetailProdukGambar" => $keranjangs->produkGambar,
      ];
    }

    $this->db->insert_batch('transaksi_detail', $t_detail);
    if ($this->db->affected_rows() > 0) {
      $this->keranjang->destroy(['keranjangKasirId' => $this->pengguna->pengId]);
      activity_log('Pesanan', 'Buat Pesanan', 'pesanan berhasil dibuat');
      $response = [
        'status' => true,
        'message' => 'Transaksi Berhasil Diproses!',
        'transaksiId' => $transaksi_id
      ];
      echo json_encode($response);
      return;
    }

    activity_log('Pesanan', 'Buat Pesanan', 'pesanan Gagal dibuat');
    $response = [
      'status' => false,
      'message' => 'Transaksi Gagal Diproses',
      'data' => $keranjang->result()
    ];
    echo json_encode($response);
    return;
  }

  public function tambah_keranjang($id)
  {
    $produk = $this->produk->get(['produkId' => $id]);
    if ($produk->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Produk Tidak Ditemukan!');
      return redirect(site_url('backoffice/pesanan'));
    }

    if ($produk->row()->produkTersedia == 0) {
      $this->session->set_flashdata('warning', 'Menu Sudah habis untuk hari ini!');
      return redirect(site_url('backoffice/pesanan'));
    }

    $check_keranjang = $this->keranjang->get(['keranjangKasirId' => $this->pengguna->pengId, 'keranjangProdukId' => $produk->row()->produkId]);
    if ($check_keranjang->num_rows() > 0) {
      $update = ['keranjangQty' => ($check_keranjang->row()->keranjangQty + 1)];
      $this->keranjang->update($update, ['keranjangId' => $check_keranjang->row()->keranjangId]);
      if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', 'Berhasil Tambah Keranjang!');
        return redirect(site_url('backoffice/pesanan'));
      }
      // $this->session->set_flashdata('error', 'Gagal Tambah Keranjang! update');
      // return redirect(site_url('backoffice/pesanan'));
      return print_r($update);
    }

    $save = [
      'keranjangProdukId' => $produk->row()->produkId,
      'keranjangKasirId' => $this->pengguna->pengId,
      'keranjangQty' => 1,
    ];

    $this->keranjang->create($save);
    if ($this->db->affected_rows() > 0) {
      activity_log('Keranjang', 'Tambah', "data {$produk->row()->produkNama}");
      $this->session->set_flashdata('success', 'Berhasil Tambah Keranjang!');
      return redirect(site_url('backoffice/pesanan'));
    }
    activity_log('Keranjang', 'gagal Tambah', "data {$produk->row()->produkNama}");
    $this->session->set_flashdata('error', 'Gagal Tambah Keranjang!');
    return redirect(site_url('backoffice/pesanan'));
  }

  public function hapus_keranjang($id)
  {
    $keranjang = $this->keranjang->get(['keranjangId' => $id]);
    if ($keranjang->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Keranjang Tidak Ditemukan!');
      return redirect(site_url('backoffice/pesanan'));
    }

    $this->keranjang->destroy(['keranjangId' => $keranjang->row()->keranjangId]);
    if ($this->db->affected_rows() > 0) {
      $this->session->set_flashdata('success', 'Berhasil Hapus Keranjang!');
      return redirect(site_url('backoffice/pesanan'));
    }
    $this->session->set_flashdata('error', 'Gagal Hapus Keranjang!');
    return redirect(site_url('backoffice/pesanan'));
  }

  /**----------------------------------------------------
   * Hapus Pesanan
  -------------------------------------------------------**/
  public function destroy($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    // $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    // if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di hapus ada dalam database
    -------------------------------------------------------**/
    $keranjang = $this->keranjang->get(['keranjangKasirId' => $id]);
    if ($keranjang->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }

    $this->keranjang->destroy(['keranjangKasirId' => $id]);
    if ($this->db->affected_rows() > 0) {
      activity_log('Transaksi', 'hapus', 'Transaksi dibatalkan');

      $this->session->set_flashdata('success', 'Transaksi Dibatalkan!');
      return redirect(site_url('backoffice/pesanan/tambah'));
    }

    activity_log('Transaksi', 'gagal hapus', $keranjang->row()->keranjangNama);
    $this->session->set_flashdata('error', 'Gagal Batalkan Transaksi!');
    return redirect(site_url('backoffice/pesanan/tambah'));
  }

  public function struk($id)
  {
    $transaksi = $this->transaksi->get(['transaksiId' => $id]);
    if ($transaksi->num_rows() < 1) {
      $this->session->set_flashdata('error', 'Data tidak ditemukan!');
      return redirect(site_url('backoffice/pesanan'));
    }

    $detail = $this->db->get_where("transaksi_detail", ['tdetailTransaksiId' => $transaksi->row()->transaksiId]);
    $data = [
      'transaksi' => $transaksi->row(),
      'transaksi_detail' => $detail->result()
    ];

    $this->load->view('backoffice/admin/pesanan/struk-belanja', $data);
  }

  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
  public function check_files()
  {
    $key = key($_FILES);
    if ($_FILES[$key]['name'] == "") {
      $this->form_validation->set_message('check_files', '{field} belum dipilih!');
      return false;
    }
    return true;
  }


  public function _generate_kode($kode)
  {
    $generate = $kode . date('Ymd') . rand(1000, 9999);
    $check = $this->transaksi->get(['transaksiFaktur' => $generate]);
    if ($check->num_rows() > 0) {
      return $this->_generate_kode($kode);
    }
    return $generate;
  }


  public function _uploadFile($url, $type, $size, $file_name, $name, $old = null, $link = null)
  {
    // config image
    $config['upload_path']          = $url;
    $config['allowed_types']        = $type;
    $config['max_size']             = $size;
    $config['file_name']            = $file_name . date('YmdHis') . '_' . rand(1000, 9999);

    $this->load->library('upload');
    $this->upload->initialize($config);

    if ($this->upload->do_upload($name)) {
      if ($old != null) {
        $file_gambar = $old;
        if ($file_gambar != 'default.png') {
          $dir_image = $url . $file_gambar;
          if (file_exists($dir_image)) {
            unlink($dir_image);
          }
        }
      }
      return $this->upload->data('file_name');
    } else {
      $error_file = $this->upload->display_errors();
      $this->session->set_flashdata('error', strip_tags($error_file) . $name .  ' ' . $type);
      if ($link != null) return redirect(site_url($link));
      return redirect($_SERVER['HTTP_REFERER']);
    }
  }

  public function coba()
  {
    $invoice = $this->pesanan->invoice_no();
    return print_r($invoice);
  }


  public function _paginate($base_url, $count_rows, $perpage)
  {
    $this->load->library('pagination');

    $get = $this->input->get(null, true);
    unset($get['per_page']);
    $uri = http_build_query($get);

    $config['base_url'] = base_url($base_url . $uri);
    $config['total_rows'] = $count_rows;
    $config['per_page'] = $perpage;
    $config['page_query_string'] = TRUE;

    $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';

    $config['first_link'] = 'Awal';
    $config['first_tag_open'] = '<li class="page-item mx-1">';
    $config['first_tag_close'] = '</li>';
    $config['first_url'] = '';

    $config['last_link'] = 'Akhir';
    $config['last_tag_open'] = '<li class="page-item mx-1">';
    $config['last_tag_close'] = '</li>';

    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li class="page-item mx-1">';
    $config['next_tag_close'] = '</li>';

    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li class="page-item mx-1">';
    $config['prev_tag_close'] = '</li>';

    $config['num_tag_open'] = '<li class="page-item mx-1">';
    $config['num_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="page-item mx-1 active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';

    $config['attributes'] = array('class' => 'page-link waitme');

    $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

    $this->pagination->initialize($config);

    $return = new stdClass();
    $return->offset = $start;
    $return->generate_link = $this->pagination->create_links();

    return $return;
  }
  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
}
