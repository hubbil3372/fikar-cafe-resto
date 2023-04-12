<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Produk extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Kategori_produk_model', 'kategori_produk');
    $this->load->model('Produk_model', 'produk');
  }

  /**----------------------------------------------------
   * Daftar Grup
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $data = [
      'title' => 'Produk',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/produk/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->produk->get_datatables();
    /**----------------------------------------------------
     * Ambil id menu untuk cek akses Update dan Destroy
    -------------------------------------------------------**/
    $menu_id = $this->menus->get_menu_id("backoffice/{$this->input->get('tautan')}");

    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $field) {
      /**----------------------------------------------------
       * Cek apakah role yang sedang login dapat melakukan Update dan Destroy
      -------------------------------------------------------**/
      $button = '';
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/produk/{$field->produkId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/produk/{$field->produkId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/produk/{$field->produkId}/hapus") . "'></i></a>";

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/produk/example')) $button .= "<a class='btn btn-sm btn-warning ms-1' href='" . site_url("backoffice/produk/example/{$field->produkId}") . "'>Example</a>";
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
      $row[] = $field->produkStatus == 1 ? "<span class=\"badge bg-success\">Aktif</span>" : "<span class=\"badge bg-danger\">Tidak Aktif</span>";
      $row[] = $field->produkTersedia == 1 ? "<span class=\"badge bg-primary\">Tersedia</span>" : "<span class=\"badge bg-warning\">Tidak Tersedia</span>";

      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->produk->count_all(),
      "recordsFiltered" => $this->produk->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Tambah Grup
  -------------------------------------------------------**/
  public function create()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuTambah')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'produkNama',
        'label' => 'Grup',
        'rules' => 'required'
      ],
      [
        'field' => 'produkKeterangan',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
      [
        'field' => 'produkHarga',
        'label' => 'Harga',
        'rules' => 'required'
      ],
      [
        'field' => 'produkHargaGrosir',
        'label' => 'Harga Grosir',
        'rules' => 'required'
      ],
      [
        'field' => 'produkStatus',
        'label' => 'Status Produk',
        'rules' => 'required'
      ],
      [
        'field' => 'produkKategoriId',
        'label' => 'Kategori',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    $kategori_produk = $this->kategori_produk->get();
    if ($this->form_validation->run() == false) {
      $data = [
        'title' => 'Tambah Produk',
        'kategori' => $kategori_produk->result()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/produk/create', $data);
    } else {
      $post = $this->input->post(null, true);
      $post['produkHarga'] = filter_var($post['produkHarga'], FILTER_SANITIZE_NUMBER_INT);
      $post['produkHargaGrosir'] = filter_var($post['produkHargaGrosir'], FILTER_SANITIZE_NUMBER_INT);
      $post['produkHargaDiskon'] = filter_var($post['produkHargaDiskon'], FILTER_SANITIZE_NUMBER_INT);
      $post['produkKode'] = $this->_generate_kode('SLP');
      $post['produkTersedia'] = 0;
      $post['produkGambar'] = 'default.png';
      if ($_FILES['produkGambar']['name'] != "") {
        $post['produkGambar'] = $this->_uploadFile("_uploads/produk/", "jpg|jpeg|png", 2040, "PRODUK_", "produkGambar", null, "backoffice/produk");
      }
      // return print_r($post);

      $this->produk->create($post);
      if ($this->db->affected_rows() == 1) {
        activity_log('Produk', 'tambah', $post['produkNama']);

        $this->session->set_flashdata('success', 'Berhasil tambah Produk!');
        return redirect(site_url('backoffice/produk'));
      }

      activity_log('Produk', 'gagal tambah', $post['produkNama']);
      $this->session->set_flashdata('error', 'Gagal tambah Produk!');
      return redirect(site_url('backoffice/produk'));
    }
  }

  /**----------------------------------------------------
   * Ubah Grup
  -------------------------------------------------------**/
  public function update($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'produkNama',
        'label' => 'Grup',
        'rules' => 'required'
      ],
      [
        'field' => 'produkKeterangan',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
      [
        'field' => 'produkHarga',
        'label' => 'Harga',
        'rules' => 'required'
      ],
      [
        'field' => 'produkHargaGrosir',
        'label' => 'Harga Grosir',
        'rules' => 'required'
      ],
      [
        'field' => 'produkHargaDiskon',
        'label' => 'Harga Diskon',
        'rules' => 'required'
      ],
      [
        'field' => 'produkStatus',
        'label' => 'Status Produk',
        'rules' => 'required'
      ],
      [
        'field' => 'produkKategoriId',
        'label' => 'Kategori',
        'rules' => 'required'
      ],
      [
        'field' => 'produkTersedia',
        'label' => 'Ketersediaan',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $produk = $this->produk->get(['produkId' => $id]);
    if ($produk->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/produk'));
    }

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    $kategori_produk = $this->kategori_produk->get();
    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah Produk',
        'produk' => $produk->row(),
        'kategori' => $kategori_produk->result(),
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/produk/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);
      $put['produkHarga'] = filter_var($put['produkHarga'], FILTER_SANITIZE_NUMBER_INT);
      $put['produkHargaGrosir'] = filter_var($put['produkHargaGrosir'], FILTER_SANITIZE_NUMBER_INT);
      $put['produkHargaDiskon'] = filter_var($put['produkHargaDiskon'], FILTER_SANITIZE_NUMBER_INT);

      if ($_FILES['produkGambar']['name'] != "") {
        $put['produkGambar'] = $this->_uploadFile("_uploads/produk/", "jpg|jpeg|png", 2040, "PRODUK_", "produkGambar", $produk->row()->produkGambar, "backoffice/produk");
      }

      $this->produk->update($put, ['produkId' => $produk->row()->produkId]);
      if ($this->db->affected_rows() > 0) {
        activity_log('Produk', 'ubah', "data {$put['produkNama']}");

        $this->session->set_flashdata('success', 'Berhasil ubah Produk!');
        return redirect(site_url('backoffice/produk'));
      }

      activity_log('Produk', 'gagal ubah', "data {$put['produkNama']}");
      $this->session->set_flashdata('error', 'Gagal ubah Produk!');
      return redirect(site_url('backoffice/produk'));
    }
  }

  /**----------------------------------------------------
   * Hapus Grup
  -------------------------------------------------------**/
  public function destroy($id)
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di hapus ada dalam database
    -------------------------------------------------------**/
    $produk = $this->produk->get(['produkId' => $id]);
    if ($produk->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/produk'));
    }
    $file_gambar = $produk->row()->produkGambar;

    $this->produk->destroy(['produkId' => $produk->row()->produkId]);
    if ($this->db->affected_rows() > 0) {
      if ($file_gambar != null) {
        if ($file_gambar != 'default.png') {
          $dir_image = "./_uploads/produk/" . $file_gambar;
          if (file_exists($dir_image)) {
            unlink($dir_image);
          }
        }
      }
      activity_log('Produk', 'hapus', $produk->row()->produkNama);

      $this->session->set_flashdata('success', 'Berhasil hapus Produk!');
      return redirect(site_url('backoffice/produk'));
    }

    activity_log('Produk', 'gagal hapus', $produk->row()->produkNama);
    $this->session->set_flashdata('error', 'Gagal hapus Produk!');
    return redirect(site_url('backoffice/produk'));
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
    $generate = $kode . date('YmdHis') . rand(1000, 9999);
    $check = $this->produk->get(['produkKode' => $generate]);
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
  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
}
