<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Laporan extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    $this->load->model('Laporan_model', 'transaksi');
  }

  /**----------------------------------------------------
   * Daftar Transaksi
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');
    if ($this->input->get('from')) {
      $from = $this->input->get('from');
      if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $from)) {
        $this->session->set_flashdata('warning', 'Format Tanggal Tidak Sesuai');
        return redirect(site_url('backoffice/laporan'));
      };
    }
    if ($this->input->get('to')) {
      $to = $this->input->get('to');
      if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to)) {
        $this->session->set_flashdata('warning', 'Format Tanggal Tidak Sesuai');
        return redirect(site_url('backoffice/laporan'));
      };
    }
    $query_url = "&";
    if ($this->input->get('from')) $query_url .= "from={$this->input->get('from')}&";
    if ($this->input->get('to')) $query_url .= "to={$this->input->get('to')}&";


    $data = [
      'title' => 'Laporan Penjualan',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
      'url' => $query_url,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/laporan/index', $data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $from = null;
    $to = null;
    if ($this->input->get("from") && $this->input->get("to")) {
      $from = $this->input->get("from");
      $to = $this->input->get("to");
    }
    $list = $this->transaksi->get_datatables(null, $from, $to);
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
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/laporan/{$field->pkId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/laporan/{$field->pkId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/laporan/{$field->pkId}/hapus") . "'></i></a>";

      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/
      if ($this->akses->access_rights_aksi('backoffice/laporan/example')) $button .= "<a class='btn btn-sm btn-warning ms-1' href='" . site_url("backoffice/laporan/example/{$field->pkId}") . "'>Example</a>";
      /**----------------------------------------------------
       * Contoh penambahan aksi
      -------------------------------------------------------**/

      if ($button == '') $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->transaksiFaktur;
      $row[] = $field->transaksiNamaPembeli;
      $row[] = $field->pengNama;
      $row[] = $field->transaksiHarga;
      $row[] = $field->transaksiDiskon;
      $row[] = $field->transaksiHargaTotal;
      $row[] = $field->transaksiTunai;
      $row[] = $field->transaksiKembalian;
      // $row[] = $field->transaksiCatatan;
      $row[] = Date("Y/m/d H:i", strtotime($field->transaksiTanggal));
      $row[] = $field->transaksiStatus == 1 ? "<span class=\"badge bg-success\">Sukses</span>" : "<span class=\"badge bg-warning\">Gagal</span>";

      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->transaksi->count_all(null, $from, $to),
      "recordsFiltered" => $this->transaksi->count_filtered(null, $from, $to),
      "data" => $data,
    );

    echo json_encode($output);
  }

  /**----------------------------------------------------
   * Tambah Transaksi
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
        'field' => 'pkNama',
        'label' => 'Transaksi',
        'rules' => 'required'
      ],
      [
        'field' => 'pkKeterangan',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == false) {
      $data = [
        'title' => 'Tambah Kategori Produk'
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/laporan/create', $data);
    } else {
      $post = $this->input->post(null, true);

      $this->transaksi->create($post);
      if ($this->db->affected_rows() == 1) {
        activity_log('Kategori Produk', 'tambah', $post['pkNama']);

        $this->session->set_flashdata('success', 'Berhasil tambah Kategori Produk!');
        return redirect(site_url('backoffice/kategori-produk'));
      }

      activity_log('Kategori Produk', 'gagal tambah', $post['pkNama']);
      $this->session->set_flashdata('error', 'Gagal tambah Kategori Produk!');
      return redirect(site_url('backoffice/kategori-produk'));
    }
  }

  /**----------------------------------------------------
   * Ubah Transaksi
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
        'field' => 'pkNama',
        'label' => 'Transaksi',
        'rules' => 'required'
      ],
      [
        'field' => 'pkKeterangan',
        'label' => 'Deskripsi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $group = $this->transaksi->get(['pkId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/kategori-produk'));
    }

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() == FALSE) {
      $data = [
        'title' => 'Ubah Transaksi',
        'group' => $group->row()
      ];

      $this->template->load('template/dasbor', 'backoffice/admin/laporan/update', $data);
    } else {
      $put = $this->input->post(null, TRUE);

      $this->transaksi->update($put, ['pkId' => $group->row()->pkId]);
      if ($this->db->affected_rows() > 0) {
        activity_log('Kategori Produk', 'ubah', "data {$put['pkNama']}");

        $this->session->set_flashdata('success', 'Berhasil ubah Transaksi');
        return redirect(site_url('backoffice/kategori-produk'));
      }

      activity_log('Kategori Produk', 'gagal ubah', "data {$put['pkNama']}");
      $this->session->set_flashdata('error', 'Gagal ubah Transaksi');
      return redirect(site_url('backoffice/kategori-produk'));
    }
  }

  /**----------------------------------------------------
   * Hapus Transaksi
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
    $group = $this->transaksi->get(['pkId' => $id]);
    if ($group->num_rows() < 1) {
      $this->session->set_flashdata('warning', 'Data Tidak Ditemukan!');
      return redirect(site_url('backoffice/kategori-produk'));
    }

    $this->transaksi->destroy(['pkId' => $group->row()->pkId]);
    if ($this->db->affected_rows() > 0) {
      activity_log('Kategori Produk', 'hapus', $group->row()->pkNama);

      $this->session->set_flashdata('success', 'Berhasil hapus Kategori Produk!');
      return redirect(site_url('backoffice/kategori-produk'));
    }

    activity_log('Kategori Produk', 'gagal hapus', $group->row()->pkNama);
    $this->session->set_flashdata('error', 'Gagal hapus Kategori Produk!');
    return redirect(site_url('backoffice/kategori-produk'));
  }

  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
  function print()
  {
    if ($this->input->get('vendor')) {
      $vendor = $this->input->get('vendor');
    }
    $from = $this->input->get('from');
    $to = $this->input->get('to');
    if ($this->input->get('from')) {
      if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $from)) {
        $this->session->set_flashdata('warning', 'Format Tanggal Tidak Sesuai');
        return redirect(site_url('backoffice/laporan'));
      };
    }
    if ($this->input->get('to')) {
      if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to)) {
        $this->session->set_flashdata('warning', 'Format Tanggal Tidak Sesuai');
        return redirect(site_url('backoffice/laporan'));
      };
    }

    if ($from > $to) {
      $this->session->set_flashdata('warning', 'Format Tanggal Awal tidak boleh lebih dari tanggal akhir');
      return redirect(site_url('backoffice/laporan'));
    }

    $interval_date = array_date_range($from, $to, 'count');
    if ($interval_date > 31) :
      $this->session->set_flashdata('warning', 'Data Maksimal untuk Export adalah 31 Hari Terakhir!');
      return redirect(site_url('backoffice/laporan'));
    endif;

    $laporan = $this->transaksi->get(['transaksiTanggal <=' => $to, 'transaksiTanggal >=' => $from], 'transaksiTanggal');
    // return print_r($laporan->result());

    /* get library */
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    /* Set style general */
    $style_col = [
      'font' => ['bold' => true], // Set font nya jadi bold
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
      'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
      ]
    ];

    $style_row = [
      'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
      'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
      ]
    ];


    /* title */
    $sheet->setCellValue('A1', "LAPORAN TRANSAKSI PENJUALAN");
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1')->getFont()->setSize(15);


    $sheet->setCellValue('A3', 'NO');
    $sheet->setCellValue('B3', 'FAKTUR');
    $sheet->setCellValue('C3', 'NAMA PELANGGAN');
    $sheet->setCellValue('D3', 'KASIR');
    $sheet->setCellValue('E3', 'SUBTOTAL');
    $sheet->setCellValue('F3', 'DISKON');
    $sheet->setCellValue('G3', 'TOTAL');
    $sheet->setCellValue('H3', 'TUNAI');
    $sheet->setCellValue('I3', 'KEMBALI');
    $sheet->setCellValue('J3', 'STATUS');
    $sheet->setCellValue('K3', 'TANGGAL TRANSAKSI');


    /* format style header */
    $sheet->getStyle('A3')->applyFromArray($style_col);
    $sheet->getStyle('B3')->applyFromArray($style_col);
    $sheet->getStyle('C3')->applyFromArray($style_col);
    $sheet->getStyle('D3')->applyFromArray($style_col);
    $sheet->getStyle('E3')->applyFromArray($style_col);
    $sheet->getStyle('F3')->applyFromArray($style_col);
    $sheet->getStyle('G3')->applyFromArray($style_col);
    $sheet->getStyle('H3')->applyFromArray($style_col);
    $sheet->getStyle('I3')->applyFromArray($style_col);
    $sheet->getStyle('J3')->applyFromArray($style_col);
    $sheet->getStyle('K3')->applyFromArray($style_col);
    /* end style */

    $row = 4;
    foreach ($laporan->result() as $key => $d) {
      $sheet->setCellValue('A' . $row, ++$key);
      $sheet->setCellValue('B' . $row, "'" . $d->transaksiFaktur);
      $sheet->setCellValue('C' . $row, $d->transaksiNamaPembeli);
      $sheet->setCellValue('D' . $row, $d->pengNama);
      $sheet->setCellValue('E' . $row, $d->transaksiHarga);
      $sheet->setCellValue('F' . $row, $d->transaksiDiskon);
      $sheet->setCellValue('G' . $row, $d->transaksiHargaTotal);
      $sheet->setCellValue('H' . $row, $d->transaksiTunai);
      $sheet->setCellValue('I' . $row, $d->transaksiKembalian);
      $sheet->setCellValue('J' . $row, $d->transaksiStatus == 1 ? "Sukses" : "Gagal");
      $sheet->setCellValue('K' . $row, Date("Y/m/d H:i", strtotime($d->transaksiTanggal)));

      /* format style rows */
      $sheet->getStyle('A' . $row)->applyFromArray($style_row);
      $sheet->getStyle('B' . $row)->applyFromArray($style_row);
      $sheet->getStyle('C' . $row)->applyFromArray($style_row);
      $sheet->getStyle('D' . $row)->applyFromArray($style_row);
      $sheet->getStyle('E' . $row)->applyFromArray($style_row);
      $sheet->getStyle('F' . $row)->applyFromArray($style_row);
      $sheet->getStyle('G' . $row)->applyFromArray($style_row);
      $sheet->getStyle('H' . $row)->applyFromArray($style_row);
      $sheet->getStyle('I' . $row)->applyFromArray($style_row);
      $sheet->getStyle('J' . $row)->applyFromArray($style_row);
      $sheet->getStyle('K' . $row)->applyFromArray($style_row);
      /* end style */
      $row++;
    }

    /* closing lib */
    $sheet->getRowDimension('1')->setRowHeight(20);
    $sheet->getRowDimension('2')->setRowHeight(20);
    $sheet->getRowDimension('7')->setRowHeight(20);

    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(20);
    $sheet->getColumnDimension('I')->setWidth(20);
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(20);

    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

    $sheet->setTitle("LAPORAN TRANSAKSI PENJUALAN");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="REKAP-TRANSAKSI.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    /* end of closing lib */
  }

  function cek_tgl_awal()
  {
    $tgl_awal = $_POST['from'];
    $tgl_akhir = $_POST['to'];
    if ($tgl_awal != null) {
      if ($tgl_awal > $tgl_akhir) {
        $this->form_validation->set_message('cek_tgl_awal', '{field} tidak boleh lebih dari Tanggal Akhir!');
        return false;
      } else {
        return true;
      }
    }
    return true;
  }

  function cek_tgl_akhir()
  {
    $tgl_awal = $_POST['from'];
    $tgl_akhir = $_POST['to'];
    if ($tgl_awal != null) {
      if ($tgl_akhir < $tgl_awal) {
        $this->form_validation->set_message('cek_tgl_akhir', '{field} tidak boleh kurang dari Tanggal Awal!');
        return false;
      } else {
        return true;
      }
    }
    return true;
  }
  /**----------------------------------------------------
   * Contoh penambahan aksi
  -------------------------------------------------------**/
}
