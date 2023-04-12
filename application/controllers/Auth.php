<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */

class Auth extends CI_Controller
{
  public $data = [];

  public function __construct()
  {
    parent::__construct();

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->load->database();
    $this->load->helper(['url', 'language', 'captcha']);
    $this->lang->load('auth');
    $this->load->model('Pengguna_model', 'pengguna');
  }

  /**----------------------------------------------------
   * Daftar Pengguna
  -------------------------------------------------------**/
  public function index()
  {
    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) return redirect(site_url('masuk'), 'refresh');

    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_menu($menu)) redirect('404_override', 'refresh');

    $this->data = [
      'title' => 'Pengguna Admin',
      /**----------------------------------------------------
       * Ambil id menu untuk cek akses Create
      -------------------------------------------------------**/
      'menu_id' => $menu,
    ];

    $this->template->load('template/dasbor', 'auth/index', $this->data);
  }

  /**----------------------------------------------------
   * Datatable
  -------------------------------------------------------**/
  public function get_json()
  {
    $list = $this->pengguna->get_datatables();

    /**----------------------------------------------------
     * Ambil id menu untuk cek akses Update dan Destroy
    -------------------------------------------------------**/
    $menu_id = $this->menus->get_menu_id("backoffice/{$this->input->get('tautan')}");

    $data = array();
    $no = @$_POST['start'];
    foreach ($list as $field) {
      $status = "Aktif";
      if ($field->pengAktif == 0) $status = "Tidak Aktif";

      /**----------------------------------------------------
       * Cek apakah role yang sedang login dapat melakukan Update dan Destroy
      -------------------------------------------------------**/
      $button = '';
      if ($this->akses->access_rights($menu_id, 'grupMenuUbah')) $button .= "<a class='btn btn-sm btn-primary me-1 waitme' href='" . site_url("backoffice/pengguna/{$field->pengId}/ubah") . "'><i class='fas fa-edit'></i></a>";
      if ($this->akses->access_rights($menu_id, 'grupMenuHapus')) $button .= "<a class='btn btn-sm btn-danger destroy' href='" . site_url("backoffice/pengguna/{$field->pengId}/hapus") . "'><i class='fas fa-trash destroy' href='" . site_url("backoffice/pengguna/{$field->pengId}/hapus") . "'></i></a>";
      if ($button == '') $button = '-';

      /**----------------------------------------------------
       * Cek apakah yang sedang login merupakan data terkait
      -------------------------------------------------------**/
      if ($this->ion_auth->get_user_id() == $field->pengId) $button = '-';

      $no++;
      $row = array();
      $row[] = "<div class='text-center'>{$no}</div>";
      $row[] = $field->pengNama;
      $row[] = $field->pengEmail;
      $row[] = $field->pengTlp;
      $row[] = $field->grupNama;
      $row[] = "<div class='text-center'>{$status}</div>";
      $row[] = "<div class='text-center'>{$button}</div>";

      $data[] = $row;
    }

    $output = array(
      "draw" => @$_POST['draw'],
      "recordsTotal" => $this->pengguna->count_all(),
      "recordsFiltered" => $this->pengguna->count_filtered(),
      "data" => $data,
    );

    echo json_encode($output);
  }


  /**----------------------------------------------------
   * Tambah Pengguna
  -------------------------------------------------------**/
  public function create()
  {
    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) redirect(site_url('auth/login'), 'refresh');

    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuTambah')) redirect('404_override', 'refresh');

    $tables = $this->config->item('tables', 'ion_auth');
    $identity_column = $this->config->item('identity', 'ion_auth');

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    if ($identity_column !== 'pengEmail') {
      $this->form_validation->set_rules('identity', 'Nama Pengguna', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
      $this->form_validation->set_rules('pengEmail', 'Alamat Email', 'trim|required|valid_email');
    } else {
      $this->form_validation->set_rules('pengEmail', 'Alamat Email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.pengEmail]');
    }

    $config_form = [
      [
        'field' => 'pengNama',
        'label' => 'Nama Lengkap',
        'rules' => 'required'
      ],
      [
        'field' => 'pengTlp',
        'label' => 'Nomor Telpon',
        'rules' => 'required'
      ],
      [
        'field' => 'pengJenisKelamin',
        'label' => 'Jenis Kelamin',
        'rules' => 'required'
      ],
      [
        'field' => 'pengPass',
        'label' => 'Kata Sandi',
        'rules' => 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]',
        'errors' => [
          'min_length' => 'Minimal kata sandi ' . $this->config->item('min_password_length', 'ion_auth') . ' karakter!',
          'matches' => '{field} tidak sama dengan konfirmasi',
        ]
      ],
      [
        'field' => 'password_confirm',
        'label' => 'Konfirmasi Kata Sandi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');
    $this->form_validation->set_message('is_unique', '{field} Sudah Digunakan!');

    /**----------------------------------------------------
     * Cek apakah inputan sudah sesuai
    -------------------------------------------------------**/
    if ($this->form_validation->run() === TRUE) {
      /**----------------------------------------------------
       * Siapkan data untuk disimpan
      -------------------------------------------------------**/
      $email = $this->input->post('pengEmail', true);
      $identity = ($identity_column === 'pengEmail') ? $email : $this->input->post('identity', true);
      $password = $this->input->post('pengPass', true);

      $additional_data = [
        'pengNama' => ucwords(strtolower($this->input->post('pengNama', true))),
        // 'pengInstansi' => $this->input->post('pengInstansi', true),
        'pengTlp' => $this->input->post('pengTlp', true),
        'pengJenisKelamin' => $this->input->post('pengJenisKelamin', true),
      ];
    }

    /**----------------------------------------------------
     * Cek ulang apakah inputan sudah sesuai dan data tersimpan di database
    -------------------------------------------------------**/
    if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, [$this->input->post('grupId', true)])) {
      activity_log('pengguna', 'tambah', ucwords(strtolower($this->input->post('pengNama', true))));

      $this->session->set_flashdata('success', 'Berhasil tambah pengguna!');
      return redirect(site_url('backoffice/pengguna'), 'refresh');
    } else {
      $this->data = [
        'title' => 'Tambah Pengguna',
        'groups' => $this->ion_auth->groups()->result_array()
      ];

      $this->template->load('template/dasbor', 'auth/create_user', $this->data);
    }
  }

  /**----------------------------------------------------
   * Ubah Pengguna
  -------------------------------------------------------**/
  public function update($id)
  {
    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) return redirect('auth', 'refresh');

    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuUbah')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $user = $this->ion_auth->user($id)->row();
    if ($user == null) {
      $this->session->set_flashdata('warning', 'Data tidak ditemukan');
      return redirect(site_url('backoffice/pengguna'));
    }

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'pengNama',
        'label' => 'Nama Lengkap',
        'rules' => 'required'
      ],
      [
        'field' => 'pengTlp',
        'label' => 'Nomor Telpon',
        'rules' => 'required'
      ],
      [
        'field' => 'pengJenisKelamin',
        'label' => 'Jenis Kelamin',
        'rules' => 'required'
      ],
    ];

    /**----------------------------------------------------
     * Apabila password di isi
    -------------------------------------------------------**/
    if ($this->input->post('pengPass', true)) {
      $this->form_validation->set_rules('pengPass', 'Kata Sandi', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
      $this->form_validation->set_rules('password_confirm', 'Konfirmasi Kata Sandi', 'required');
    }

    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');
    $this->form_validation->set_message('is_unique', '{field} Sudah Digunakan!');
    $this->form_validation->set_message('min_length', 'Minimal kata sandi ' . $this->config->item('min_password_length', 'ion_auth') . ' karakter!');

    if (isset($_POST) && !empty($_POST)) {

      if ($this->form_validation->run() === TRUE) {
        $data = [
          'pengNama' => ucwords(strtolower($this->input->post('pengNama', true))),
          // 'pengInstansi' => $this->input->post('pengInstansi', true),
          'pengTlp' => $this->input->post('pengTlp', true),
          'pengAktif' => $this->input->post('pengAktif', true),
          'pengJenisKelamin' => $this->input->post('pengJenisKelamin', true),
        ];

        /**----------------------------------------------------
         * Update password jikad ada
        -------------------------------------------------------**/
        if ($this->input->post('pengPass', true)) {
          $data['pengPass'] = $this->input->post('pengPass', true);
        }

        /**----------------------------------------------------
         * Siapkan data dan Update grup kedalam database
        -------------------------------------------------------**/
        $this->ion_auth->remove_from_group('', $id);
        $this->ion_auth->add_to_group($this->input->post('grupId', true), $id);


        /**----------------------------------------------------
         * Cek Jika data ter Update
        -------------------------------------------------------**/
        if ($this->ion_auth->update($user->pengId, $data)) {
          activity_log('pengguna', 'ubah', ucwords(strtolower($this->input->post('pengNama', true))));

          $this->session->set_flashdata('success', 'Berhasil merubah pengguna');
          return redirect(site_url('backoffice/pengguna'));
        } else {
          $this->session->set_flashdata('error', 'Gagal merubah pengguna');
          return redirect(site_url('backoffice/pengguna'));
        }
      }
    }

    $this->data = [
      'title' => 'Ubah Pengguna',
      'user' => $user,
      'current_group' => $this->ion_auth->get_users_groups($id)->row_array(),
      'groups' => $this->ion_auth->groups()->result_array(),
    ];

    $this->template->load('template/dasbor', 'auth/edit_user', $this->data);
  }

  /**----------------------------------------------------
   * Hapus Pengguna
  -------------------------------------------------------**/
  public function destroy($id)
  {
    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if (!$this->ion_auth->logged_in()) return redirect('auth', 'refresh');

    /**----------------------------------------------------
     * Cek apakah pengguna dapat akses menu
    -------------------------------------------------------**/
    $menu = $this->menus->get_menu_id("backoffice/{$this->uri->segment(2)}");
    if (!$this->akses->access_rights($menu, 'grupMenuHapus')) redirect('404_override', 'refresh');

    /**----------------------------------------------------
     * Cek apakah data yang di edit ada dalam database
    -------------------------------------------------------**/
    $user = $this->pengguna->get(['pengId' => $id]);
    if ($user == 0) {
      $this->session->set_flashdata('warning', 'Data tidak ditemukan');
      return redirect(site_url('backoffice/pengguna'));
    }

    /**----------------------------------------------------
     * Cek apakah data gambar merupakan default
    -------------------------------------------------------**/
    $file_gambar = $user->pengFoto;
    if ($file_gambar != 'default.jpg') {
      /**----------------------------------------------------
       * Cek apakah ada dalam direktori
      -------------------------------------------------------**/
      $dir_image = './_assets/images/profile/' . $file_gambar;
      if (file_exists($dir_image)) {
        unlink($dir_image);
      }
    }

    $this->pengguna->destroy(['pengId' => $id]);

    if ($this->db->affected_rows() > 0) {
      activity_log('pengguna', 'hapus', $user->row()->pengNama);

      $this->session->set_flashdata('success', 'Berhasil hapus pengguna!');
      return redirect(site_url('backoffice/pengguna'));
    }

    $this->session->set_flashdata('error', 'Gagal hapus pengguna!');
    return redirect(site_url('backoffice/pengguna'));
  }

  /**----------------------------------------------------
   * Login Pengguna
  -------------------------------------------------------**/
  public function login()
  {
    /**----------------------------------------------------
     * Cek apakah sudah login
    -------------------------------------------------------**/
    if ($this->ion_auth->logged_in()) {
      redirect(site_url('backoffice/dasbor'));
    }

    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'identity',
        'label' => 'Alamat Email',
        'rules' => 'required'
      ],
      [
        'field' => 'password',
        'label' => 'Kata Sandi',
        'rules' => 'required'
      ],
      [
        'field' => 'captcha',
        'label' => 'Captcha',
        'rules' => 'required|callback_check_captcha'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    if ($this->form_validation->run() === TRUE) {
      /**----------------------------------------------------
       * Cek apakah user menekan remember me
      -------------------------------------------------------**/
      $remember = (bool)$this->input->post('remember', true);

      if ($this->ion_auth->login($this->input->post('identity', true), $this->input->post('password', true), $remember)) {
        $this->session->set_flashdata('success', 'Berhasil masuk kedalam sistem!');
        $this->_clear_captcha();
        redirect(site_url('backoffice/dasbor'), 'refresh');
      } else {
        redirect(site_url('masuk'), 'refresh');
      }
    } else {
      $this->data = [
        'captcha' => $this->_generate_captcha()
      ];

      $this->template->load('template/auth', 'auth/login', $this->data);
    }
  }

  /**----------------------------------------------------
   * Captcha
  -------------------------------------------------------**/
  private function _generate_captcha()
  {
    $captcha_old = $this->session->userdata('file');
    if ($captcha_old) {
      $dir = './_assets/images/captcha/' . $captcha_old;
      if (file_exists($dir)) {
        unlink($dir);
      }
    }

    $vals = [
      'word'          => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5),
      'img_path'      => './_assets/images/captcha/',
      'img_url'       => base_url('_assets/images/captcha/'),
      'img_width'     => '200',
      'img_height'    => 30,
      'expiration'    => 7200,
      'word_length'   => 12,
      'font_size'     => 19,
      'img_id'        => 'Imageid',
      'pool'          => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

      'colors'        => [
        'background' => [255, 255, 255],
        'border'    => [228, 253, 253],
        'text'      => [0, 0, 0],
        'grid'      => [255, 140, 40]
      ]
    ];
    $captcha = create_captcha($vals);
    $this->session->set_userdata(['captcha' => $captcha['word'], 'file' => $captcha['filename']]);
    return $captcha;
  }

  /**----------------------------------------------------
   * Clear Capthca
  -------------------------------------------------------**/
  private function _clear_captcha()
  {
    $captcha_old = $this->session->userdata('file');
    if ($captcha_old) {
      $dir = './uploads/captcha/' . $captcha_old;
      if (file_exists($dir)) {
        unlink($dir);
      }
    }
    $this->session->unset_userdata(['file', 'captcha']);
  }

  /**----------------------------------------------------
   * Callback
  -------------------------------------------------------**/
  function check_captcha()
  {
    $captcha = $this->session->userdata('captcha');
    $kode_captcha = $_POST['captcha'];
    if ($captcha != $kode_captcha) {
      $this->form_validation->set_message('check_captcha', '{field} Tidak Sesuai!');
      return false;
    } else {
      return true;
    }
  }

  /**----------------------------------------------------
   * Data Diri Pengguna
  -------------------------------------------------------**/
  public function profile()
  {
    /**----------------------------------------------------
     * Konfigurasi Form Validation
    -------------------------------------------------------**/
    $config_form = [
      [
        'field' => 'pengNama',
        'label' => 'Nama Lengkap',
        'rules' => 'required'
      ],
      [
        'field' => 'pengTlp',
        'label' => 'Nomor HP',
        'rules' => 'required'
      ],
      [
        'field' => 'pengJenisKelamin',
        'label' => 'Jenis Kelamin',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    $user = $this->ion_auth->user()->row();

    if ($this->form_validation->run() === false) {
      $data = [
        'title'   => 'Data Diri',
        'user' => $user,
      ];

      $this->template->load('template/dasbor', 'auth/profile', $data);
    } else {
      $put = $this->input->post(null, true);
      $put['pengNama'] = ucwords(strtolower($put['pengNama']));
      // cek pass dan ubah password
      // if (isset($put['pengPass'])) $this->ion_auth->edit_password($put['pengEmail'], $put['pengPass']);
      // unset($put['pengPass']);

      // config image
      $config['upload_path']          = './_assets/images/profile';
      $config['allowed_types']        = 'png|jpg|jpeg';
      $config['max_size']             = 2048;
      $config['file_name']            = 'PROFILE_' . date('YmdHis') . '_' . rand(1000, 9999);
      $this->load->library('upload', $config);

      if (@$_FILES['image']['name'] == "") {
        $this->pengguna->update($put, ['pengId' => $user->pengId]);
      } else {
        if ($this->upload->do_upload('image')) {
          $file_gambar = $user->pengFoto;
          if ($file_gambar != 'default.jpg') {
            $dir_image = './_assets/images/profile/' . $file_gambar;
            if (file_exists($dir_image)) {
              unlink($dir_image);
            }
          }

          $put['pengFoto'] = $this->upload->data('file_name');
          $this->pengguna->update($put, ['pengId' => $user->pengId]);
        } else {
          $error_file = $this->upload->display_errors();
          $this->session->set_flashdata('error', strip_tags($error_file));
          redirect("backoffice/{$this->url}/create");
        }
      }

      if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', 'Berhasil merubah data diri!');
        redirect(site_url('backoffice/data-diri'));
      } else {
        $this->session->set_flashdata('error', 'Gagal merubah data diri!');
        redirect(site_url('backoffice/data-diri'));
      }
    }
  }

  /**----------------------------------------------------
   * Daftar Pengguna
  -------------------------------------------------------**/
  public function register()
  {
    // checking if not logged in
    if ($this->ion_auth->logged_in()) {
      redirect(site_url('dashboard'));
    }

    // form validation
    $config_form = [
      [
        'field' => 'pengNama',
        'label' => 'Nama Lengkap',
        'rules' => 'required'
      ],
      [
        'field' => 'pengEmail',
        'label' => 'Alamat Email',
        'rules' => 'required|is_unique[pengguna.pengEmail]',
        'errors' => [
          'is_unique' => '{field} Sudah Digunakan!'
        ]
      ],
      [
        'field' => 'pengTlp',
        'label' => 'Nomor Telpon',
        'rules' => 'required'
      ],
      [
        'field' => 'pengPass',
        'label' => 'Kata Sandi',
        'rules' => 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']',
        'errors' => [
          'min_length' => 'Minimal kata sandi ' . $this->config->item('min_password_length', 'ion_auth') . ' karakter!',
          'is_unique' => '{field} Sudah Digunakan!'
        ]
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    if ($this->form_validation->run() === false) {
      $this->template->load('template/auth', 'auth/register', $this->data);
    } else {

      // getting all data from form
      $data = $this->input->post(null, true);

      // post data to users table
      $register = $this->ion_auth->register($data['pengEmail'], $data['pengPass'], $data['pengEmail'], [
        'pengNama' => ucwords(strtolower($data['pengNama'])),
        'pengTlp' => $data['pengTlp'],
        'pengInstansi' => $data['pengInstansi'],
      ], [2]);

      // checkin if data success
      if ($register !== FALSE) {
        if ($this->config->item('email_activation', 'ion_auth')) {
          $this->session->set_flashdata('success', 'Cek pada alamat email untuk aktivasi akun!');
        } else {
          $this->session->set_flashdata('success', 'Berhasil daftar kedalam sistem!');
        }
        redirect(site_url('masuk'));
      }

      $this->session->set_flashdata('error', 'Gagal daftar pada sistem!');
      redirect(site_url('daftar'));
    }
  }

  /**----------------------------------------------------
   * Keluar Pengguna
  -------------------------------------------------------**/
  public function logout()
  {
    // log the user out
    $this->ion_auth->logout();

    // redirect them to the login page
    redirect(site_url('masuk'), 'refresh');
  }

  /**----------------------------------------------------
   * Ubah Kata Sandi Pengguna
  -------------------------------------------------------**/
  public function change_password()
  {
    if (!$this->ion_auth->logged_in()) {
      redirect('masuk', 'refresh');
    }

    // form validation
    $config_form = [
      [
        'field' => 'old',
        'label' => 'Kata Sandi Lama',
        'rules' => 'required'
      ],
      [
        'field' => 'new',
        'label' => 'Kata Sandi Baru',
        'rules' => 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]',
        'errors' => [
          'min_length' => 'Minimal kata sandi ' . $this->config->item('min_password_length', 'ion_auth') . ' karakter!',
          'matches' => '{field} tidak sama dengan konfirmasi',
        ]
      ],
      [
        'field' => 'new_confirm',
        'label' => 'Konfirmasi Kata Sandi',
        'rules' => 'required'
      ],
    ];
    $this->form_validation->set_rules($config_form);
    $this->form_validation->set_message('required', '{field} Tidak Boleh kosong!');

    $user = $this->ion_auth->user()->row();

    if ($this->form_validation->run() === FALSE) {
      // display the form
      // set the flash data error message if there is one
      $this->data['title'] = 'Ganti Kata Sandi';
      $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

      $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
      $this->data['old_password'] = [
        'name' => 'old',
        'id' => 'old',
        'type' => 'password',
      ];
      $this->data['new_password'] = [
        'name' => 'new',
        'id' => 'new',
        'type' => 'password',
        'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
      ];
      $this->data['new_password_confirm'] = [
        'name' => 'new_confirm',
        'id' => 'new_confirm',
        'type' => 'password',
        'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
      ];
      $this->data['user_id'] = [
        'name' => 'user_id',
        'id' => 'user_id',
        'type' => 'hidden',
        'value' => $user->pengId,
      ];

      $this->template->load('template/dasbor', 'auth/change_password', $this->data);
    } else {
      $identity = $this->session->userdata('identity');
      $change = $this->ion_auth->change_password($identity, $this->input->post('old', true), $this->input->post('new', true));

      if ($change) {
        $this->session->set_flashdata('success', 'Berhasil merubah kata sandi!');
        return redirect('backoffice/ganti-password', 'refresh');
      } else {
        $this->session->set_flashdata('error', 'Gagal merubah kata sandi!');
        return redirect('backoffice/ganti-password', 'refresh');
      }
    }
  }

  /**----------------------------------------------------
   * Lupa kata sandi pengguna
  -------------------------------------------------------**/
  public function forgot_password()
  {
    $this->data['title'] = $this->lang->line('forgot_password_heading');

    // setting validation rules by checking whether identity is username or email
    if ($this->config->item('identity', 'ion_auth') != 'pengEmail') {
      $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
    } else {
      $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
    }

    if ($this->form_validation->run() === FALSE) {
      $this->data['type'] = $this->config->item('identity', 'ion_auth');
      // setup the input
      $this->data['identity'] = [
        'name' => 'identity',
        'id' => 'identity',
      ];

      if ($this->config->item('identity', 'ion_auth') != 'pengEmail') {
        $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
      } else {
        $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
      }

      // set any errors and display the form
      $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
      $this->template->load('template/auth', 'auth/forgot_password', $this->data);
    } else {
      $identity_column = $this->config->item('identity', 'ion_auth');
      $identity = $this->ion_auth->where($identity_column, $this->input->post('identity', true))->users()->row();

      if (empty($identity)) {
        $this->session->set_flashdata('error', 'Email tidak ditemukan dalam sistem!');
        redirect(site_url('lupa-password'), 'refresh');
      }

      // run the forgotten password method to email an activation code to the user
      $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

      if ($forgotten) {
        // if there were no errors
        $this->session->set_flashdata('success', 'Reset kata sandi telah terkirim ke email');
        redirect(site_url('masuk'), 'refresh'); //we should display a confirmation page here instead of the login page
      } else {
        $this->session->set_flashdata('error', 'Tidak dapat mengirim email tautan!');
        redirect(site_url('lupa-password'), 'refresh');
      }
    }
  }

  /**----------------------------------------------------
   * Reset kata sandi pengguna
  -------------------------------------------------------**/
  public function reset_password($code = NULL)
  {
    if (!$code) {
      show_404();
    }

    $this->data['title'] = "Reset Kata Sandi";

    $user = $this->ion_auth->forgotten_password_check($code);

    if ($user) {
      $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
      $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

      if ($this->form_validation->run() === FALSE) {
        // display the form

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['new_password'] = [
          'name' => 'new',
          'id' => 'new',
          'type' => 'password',
          'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        ];
        $this->data['new_password_confirm'] = [
          'name' => 'new_confirm',
          'id' => 'new_confirm',
          'type' => 'password',
          'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        ];
        $this->data['user_id'] = [
          'name' => 'user_id',
          'id' => 'user_id',
          'type' => 'hidden',
          'value' => $user->pengId,
        ];
        $this->data['code'] = $code;

        // render
        $this->template->load('template/auth', 'auth/reset_password', $this->data);
      } else {
        $identity = $user->{$this->config->item('identity', 'ion_auth')};


        // finally change the password
        $change = $this->ion_auth->reset_password($identity, $this->input->post('new', true));
        if ($change) {
          // if the password was successfully changed
          $this->session->set_flashdata('success', 'Berhasil reset password akun!');
          redirect(site_url('masuk'), 'refresh');
        } else {
          $this->session->set_flashdata('error', 'Gagal reset password akun!');
          redirect('auth/reset_password/' . $code, 'refresh');
        }
      }
    } else {
      $this->session->set_flashdata('error', 'Gagal reset password, Kirim ulang email!');
      redirect(site_url('lupa-password'), 'refresh');
    }
  }

  /**----------------------------------------------------
   * Ubah status menjadi aktif
  -------------------------------------------------------**/
  public function activate($id, $code = FALSE)
  {
    $activation = FALSE;

    if ($code !== FALSE) {
      $activation = $this->ion_auth->activate($id, $code);
      if ($activation) {
        // redirect them to the auth page
        $this->session->set_flashdata('success', 'Berhasil aktivasi sistem!');
        redirect(site_url('masuk'), 'refresh');
      } else {
        // redirect them to the forgot password page
        $this->session->set_flashdata('error', 'Gagal aktivasi pengguna!');
        redirect(site_url('masuk'), 'refresh');
      }
    } else if ($this->ion_auth->is_admin()) {
      $activation = $this->ion_auth->activate($id);
      if ($activation) {
        // redirect them to the auth page
        $this->session->set_flashdata('success', 'Berhasil merubah status pengguna!');
        redirect(site_url('backoffice/pengguna'), 'refresh');
      } else {
        // redirect them to the forgot password page
        $this->session->set_flashdata('error', 'Gagal merubah status pengguna!');
        redirect(site_url('backoffice/pengguna'), 'refresh');
      }
    }
  }

  /**----------------------------------------------------
   * Ubah status menjadi tidak aktif
  -------------------------------------------------------**/
  public function deactivate($id = NULL)
  {
    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
      // redirect them to the home page because they must be an administrator to view this
      $this->session->set_flashdata('warning', 'Halaman tidak ditemukan!');
      return redirect(site_url('backoffice/dasbor'));
    }

    // do we have the right userlevel?
    if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
      $this->session->set_flashdata('success', 'Berhasil merubah status pengguna!');
      $this->ion_auth->deactivate($id);
    }

    // redirect them back to the auth page
    redirect(site_url('backoffice/pengguna'), 'refresh');
  }

  /**
   * @return array A CSRF key-value pair
   */
  public function _get_csrf_nonce()
  {
    $this->load->helper('string');
    $key = random_string('alnum', 8);
    $value = random_string('alnum', 20);
    $this->session->set_flashdata('csrfkey', $key);
    $this->session->set_flashdata('csrfvalue', $value);

    return [$key => $value];
  }

  /**
   * @return bool Whether the posted CSRF token matches
   */
  public function _valid_csrf_nonce()
  {
    $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
    if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
      return TRUE;
    }
    return TRUE;
  }

  /**
   * @param string     $view
   * @param array|null $data
   * @param bool       $returnhtml
   *
   * @return mixed
   */
  public function _render_page($view, $data = NULL, $returnhtml = FALSE) //I think this makes more sense
  {
    $viewdata = (empty($data)) ? $this->data : $data;

    $view_html = $this->load->view($view, $viewdata, $returnhtml);

    // This will return html on 3rd argument being true
    if ($returnhtml) {
      return $view_html;
    }
  }
}
