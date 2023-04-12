<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Example extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');
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
      'title' => 'Example',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'backoffice/admin/example/index', $data);
  }

  public function pdf()
  {

    $data = [
      "dataku" => [
        "nama" => "Untirta Jawara",
        "url" => "https://untirta.ac.id"
      ]
    ];

    $this->load->library('pdf');

    $this->pdf->setPaper('A4', 'potrait');
    $this->pdf->filename = "example.pdf";
    $this->pdf->load_view('backoffice/admin/example/pdf', $data);
  }

  public function export_excel()
  {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
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

    // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
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

    $sheet->setCellValue('A1', "DATA UNTIRTA"); // Set kolom A1 dengan tulisan "DATA UNTIRTA"
    $sheet->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai F1
    $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
    $sheet->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

    // Buat header tabel nya pada baris ke 3
    $sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
    $sheet->setCellValue('B3', "NIM"); // Set kolom B3 dengan tulisan "NIS"
    $sheet->setCellValue('C3', "NAMA"); // Set kolom C3 dengan tulisan "NAMA"
    $sheet->setCellValue('D3', "JENIS KELAMIN"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
    $sheet->setCellValue('E3', "TELEPON"); // Set kolom E3 dengan tulisan "TELEPON"
    $sheet->setCellValue('F3', "ALAMAT"); // Set kolom F3 dengan tulisan "ALAMAT"

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A3')->applyFromArray($style_col);
    $sheet->getStyle('B3')->applyFromArray($style_col);
    $sheet->getStyle('C3')->applyFromArray($style_col);
    $sheet->getStyle('D3')->applyFromArray($style_col);
    $sheet->getStyle('E3')->applyFromArray($style_col);
    $sheet->getStyle('F3')->applyFromArray($style_col);

    // Set height baris ke 1, 2 dan 3
    $sheet->getRowDimension('1')->setRowHeight(20);
    $sheet->getRowDimension('2')->setRowHeight(20);
    $sheet->getRowDimension('3')->setRowHeight(20);

    // Buat query untuk menampilkan semua data siswa
    $sql = [
      [
        'nim' => '11217052',
        'nama' => 'Handoko Adji Pangestu',
        'jenis_kelamin' => 'Pria Sejati',
        'telp' => '089650574913',
        'alamat' => 'Serang',
      ]
    ];

    $no = 1; // Untuk penomoran tabel, di awal set dengan 1
    $row = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
    foreach ($sql as $data) { // Ambil semua data dari hasil eksekusi $sql
      $sheet->setCellValue('A' . $row, $no);
      $sheet->setCellValue('B' . $row, $data['nim']);
      $sheet->setCellValue('C' . $row, $data['nama']);
      $sheet->setCellValue('D' . $row, $data['jenis_kelamin']);
      // Khusus untuk no telepon. kita set type kolom nya jadi STRING
      $sheet->setCellValueExplicit('E' . $row, $data['telp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->setCellValue('F' . $row, $data['alamat']);
      // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
      $sheet->getStyle('A' . $row)->applyFromArray($style_row);
      $sheet->getStyle('B' . $row)->applyFromArray($style_row);
      $sheet->getStyle('C' . $row)->applyFromArray($style_row);
      $sheet->getStyle('D' . $row)->applyFromArray($style_row);
      $sheet->getStyle('E' . $row)->applyFromArray($style_row);
      $sheet->getStyle('F' . $row)->applyFromArray($style_row);
      $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom No
      $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Set text left untuk kolom NIS
      $sheet->getRowDimension($row)->setRowHeight(20); // Set height tiap row
      $no++; // Tambah 1 setiap kali looping
      $row++; // Tambah 1 setiap kali looping
    }

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
    $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
    $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
    $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
    $sheet->getColumnDimension('E')->setWidth(15); // Set width kolom E
    $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom F
    // Set orientasi kertas jadi LANDSCAPE
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    // Set judul file excel nya
    $sheet->setTitle("Laporan Data Mahasiswa");
    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Data Mahasiswa.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }

  public function import_excel()
  {
    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
      -------------------------------------------------------**/
    $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    if (isset($_FILES['excel']['name']) && in_array($_FILES['excel']['type'], $file_mimes)) {

      $arr_file = explode('.', $_FILES['excel']['name']);
      $extension = end($arr_file);

      if ('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
      } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      }

      $spreadsheet = $reader->load($_FILES['excel']['tmp_name']);

      $sheetData = $spreadsheet->getActiveSheet()->toArray();

      for ($i = 1; $i < count($sheetData); $i++) {
        if ($sheetData[$i]['1'] == null) break;

        $post[$i - 1]['mahasiswaId'] = $this->uuid->v4();
        $post[$i - 1]['mahasiswaNama'] = $sheetData[$i]['1'];
        $post[$i - 1]['mahasiswaNim'] = $sheetData[$i]['2'];
      }

      if (!isset($post)) {
        $this->session->set_flashdata('error', 'Data excel kosong!');
        return redirect(site_url('backoffice/example'));
      }

      var_dump($post); die;
    }

    $this->session->set_flashdata('error', 'Gagal! Format csv dan xlsx untuk import produk');
    return redirect(site_url('backoffice/example'));
  }
}
