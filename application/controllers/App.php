<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Jakarta');
        is_admin();
    }

    // Dashboard
    public function index()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => 'active',
            'expanded' => 'true'
        ];
        $data['master'] = [
            'menu' => '',
            'expanded' => 'false',
            'collapse' => ''
        ];
        $data['sub_master'] = [
            'siswa' => '',
            'guru' => ''
        ];
        $data['menu_kelas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_mapel'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_relasi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['guru'] = $this->db->get('guru')->result();
        $data['guru_aktif'] = $this->db->get_where('guru', ['is_active' => 1])->result();
        $data['guru_tidak_aktif'] = $this->db->get_where('guru', ['is_active' => 0])->result();

        $data['siswa'] = $this->db->get('siswa')->result();
        $data['siswa_aktif'] = $this->db->get_where('siswa', ['is_active' => 1])->result();
        $data['siswa_tidak_aktif'] = $this->db->get_where('siswa', ['is_active' => 0])->result();

        $data['kelas'] = $this->db->get('kelas')->result();
        $data['mapel'] = $this->db->get('mapel')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar/admin');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer');
    }

    // START KELAS
    public function kelas()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['master'] = [
            'menu' => '',
            'expanded' => 'false',
            'collapse' => ''
        ];
        $data['sub_master'] = [
            'siswa' => '',
            'guru' => ''
        ];
        $data['menu_kelas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_mapel'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_relasi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['kelas'] = $this->db->get('kelas')->result();

        $this->form_validation->set_rules('nama_kelas[]', 'Nama Kelas', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar/admin', $data);
            $this->load->view('admin/kelas/list', $data);
            $this->load->view('templates/footer');
        } else {
            // Ambil data yang dikirim dari form
            $nama_kelas = $this->input->post('nama_kelas');
            $data_kelas = array();

            $index = 0; // Set index array awal dengan 0
            foreach ($nama_kelas as $nama) { // Kita buat perulangan berdasarkan nama_kelas sampai data terakhir
                array_push($data_kelas, array(
                    'nama_kelas' => $nama,
                ));

                $index++;
            }

            $sql = $this->db->insert_batch('kelas', $data_kelas);

            // Cek apakah query insert nya sukses atau gagal
            if ($sql) { // Jika sukses
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
                redirect('app/kelas');
            } else { // Jika gagal
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
                redirect('app/kelas');
            }
        }
    }

    public function hapus_kelas($id = '')
    {
        $id_kelas = decrypt_url($id);
        $this->db->delete('kelas', ['id_kelas' => $id_kelas]);
        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/kelas');
    }

    public function edit_kelas()
    {
        $id_kelas = $this->input->post('id_kelas');
        $nama_kelas = $this->input->post('nama_kelas');

        $this->db->set('nama_kelas', $nama_kelas);
        $this->db->where('id_kelas', $id_kelas);
        $this->db->update('kelas');

        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'nama kelas diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/kelas');
    }
    // END KELAS

    // START MAPEL
    public function mapel()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['master'] = [
            'menu' => '',
            'expanded' => 'false',
            'collapse' => ''
        ];
        $data['sub_master'] = [
            'siswa' => '',
            'guru' => ''
        ];
        $data['menu_kelas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_mapel'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_relasi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['mapel'] = $this->db->get('mapel')->result();

        $this->form_validation->set_rules('nama_mapel[]', 'Nama Mapel', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar/admin', $data);
            $this->load->view('admin/mapel/list', $data);
            $this->load->view('templates/footer');
        } else {
            // Ambil data yang dikirim dari form
            $nama_mapel = $this->input->post('nama_mapel');
            $data_mapel = array();

            $index = 0; // Set index array awal dengan 0
            foreach ($nama_mapel as $nama) { // Kita buat perulangan berdasarkan nama_mapel sampai data terakhir
                array_push($data_mapel, array(
                    'nama_mapel' => $nama,
                ));

                $index++;
            }

            $sql = $this->db->insert_batch('mapel', $data_mapel);

            // Cek apakah query insert nya sukses atau gagal
            if ($sql) { // Jika sukses
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
                redirect('app/mapel');
            } else { // Jika gagal
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
                redirect('app/mapel');
            }
        }
    }

    public function edit_mapel()
    {
        $id_mapel = $this->input->post('id_mapel');
        $nama_mapel = $this->input->post('nama_mapel');

        $this->db->set('nama_mapel', $nama_mapel);
        $this->db->where('id_mapel', $id_mapel);
        $this->db->update('mapel');

        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'nama mapel diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/mapel');
    }

    public function hapus_mapel($id = '')
    {
        $id_mapel = decrypt_url($id);
        $this->db->delete('mapel', ['id_mapel' => $id_mapel]);
        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/mapel');
    }
    // END MAPEL

    // START SISWA
    public function siswa()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['master'] = [
            'menu' => 'active',
            'expanded' => 'true',
            'collapse' => 'show'
        ];
        $data['sub_master'] = [
            'siswa' => 'active',
            'guru' => ''
        ];
        $data['menu_kelas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_mapel'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_relasi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['siswa'] = $this->db->get('siswa')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $this->form_validation->set_rules('nis[]', 'Nomor Induk', 'required');
        $this->form_validation->set_rules('nama_siswa[]', 'Nama Siswa', 'required');
        $this->form_validation->set_rules('email[]', 'email', 'required|valid_email');
        $this->form_validation->set_rules('jenis_kelamin[]', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('kelas[]', 'Kelas', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar/admin', $data);
            $this->load->view('admin/siswa/list', $data);
            $this->load->view('templates/footer');
        } else {
            // Ambil data yang dikirim dari form
            $nama_siswa = $this->input->post('nama_siswa');
            $data_siswa = array();

            $index = 0; // Set index array awal dengan 0
            foreach ($nama_siswa as $nama) { // Kita buat perulangan berdasarkan nama_siswa sampai data terakhir
                $kelas = $this->db->get_where('kelas', ['id_kelas' => $this->input->post('kelas')[$index]])->row();
                array_push($data_siswa, array(
                    'no_induk_siswa' => $this->input->post('nis')[$index],
                    'nama_siswa' => $nama,
                    'email' => $this->input->post('email')[$index],
                    'password' => password_hash($this->input->post('nis')[$index], PASSWORD_DEFAULT),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin')[$index],
                    'kelas' => $this->input->post('kelas')[$index],
                    'role' => 2,
                    'is_active' => 1,
                    'date_created' => time(),
                    'avatar' => 'default.jpg'
                ));

                // KIRIM EMAIL
                $email_app = $this->db->get('admin')->row();
                $config = [
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://smtp.googlemail.com',
                    'smtp_user' => "$email_app->email",
                    'smtp_pass' => "$email_app->pm",
                    'smtp_port' => 465,
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'newline' => "\r\n"
                ];
                $this->email->initialize($config);

                $this->email->set_newline("\r\n");

                $this->load->library('email', $config);

                $this->email->from("$email_app->email", 'CBT Malela');
                $this->email->to($this->input->post('email')[$index]);

                $this->email->subject('Akun CBT-Email');
                $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $nama . ' <br>
                            <span style="color: #000;">Admin telah menambahkan anda kedalam aplikasi CBT MALELA</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>NAMA</td>
                                <td> : ' . $nama . '</td>
                            </tr>
                            <tr>
                                <td>EMAIL</td>
                                <td> : ' . $this->input->post('email')[$index] . '</td>
                            </tr>
                            <tr>
                                <td>KELAS</td>
                                <td> : ' . $kelas->nama_kelas . '</td>
                            </tr>
                            <tr>
                                <td>PASSWORD</td>
                                <td> : ' . $this->input->post('nis')[$index] . '</td>
                            </tr>
                        </table>
                        <br>
                        <a href="' . base_url('auth') . '"
                            style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">Sign
                            In
                            Now!</a>
                        </div>
                ');

                if (!$this->email->send()) {
                    echo $this->email->print_debugger();
                    die();
                }

                $index++;
            }


            $sql = $this->db->insert_batch('siswa', $data_siswa);

            // Cek apakah query insert nya sukses atau gagal
            if ($sql) { // Jika sukses
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
                redirect('app/siswa?pesan=success');
            } else { // Jika gagal
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
                redirect('app/siswa?pesan=success');
            }
        }
    }

    public function edit_siswa()
    {
        $id_siswa = $this->input->post('id_siswa');
        $email = $this->input->post('email');
        $nama_siswa = $this->input->post('nama_siswa');
        $kelas = $this->input->post('kelas');
        $active = $this->input->post('active');

        $this->db->set('nama_siswa', $nama_siswa);
        $this->db->set('email', $email);
        $this->db->set('kelas', $kelas);
        $this->db->set('is_active', $active);
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa');

        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data siswa diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/siswa');
    }

    public function hapus_siswa($id = '')
    {
        $id_siswa = decrypt_url($id);
        $this->db->delete('siswa', ['id_siswa' => $id_siswa]);
        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/siswa');
    }
    // END SISWA

    // START GURU
    public function guru()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['master'] = [
            'menu' => 'active',
            'expanded' => 'true',
            'collapse' => 'show'
        ];
        $data['sub_master'] = [
            'siswa' => '',
            'guru' => 'active'
        ];
        $data['menu_kelas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_mapel'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_relasi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['siswa'] = $this->db->get('siswa')->result();
        $data['guru'] = $this->db->get('guru')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $this->form_validation->set_rules('nama_guru[]', 'Nama Guru', 'required');
        $this->form_validation->set_rules('email[]', 'email', 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar/admin', $data);
            $this->load->view('admin/guru/list', $data);
            $this->load->view('templates/footer');
        } else {
            // Ambil data yang dikirim dari form
            $nama_guru = $this->input->post('nama_guru');
            $data_guru = array();

            $index = 0; // Set index array awal dengan 0
            foreach ($nama_guru as $nama) { // Kita buat perulangan berdasarkan nama_guru sampai data terakhir
                array_push($data_guru, array(
                    'nama_guru' => $nama,
                    'email' => $this->input->post('email')[$index],
                    'password' => password_hash('123', PASSWORD_DEFAULT),
                    'role' => 3,
                    'is_active' => 1,
                    'date_created' => time(),
                    'avatar' => 'default.jpg'
                ));

                // KIRIM EMAIL
                $email_app = $this->db->get('admin')->row();
                $config = [
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://smtp.googlemail.com',
                    'smtp_user' => "$email_app->email",
                    'smtp_pass' => "$email_app->pm",
                    'smtp_port' => 465,
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'newline' => "\r\n"
                ];
                $this->email->initialize($config);

                $this->email->set_newline("\r\n");

                $this->load->library('email', $config);

                $this->email->from("$email_app->email", 'CBT Malela');
                $this->email->to($this->input->post('email')[$index]);

                $this->email->subject('Akun CBT-Email');
                $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $nama . ' <br>
                            <span style="color: #000;">Admin telah menambahkan anda kedalam aplikasi CBT MALELA</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>NAMA</td>
                                <td style="text-transform: uppercase;"> : ' . $nama . '</td>
                            </tr>
                            <tr>
                                <td>EMAIL</td>
                                <td> : ' . $this->input->post('email')[$index] . '</td>
                            </tr>
                            <tr>
                                <td>ROLE</td>
                                <td> : GURU</td>
                            </tr>
                            <tr>
                                <td>PASSWORD</td>
                                <td> : 123</td>
                            </tr>
                            <tr>
                                <td>STATUS AKUN</td>
                                <td> : AKTIF</td>
                            </tr>
                        </table>
                        <br>
                        <a href="' . base_url('auth') . '"
                            style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">Sign
                            In
                            Now!</a>
                        </div>
                ');

                if (!$this->email->send()) {
                    echo $this->email->print_debugger();
                    die();
                }

                $index++;
            }

            $sql = $this->db->insert_batch('guru', $data_guru);

            // Cek apakah query insert nya sukses atau gagal
            if ($sql) { // Jika sukses
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
                redirect('app/guru?pesan=success');
            } else { // Jika gagal
                $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
                redirect('app/guru?pesan=success');
            }
        }
    }

    public function edit_guru()
    {
        $id_guru = $this->input->post('id_guru');
        $nama_guru = $this->input->post('nama_guru');
        $email = $this->input->post('email');
        $active = $this->input->post('active');

        $this->db->set('nama_guru', $nama_guru);
        $this->db->set('email', $email);
        $this->db->set('is_active', $active);
        $this->db->where('id_guru', $id_guru);
        $this->db->update('guru');

        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data guru diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/guru');
    }

    public function hapus_guru($id = '')
    {
        $id_guru = decrypt_url($id);
        $this->db->delete('guru', ['id_guru' => $id_guru]);
        $this->session->set_flashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        redirect('app/guru');
    }
    // END GURU

    // START RELASI
    public function relasi()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['master'] = [
            'menu' => '',
            'expanded' => 'false',
            'collapse' => ''
        ];
        $data['sub_master'] = [
            'siswa' => '',
            'guru' => ''
        ];
        $data['menu_kelas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_mapel'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_relasi'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['kelas'] = $this->db->get('kelas')->result();
        $data['mapel'] = $this->db->get('mapel')->result();
        $data['guru'] = $this->db->get('guru')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar/admin', $data);
        $this->load->view('admin/guru/relasi-index', $data);
        $this->load->view('templates/footer');
    }

    public function atur_relasi($id = '')
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['master'] = [
            'menu' => '',
            'expanded' => 'false',
            'collapse' => ''
        ];
        $data['sub_master'] = [
            'siswa' => '',
            'guru' => ''
        ];
        $data['menu_kelas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_mapel'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_relasi'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $id_guru = decrypt_url($id);

        $data['kelas'] = $this->db->get('kelas')->result();
        $data['mapel'] = $this->db->get('mapel')->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $id_guru])->row();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar/admin', $data);
        $this->load->view('admin/guru/relasi', $data);
        $this->load->view('templates/footer');
    }
    // END RELASI
}
