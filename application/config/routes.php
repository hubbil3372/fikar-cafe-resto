<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth/login';
$route['404_override'] = 'Page/not_found_404';
$route['translate_uri_dashes'] = FALSE;

/* 
| -------------------------------------------------------------------------
| CUSTOM ROUTES
| -------------------------------------------------------------------------
*/

/**----------------------------------------------------
 * Auth
-------------------------------------------------------**/
$route['daftar'] = 'Auth/register';
$route['lupa-password'] = 'Auth/forgot_password';
$route['masuk'] = 'Auth/login';
$route['keluar'] = 'Auth/logout';
$route['reset-kata-sandi/(:any)'] = 'Auth/reset_password/$1';

$route['backoffice/data-diri'] = 'Auth/profile';
$route['backoffice/ganti-password'] = 'Auth/change_password';


/**----------------------------------------------------
 * Dasbor
-------------------------------------------------------**/
$route['backoffice/dasbor'] = 'backoffice/admin/Dasbor';

/**----------------------------------------------------
 * Menu Menejmen
-------------------------------------------------------**/
$route['backoffice/menu-manajemen'] = 'backoffice/admin/Menu';

/**----------------------------------------------------
 * Pengguna
-------------------------------------------------------**/
$route['backoffice/pengguna/tambah'] = 'Auth/create';
$route['backoffice/pengguna'] = 'Auth';
$route['backoffice/pengguna/(:any)/ubah'] = 'Auth/update/$1';
$route['backoffice/pengguna/(:any)/hapus'] = 'Auth/destroy/$1';

/**----------------------------------------------------
 * Grup
-------------------------------------------------------**/
$route['backoffice/grup/tambah'] = 'backoffice/admin/Grup/create';
$route['backoffice/grup'] = 'backoffice/admin/Grup';
$route['backoffice/grup/(:any)/ubah'] = 'backoffice/admin/Grup/update/$1';
$route['backoffice/grup/(:any)/hapus'] = 'backoffice/admin/Grup/destroy/$1';

/**----------------------------------------------------
 * Development
-------------------------------------------------------**/
$route['backoffice/example'] = 'backoffice/admin/Example';
$route['backoffice/example/pdf'] = 'backoffice/admin/Example/pdf';
$route['backoffice/example/export-excel'] = 'backoffice/admin/Example/export_excel';
$route['backoffice/example/import-excel'] = 'backoffice/admin/Example/import_excel';

// Contoh penambahan unit kerja
$route['backoffice/grup/example/(:any)'] = 'backoffice/admin/Grup/example/$1';
// Contoh penambahan unit kerja


/**----------------------------------------------------
 * Hak Akses
-------------------------------------------------------**/
$route['backoffice/hak-akses/tambah'] = 'backoffice/admin/HakAkses/create';
$route['backoffice/hak-akses'] = 'backoffice/admin/HakAkses';
$route['backoffice/hak-akses/(:any)/grup'] = 'backoffice/admin/HakAkses/show/$1';
$route['backoffice/hak-akses/(:any)/ubah'] = 'backoffice/admin/HakAkses/update/$1';
$route['backoffice/hak-akses/(:any)/hapus'] = 'backoffice/admin/HakAkses/destroy/$1';

/**----------------------------------------------------
 * Aksi
-------------------------------------------------------**/
$route['backoffice/hak-akses/(:any)/grup/(:any)/menu/tambah'] = 'backoffice/admin/Aksi/create/$1/$2';
$route['backoffice/hak-akses/(:any)/grup/(:any)/menu/(:any)/menu-grup/ubah'] = 'backoffice/admin/Aksi/update/$1/$2/$3';
$route['backoffice/hak-akses/(:any)/grup/(:any)/menu-grup/hapus'] = 'backoffice/admin/Aksi/destroy/$1/$2';

/**----------------------------------------------------
 * Unit Kerja
-------------------------------------------------------**/
$route['backoffice/unit-kerja/tambah'] = 'backoffice/admin/UnitKerja/create';
$route['backoffice/unit-kerja'] = 'backoffice/admin/UnitKerja';
$route['backoffice/unit-kerja/(:any)/ubah'] = 'backoffice/admin/UnitKerja/update/$1';
$route['backoffice/unit-kerja/(:any)/hapus'] = 'backoffice/admin/UnitKerja/destroy/$1';
$route['backoffice/hak-akses/(:any)/grup/(:any)/pengguna'] = 'backoffice/admin/UnitKerja/access/$1/$2';
$route['backoffice/hak-akses/(:any)/grup/(:any)/pengguna/(:any)/unit-kerja'] = 'backoffice/admin/UnitKerja/create_access/$1/$2/$3';

/**----------------------------------------------------
 * Log
-------------------------------------------------------**/
$route['backoffice/log'] = 'backoffice/admin/Log';

/**----------------------------------------------------
 * Load Time
-------------------------------------------------------**/
$route['backoffice/load-time'] = 'backoffice/admin/LoadTime';

/**----------------------------------------------------
 * Dokumentasi
-------------------------------------------------------**/
$route['backoffice/dokumentasi'] = 'backoffice/admin/Dokumentasi';


/**----------------------------------------------------
 * Kategori Produk
-------------------------------------------------------**/
$route['backoffice/kategori-produk'] = 'backoffice/admin/Kategori_produk';
$route['backoffice/kategori-produk/tambah'] = 'backoffice/admin/Kategori_produk/create';
$route['backoffice/kategori-produk/get_json'] = 'backoffice/admin/Kategori_produk/get_json';
$route['backoffice/kategori-produk/(:any)/ubah'] = 'backoffice/admin/Kategori_produk/update/$1';
$route['backoffice/kategori-produk/(:any)/hapus'] = 'backoffice/admin/Kategori_produk/destroy/$1';


/**----------------------------------------------------
 * Produk
-------------------------------------------------------**/
$route['backoffice/produk'] = 'backoffice/admin/Produk';
$route['backoffice/produk/tambah'] = 'backoffice/admin/Produk/create';
$route['backoffice/produk/get_json'] = 'backoffice/admin/Produk/get_json';
$route['backoffice/produk/(:any)/ubah'] = 'backoffice/admin/Produk/update/$1';
$route['backoffice/produk/(:any)/hapus'] = 'backoffice/admin/Produk/destroy/$1';

/**----------------------------------------------------
 * Transaksi
-------------------------------------------------------**/
$route['backoffice/transaksi'] = 'backoffice/admin/Transaksi';
$route['backoffice/transaksi/tambah'] = 'backoffice/admin/Transaksi/create';
$route['backoffice/transaksi/get_json'] = 'backoffice/admin/Transaksi/get_json';
$route['backoffice/transaksi/get-json-produk/(:any)'] = 'backoffice/admin/Transaksi/get_json_produk/$1';
$route['backoffice/transaksi/(:any)/ubah'] = 'backoffice/admin/Transaksi/update/$1';
$route['backoffice/transaksi/(:any)/hapus'] = 'backoffice/admin/Transaksi/destroy/$1';
$route['backoffice/transaksi/detail/(:any)'] = 'backoffice/admin/Transaksi/show/$1';
$route['backoffice/transaksi/hapus-item/(:any)'] = 'backoffice/admin/Transaksi/destroy_details/$1';
$route['backoffice/transaksi/tambah-item/(:any)/(:any)'] = 'backoffice/admin/Transaksi/create_details/$1/$2';
$route['backoffice/transaksi/detail-item/(:any)'] = 'backoffice/admin/Transaksi/show_details/$1';
$route['backoffice/transaksi/ubah-item'] = 'backoffice/admin/Transaksi/update_details';


/**----------------------------------------------------
 * Pesanan
-------------------------------------------------------**/
$route['backoffice/pesanan'] = 'backoffice/admin/Pesanan';
$route['backoffice/pesanan/tambah'] = 'backoffice/admin/Pesanan/create';
$route['backoffice/pesanan/get_json_produk'] = 'backoffice/admin/Pesanan/get_json_produk';
$route['backoffice/pesanan/(:any)/ubah'] = 'backoffice/admin/Pesanan/update/$1';
$route['backoffice/pesanan/(:any)/batalkan'] = 'backoffice/admin/Pesanan/destroy/$1';
$route['backoffice/pesanan/cetak/(:any)'] = 'backoffice/admin/Pesanan/struk/$1';

/* keranjang */
$route['backoffice/keranjang/tambah/(:any)'] = 'backoffice/admin/keranjang/create/$1';
$route['backoffice/keranjang/ubah'] = 'backoffice/admin/keranjang/update';
$route['backoffice/keranjang/data'] = 'backoffice/admin/keranjang/data';
$route['backoffice/keranjang/(:any)/hapus'] = 'backoffice/admin/Keranjang/destroy/$1';

/* ketersediaan menu */
$route['backoffice/menu-tersedia'] = 'backoffice/admin/Menu_tersedia';
$route['backoffice/menu-tersedia/get_json'] = 'backoffice/admin/Menu_tersedia/get_json';
$route['backoffice/menu-tersedia/update-menu/(:any)/(:num)'] = 'backoffice/admin/Menu_tersedia/update_available/$1/$2';


/**----------------------------------------------------
 * Laporan
-------------------------------------------------------**/
$route['backoffice/laporan'] = 'backoffice/admin/laporan';
$route['backoffice/laporan/tambah'] = 'backoffice/admin/laporan/create';
$route['backoffice/laporan/get_json'] = 'backoffice/admin/laporan/get_json';
$route['backoffice/laporan/(:any)/ubah'] = 'backoffice/admin/laporan/update/$1';
$route['backoffice/laporan/(:any)/hapus'] = 'backoffice/admin/laporan/destroy/$1';
$route['backoffice/laporan/detail/(:any)'] = 'backoffice/admin/laporan/show/$1';
$route['backoffice/laporan/print'] = 'backoffice/admin/laporan/print';

/**----------------------------------------------------
 * daftar menu
-------------------------------------------------------**/
$route['daftar-menu'] = 'daftar_menu';
$route['daftar-menu/(:any)'] = 'daftar_menu/index/$1';
