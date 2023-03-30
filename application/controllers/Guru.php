<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guru extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Jakarta');
        $this->load->helper('string');
        is_guru();
    }

    // Dashboard
    public function index()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => 'active',
            'expanded' => 'true'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/dashboard');
        $this->load->view('templates/footer');
    }

    public function profile()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];

        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->form_validation->set_rules('nama_guru', 'Guru', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar/guru', $data);
            $this->load->view('guru/profile', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->set('nama_guru', htmlspecialchars($this->input->post('nama_guru')));

            if ($_FILES['avatar']['name']) {

                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']     = '5048';
                $config['upload_path'] = './assets/app-assets/user/';
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('avatar')) {
                    $this->db->set('avatar', $this->upload->data('file_name'));
                    $old_image = $data['guru']->avatar;
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/app-assets/user/' . $old_image);
                    }
                }
            }

            $this->db->where('id_guru', $this->session->userdata('id'));
            $this->db->update('guru');

            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Profile telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/profile');
        }
    }

    public function update_password()
    {
        $guru = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        if (password_verify($this->input->post('current_password'), $guru->password)) {
            $this->db->set('password', password_hash($this->input->post('password'), PASSWORD_DEFAULT));
            $this->db->where('id_guru', $guru->id_guru);
            $this->db->update('guru');
            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Password telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/profile');
        } else {
            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops..',
                            text: 'Current Password Salah',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
            redirect('guru/profile');
        }
    }

    // START MATERI
    public function materi()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['materi'] = $this->db->get_where('materi', ['guru' => $this->session->userdata('id')])->result();
        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->form_validation->set_rules('nama_materi', 'Nama Materi', 'required');
        $this->form_validation->set_rules('mapel', 'Mapel', 'required');
        $this->form_validation->set_rules('kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('text_materi', 'teks materi', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar/guru', $data);
            $this->load->view('guru/materi/list', $data);
            $this->load->view('templates/footer');
        } else {
            $data_materi = [
                'kode_materi' => $this->input->post('kode_materi'),
                'nama_materi' => $this->input->post('nama_materi'),
                'guru' => $this->session->userdata('id'),
                'mapel' => $this->input->post('mapel'),
                'kelas' => $this->input->post('kelas'),
                'text_materi' => htmlspecialchars($this->input->post('text_materi', true)),
                'date_created' => time()
            ];
            $mapel = $this->db->get_where('mapel', ['id_mapel' => $this->input->post('mapel')])->row();

            $siswa = $this->db->get_where('siswa', ['kelas' => $this->input->post('kelas')])->result();

            if (count($siswa) == 0) {
                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops!',
                            text: 'Belum ada siswa dikelas ini',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
                redirect('guru/materi');
            }

            // Kirim Email Ke siswa
            $email_app = $this->db->get('admin')->row();
            foreach ($siswa as $s) {
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
                $this->email->to($s->email);

                $this->email->subject('Materi');
                $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $s->nama_siswa . ' <br>
                            <span style="color: #000;">' . $this->session->userdata('nama') . ' memposting materi baru</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Material</td>
                                <td> : ' . $this->input->post('nama_materi') . '</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td>
                                <td> : ' . $mapel->nama_mapel . '</td>
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
            }
            $this->db->insert('materi', $data_materi);

            if ($_FILES['file_materi']) {

                // Hitung Jumlah File/Gambar yang dipilih
                $jumlahData = count($_FILES['file_materi']['name']);

                // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
                for ($i = 0; $i < $jumlahData; $i++) :

                    // Inisialisasi Nama,Tipe,Dll.
                    $_FILES['file']['name']     = $_FILES['file_materi']['name'][$i];
                    $_FILES['file']['type']     = $_FILES['file_materi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['file_materi']['tmp_name'][$i];
                    $_FILES['file']['size']     = $_FILES['file_materi']['size'][$i];

                    // Konfigurasi Upload
                    $config['upload_path']          = './assets/app-assets/file/';
                    $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|ppt|pptx|rar|zip|xlsx|php|js|html|css|txt|iso';

                    // Memanggil Library Upload dan Setting Konfigurasi
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                        $fileData = $this->upload->data(); // Lakukan Upload Data

                        // Membuat Variable untuk dimasukkan ke Database
                        $uploadData[$i]['kode_file'] = $this->input->post('kode_materi');
                        $uploadData[$i]['nama_file'] = $fileData['file_name'];
                    }

                endfor; // Penutup For

                if ($uploadData !== null) { // Jika Berhasil Upload
                    $this->db->insert_batch('file', $uploadData);
                }
            }

            if ($_FILES['video_materi']) {
                // Hitung Jumlah File/Gambar yang dipilih
                $jumlahData_video = count($_FILES['video_materi']['name']);

                // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
                for ($i = 0; $i < $jumlahData_video; $i++) :

                    // Inisialisasi Nama,Tipe,Dll.
                    $_FILES['file']['name']     = $_FILES['video_materi']['name'][$i];
                    $_FILES['file']['type']     = $_FILES['video_materi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['video_materi']['tmp_name'][$i];
                    $_FILES['file']['size']     = $_FILES['video_materi']['size'][$i];

                    // Konfigurasi Upload
                    $config_video['upload_path']          = './assets/app-assets/file/';
                    $config_video['allowed_types']        = 'mp4|MP4|3gp|3GP|avi|AVI|mkv|MKV|mpeg|MPEG|wmp|WMP';

                    // Memanggil Library Upload dan Setting Konfigurasi
                    $this->load->library('upload', $config_video);
                    $this->upload->initialize($config_video);

                    if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                        $fileData_video = $this->upload->data(); // Lakukan Upload Data

                        // Membuat Variable untuk dimasukkan ke Database
                        $uploadData_video[$i]['kode_file'] = $this->input->post('kode_materi');
                        $uploadData_video[$i]['nama_file'] = $fileData_video['file_name'];
                    }

                endfor; // Penutup For

                if ($uploadData_video !== null) { // Jika Berhasil Upload
                    $this->db->insert_batch('file', $uploadData_video);
                }
            }

            $siswa_materi = [];
            $index_siswa_materi = 0;
            foreach ($siswa as $s2) {
                array_push($siswa_materi, array(
                    'materi' => $this->input->post('kode_materi'),
                    'kelas' => $this->input->post('kelas'),
                    'mapel' => $this->input->post('mapel'),
                    'siswa' => $s2->id_siswa,
                    'dilihat' => 0
                ));
            }

            $this->db->insert_batch('materi_siswa', $siswa_materi);

            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Materi telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/materi?pesan=success');
        }
    }

    public function lihat_materi($data_id_materi, $data_id_guru)
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $materi_where = [
            'id_materi' => decrypt_url($data_id_materi),
            'guru' => decrypt_url($data_id_guru)
        ];
        $data['materi'] = $this->db->get_where('materi', $materi_where)->row();
        $data['mapel'] = $this->db->get_where('mapel', ['id_mapel' => $data['materi']->mapel])->row();
        $data['kelas'] = $this->db->get_where('kelas', ['id_kelas' => $data['materi']->kelas])->row();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $data['materi']->guru])->row();

        $data['file'] = $this->db->get_where('file', ['kode_file' => $data['materi']->kode_materi])->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/materi/lihat-materi', $data);
        $this->load->view('templates/footer');
    }

    public function edit_materi()
    {
        $kode_materi = $this->input->post('e_kode_materi');

        $data = [
            'nama_materi' => $this->input->post('e_nama_materi'),
            'mapel' => $this->input->post('e_mapel'),
            'kelas' => $this->input->post('e_kelas'),
            'text_materi' => $this->input->post('e_text_materi'),
        ];

        $this->db->where('kode_materi', $kode_materi);
        $this->db->update('materi', $data);

        if ($_FILES['e_file_materi']) {

            // Hitung Jumlah File/Gambar yang dipilih
            $jumlahData = count($_FILES['e_file_materi']['name']);

            // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
            for ($i = 0; $i < $jumlahData; $i++) :

                // Inisialisasi Nama,Tipe,Dll.
                $_FILES['file']['name']     = $_FILES['e_file_materi']['name'][$i];
                $_FILES['file']['type']     = $_FILES['e_file_materi']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['e_file_materi']['tmp_name'][$i];
                $_FILES['file']['size']     = $_FILES['e_file_materi']['size'][$i];

                // Konfigurasi Upload
                $config['upload_path']          = './assets/app-assets/file/';
                $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|ppt|pptx|rar|zip|xlsx|php|js|html|css|txt|iso';

                // Memanggil Library Upload dan Setting Konfigurasi
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                    $fileData = $this->upload->data(); // Lakukan Upload Data

                    // Membuat Variable untuk dimasukkan ke Database
                    $uploadData[$i]['kode_file'] = $kode_materi;
                    $uploadData[$i]['nama_file'] = $fileData['file_name'];
                }

            endfor; // Penutup For

            if ($uploadData !== null) { // Jika Berhasil Upload
                $this->db->insert_batch('file', $uploadData);
            }
        }

        if ($_FILES['e_video_materi']) {
            // Hitung Jumlah File/Gambar yang dipilih
            $jumlahData = count($_FILES['e_video_materi']['name']);

            // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
            for ($i = 0; $i < $jumlahData; $i++) :

                // Inisialisasi Nama,Tipe,Dll.
                $_FILES['file']['name']     = $_FILES['e_video_materi']['name'][$i];
                $_FILES['file']['type']     = $_FILES['e_video_materi']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['e_video_materi']['tmp_name'][$i];
                $_FILES['file']['size']     = $_FILES['e_video_materi']['size'][$i];

                // Konfigurasi Upload
                $config['upload_path']          = './assets/app-assets/file/';
                $config['allowed_types']        = 'mp4|MP4|3gp|3GP|avi|AVI|mkv|MKV|mpeg|MPEG|wmp|WMP';

                // Memanggil Library Upload dan Setting Konfigurasi
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                    $fileData = $this->upload->data(); // Lakukan Upload Data

                    // Membuat Variable untuk dimasukkan ke Database
                    $uploadData[$i]['kode_file'] = $kode_materi;
                    $uploadData[$i]['nama_file'] = $fileData['file_name'];
                }

            endfor; // Penutup For

            if ($uploadData !== null) { // Jika Berhasil Upload
                $this->db->insert_batch('file', $uploadData);
            }
        }

        $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Materi telah diupdate',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        redirect('guru/materi');
    }

    public function hapus_materi($data_id_materi, $data_id_guru)
    {
        $materi_where = [
            'id_materi' => decrypt_url($data_id_materi),
            'guru' => decrypt_url($data_id_guru)
        ];
        $materi = $this->db->get_where('materi', $materi_where)->row();

        $this->db->delete('materi', $materi_where);
        $this->db->delete('chat_materi', ['materi' => $materi->kode_materi]);

        $this->db->delete('materi_siswa', ['materi' => $materi->kode_materi]);

        $file = $this->db->get_where('file', ['kode_file' => $materi->kode_materi])->result();

        foreach ($file as $f) {
            if ($f->kode_file == $materi->kode_materi) {
                unlink(FCPATH . 'assets/app-assets/file/' . $f->nama_file);
                $this->db->delete('file', ['kode_file' => $materi->kode_materi]);
            }
        }

        $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Materi telah dihapus',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        redirect('guru/materi');
    }

    // END MATERI

    public function tugas()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['tugas'] = $this->db->get_where('tugas', ['guru' => $this->session->userdata('id')])->result();
        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->form_validation->set_rules('nama_tugas', 'Nama Tugas', 'required');
        $this->form_validation->set_rules('mapel', 'Mapel', 'required');
        $this->form_validation->set_rules('kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar/guru', $data);
            $this->load->view('guru/tugas/list', $data);
            $this->load->view('templates/footer');
        } else {
            $data_tugas = [
                'kode_tugas' => $this->input->post('kode_tugas'),
                'kelas' => $this->input->post('kelas'),
                'mapel' => $this->input->post('mapel'),
                'guru' => $this->session->userdata('id'),
                'nama_tugas' => $this->input->post('nama_tugas'),
                'deskripsi' => htmlspecialchars($this->input->post('deskripsi', true)),
                'date_created' => time(),
                'due_date' => $this->input->post('tgl') . ' ' . $this->input->post('jam')
            ];
            $mapel = $this->db->get_where('mapel', ['id_mapel' => $this->input->post('mapel')])->row();

            $siswa = $this->db->get_where('siswa', ['kelas' => $this->input->post('kelas')])->result();
            if (count($siswa) == 0) {
                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops!',
                            text: 'Belum ada siswa dikelas ini',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
                redirect('guru/tugas');
            }
            $data_siswa = [];
            $index_siswa = 0;

            // Kirim Email Ke siswa
            $email_app = $this->db->get('admin')->row();
            foreach ($siswa as $s) {
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
                $this->email->to($s->email);

                $this->email->subject('Tugas');
                $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $s->nama_siswa . ' <br>
                            <span style="color: #000;">' . $this->session->userdata('nama') . ' memposting Tugas baru</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Tugas</td>
                                <td> : ' . $this->input->post('nama_tugas') . '</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td>
                                <td> : ' . $mapel->nama_mapel . '</td>
                            </tr>
                            <tr>
                                <td>Due Date</td>
                                <td> : ' . $data_tugas['due_date'] . '</td>
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

                array_push($data_siswa, array(
                    'tugas' => $this->input->post('kode_tugas'),
                    'siswa' => $s->id_siswa
                ));

                $index_siswa++;
            }
            $this->db->insert('tugas', $data_tugas);
            $this->db->insert_batch('tugas_siswa', $data_siswa);

            if ($_FILES['file_materi']) {

                // Hitung Jumlah File/Gambar yang dipilih
                $jumlahData = count($_FILES['file_materi']['name']);

                // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
                for (
                    $i = 0;
                    $i < $jumlahData;
                    $i++
                ) :

                    // Inisialisasi Nama,Tipe,Dll.
                    $_FILES['file']['name']     = $_FILES['file_materi']['name'][$i];
                    $_FILES['file']['type']     = $_FILES['file_materi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['file_materi']['tmp_name'][$i];
                    $_FILES['file']['size']     = $_FILES['file_materi']['size'][$i];

                    // Konfigurasi Upload
                    $config['upload_path']          = './assets/app-assets/file/';
                    $config['allowed_types']        = 'jpeg|gif|jpg|png|pdf|doc|docx|ppt|pptx|rar|zip|xlsx|php|js|html|css|txt|iso';

                    // Memanggil Library Upload dan Setting Konfigurasi
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                        $fileData = $this->upload->data(); // Lakukan Upload Data

                        // Membuat Variable untuk dimasukkan ke Database
                        $uploadData[$i]['kode_file'] = $this->input->post('kode_tugas');
                        $uploadData[$i]['nama_file'] = $fileData['file_name'];
                    }

                endfor; // Penutup For

                if ($uploadData !== null) { // Jika Berhasil Upload
                    $this->db->insert_batch('file', $uploadData);
                }
            }

            if ($_FILES['video_materi']) {
                // Hitung Jumlah File/Gambar yang dipilih
                $jumlahData_video = count($_FILES['video_materi']['name']);

                // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
                for ($i = 0; $i < $jumlahData_video; $i++) :

                    // Inisialisasi Nama,Tipe,Dll.
                    $_FILES['file']['name']     = $_FILES['video_materi']['name'][$i];
                    $_FILES['file']['type']     = $_FILES['video_materi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['video_materi']['tmp_name'][$i];
                    $_FILES['file']['size']     = $_FILES['video_materi']['size'][$i];

                    // Konfigurasi Upload
                    $config_video['upload_path']          = './assets/app-assets/file/';
                    $config_video['allowed_types']        = 'mp4|MP4|3gp|3GP|avi|AVI|mkv|MKV|mpeg|MPEG|wmp|WMP';

                    // Memanggil Library Upload dan Setting Konfigurasi
                    $this->load->library('upload', $config_video);
                    $this->upload->initialize($config_video);

                    if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                        $fileData_video = $this->upload->data(); // Lakukan Upload Data

                        // Membuat Variable untuk dimasukkan ke Database
                        $uploadData_video[$i]['kode_file'] = $this->input->post('kode_tugas');
                        $uploadData_video[$i]['nama_file'] = $fileData_video['file_name'];
                    }

                endfor; // Penutup For

                if ($uploadData_video !== null) { // Jika Berhasil Upload
                    $this->db->insert_batch('file', $uploadData_video);
                }
            }
            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Tugas telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/tugas?pesan=success');
        }
    }

    public function edit_tugas()
    {
        $kode_tugas = $this->input->post('e_kode_tugas');

        $data = [
            'nama_tugas' => $this->input->post('e_nama_tugas'),
            'mapel' => $this->input->post('e_mapel'),
            'kelas' => $this->input->post('e_kelas'),
            'deskripsi' => htmlspecialchars($this->input->post('e_deskripsi')),
            'due_date' => $this->input->post('e_tgl') . ' ' . $this->input->post('e_jam')
        ];

        $this->db->where('kode_tugas', $kode_tugas);
        $this->db->update('tugas', $data);

        if ($_FILES['e_file_materi']) {

            // Hitung Jumlah File/Gambar yang dipilih
            $jumlahData = count($_FILES['e_file_materi']['name']);

            // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
            for ($i = 0; $i < $jumlahData; $i++) :

                // Inisialisasi Nama,Tipe,Dll.
                $_FILES['file']['name']     = $_FILES['e_file_materi']['name'][$i];
                $_FILES['file']['type']     = $_FILES['e_file_materi']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['e_file_materi']['tmp_name'][$i];
                $_FILES['file']['size']     = $_FILES['e_file_materi']['size'][$i];

                // Konfigurasi Upload
                $config['upload_path']          = './assets/app-assets/file/';
                $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|ppt|pptx|rar|zip|xlsx|php|js|html|css|txt|iso';

                // Memanggil Library Upload dan Setting Konfigurasi
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                    $fileData = $this->upload->data(); // Lakukan Upload Data

                    // Membuat Variable untuk dimasukkan ke Database
                    $uploadData[$i]['kode_file'] = $kode_tugas;
                    $uploadData[$i]['nama_file'] = $fileData['file_name'];
                }

            endfor; // Penutup For

            if ($uploadData !== null) { // Jika Berhasil Upload
                $this->db->insert_batch('file', $uploadData);
            }
        }

        if ($_FILES['e_video_materi']) {
            // Hitung Jumlah File/Gambar yang dipilih
            $jumlahData = count($_FILES['e_video_materi']['name']);

            // Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
            for ($i = 0; $i < $jumlahData; $i++) :

                // Inisialisasi Nama,Tipe,Dll.
                $_FILES['file']['name']     = $_FILES['e_video_materi']['name'][$i];
                $_FILES['file']['type']     = $_FILES['e_video_materi']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['e_video_materi']['tmp_name'][$i];
                $_FILES['file']['size']     = $_FILES['e_video_materi']['size'][$i];

                // Konfigurasi Upload
                $config['upload_path']          = './assets/app-assets/file/';
                $config['allowed_types']        = 'mp4|MP4|3gp|3GP|avi|AVI|mkv|MKV|mpeg|MPEG|wmp|WMP';

                // Memanggil Library Upload dan Setting Konfigurasi
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) { // Jika Berhasil Upload

                    $fileData = $this->upload->data(); // Lakukan Upload Data

                    // Membuat Variable untuk dimasukkan ke Database
                    $uploadData[$i]['kode_file'] = $kode_tugas;
                    $uploadData[$i]['nama_file'] = $fileData['file_name'];
                }

            endfor; // Penutup For

            if ($uploadData !== null) { // Jika Berhasil Upload
                $this->db->insert_batch('file', $uploadData);
            }
        }

        $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Tugas telah diupdate',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        redirect('guru/tugas');
    }

    public function lihat_tugas($data_id_tugas, $data_id_guru)
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $tugas_where = [
            'id_tugas' => decrypt_url($data_id_tugas),
            'guru' => decrypt_url($data_id_guru)
        ];
        $data['tugas'] = $this->db->get_where('tugas', $tugas_where)->row();
        $data['mapel'] = $this->db->get_where('mapel', ['id_mapel' => $data['tugas']->mapel])->row();
        $data['kelas'] = $this->db->get_where('kelas', ['id_kelas' => $data['tugas']->kelas])->row();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $data['tugas']->guru])->row();

        $data['file'] = $this->db->get_where('file', ['kode_file' => $data['tugas']->kode_tugas])->result();
        $data['tugas_siswa'] = $this->db->get_where('tugas_siswa', ['tugas' => $data['tugas']->kode_tugas])->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/tugas/lihat-tugas', $data);
        $this->load->view('templates/footer');
    }

    public function tugas_siswa($data_tugas, $data_siswa)
    {
        $kode_tugas = decrypt_url($data_tugas);
        $id_siswa = decrypt_url($data_siswa);

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['siswa'] = $this->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
        $data['tugas_siswa'] = $this->db->get_where('tugas_siswa', ['tugas' => $kode_tugas, 'siswa' => $id_siswa])->row();
        $data['tugas'] = $this->db->get_where('tugas', ['kode_tugas' => $kode_tugas])->row();
        $data['file'] = $this->db->get_where('file', ['kode_file' => $data['tugas_siswa']->file_siswa])->result();

        $this->form_validation->set_rules('tugas', 'Tugas', 'required');
        $this->form_validation->set_rules('siswa', 'Siswa', 'required');
        $this->form_validation->set_rules('nilai', 'Nilai', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar/guru', $data);
            $this->load->view('guru/tugas/tugas-siswa', $data);
            $this->load->view('templates/footer');
        } else {
            $tugas = $this->db->get_where('tugas', ['kode_tugas' => $this->input->post('tugas')])->row();
            $this->db->set('nilai', $this->input->post('nilai'));
            $this->db->set('catatan_guru', htmlspecialchars($this->input->post('catatan_guru')));
            $this->db->where('tugas', $this->input->post('tugas'));
            $this->db->where('siswa', $this->input->post('siswa'));
            $this->db->update('tugas_siswa');

            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Tugas telah Dinilai',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/lihat_tugas/' .  encrypt_url($tugas->id_tugas) . '/' . encrypt_url($this->session->userdata('id')));
        }
    }

    public function hapus_tugas($data_id_tugas, $data_id_guru)
    {
        $tugas_where = [
            'id_tugas' => decrypt_url($data_id_tugas),
            'guru' => decrypt_url($data_id_guru)
        ];
        $tugas = $this->db->get_where('tugas', $tugas_where)->row();

        $file = $this->db->get_where('file', ['kode_file' => $tugas->kode_tugas])->result();

        foreach ($file as $f) {
            if ($f->kode_file == $tugas->kode_tugas) {
                unlink(FCPATH . 'assets/app-assets/file/' . $f->nama_file);
                $this->db->delete('file', ['kode_file' => $tugas->kode_tugas]);
            }
        }

        $this->db->delete('tugas', $tugas_where);
        $this->db->delete('chat_tugas', ['tugas' => $tugas->kode_tugas]);

        $tugas_siswa = $this->db->get_where('tugas_siswa', ['tugas' => $tugas->kode_tugas])->result();

        foreach ($tugas_siswa as $ts) {
            $file_siswa = $this->db->get_where('file', ['kode_file' => $ts->file_siswa])->row();
            if ($file_siswa) {
                if ($file_siswa->kode_file == $ts->file_siswa) {
                    unlink(FCPATH . 'assets/app-assets/file/' . $file_siswa->nama_file);
                    $this->db->delete('file', ['kode_file' => $ts->file_siswa]);
                }
            }
        }

        $this->db->delete('tugas_siswa', ['tugas' => $tugas->kode_tugas]);

        $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Tugas telah dihapus',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        redirect('guru/tugas');
    }

    // UJIAN =====================================================

    public function ujian()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->db->get_where('ujian', ['guru' => $this->session->userdata('id')])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/ujian/list', $data);
        $this->load->view('templates/footer');
    }
    // PILIHAN GANDA
    public function tambah_pg()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->db->get_where('ujian', ['guru' => $this->session->userdata('id')])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->form_validation->set_rules('nama_ujian', 'Nama Ujian', 'required');
        $this->form_validation->set_rules('kelas', 'kelas', 'required');
        $this->form_validation->set_rules('mapel', 'Mapel', 'required');
        $this->form_validation->set_rules('tgl_mulai', 'tgl mulai', 'required');
        $this->form_validation->set_rules('jam_mulai', 'jam mulai', 'required');
        $this->form_validation->set_rules('tgl_berakhir', 'tgl berakhir', 'required');
        $this->form_validation->set_rules('jam_berakhir', 'jam berakhir', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar/guru', $data);
            $this->load->view('guru/ujian/pg-tambah');
            $this->load->view('templates/footer');
        } else {
            $siswa = $this->db->get_where('siswa', ['kelas' => $this->input->post('kelas')])->result();
            if (count($siswa) == 0) {
                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops!',
                            text: 'Belum ada siswa dikelas ini',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
                redirect('guru/ujian');
            }
            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'nama_ujian' => $this->input->post('nama_ujian'),
                'guru' => $this->session->userdata('id'),
                'kelas' => $this->input->post('kelas'),
                'mapel' => $this->input->post('mapel'),
                'date_created' => time(),
                'waktu_mulai' => $this->input->post('tgl_mulai') . ' ' . $this->input->post('jam_mulai'),
                'waktu_berakhir' => $this->input->post('tgl_berakhir') . ' ' . $this->input->post('jam_berakhir'),
            ];
            // END DATA UJIAN


            // DATA DETAIL UJIAN PG
            $nama_soal = $this->input->post('nama_soal');
            $data_detail_ujian = array();
            $index = 0;
            foreach ($nama_soal as $nama) {
                array_push($data_detail_ujian, array(
                    'kode_ujian' => $kode_ujian,
                    'nama_soal' => $nama,
                    'pg_1' => 'A. ' . $this->input->post('pg_1')[$index],
                    'pg_2' => 'B. ' . $this->input->post('pg_2')[$index],
                    'pg_3' => 'C. ' . $this->input->post('pg_3')[$index],
                    'pg_4' => 'D. ' . $this->input->post('pg_4')[$index],
                    'pg_5' => 'E. ' . $this->input->post('pg_5')[$index],
                    'jawaban' => $this->input->post('jawaban')[$index],
                ));

                $index++;
            }
            // END DATA DETAIL UJIAN PG

            // Kirim Email Ke siswa
            $mapel = $this->db->get_where('mapel', ['id_mapel' => $this->input->post('mapel')])->row();
            $email_app = $this->db->get('admin')->row();
            foreach ($siswa as $s) {
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
                $this->email->to($s->email);

                $this->email->subject('Ujian');
                $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $s->nama_siswa . ' <br>
                            <span style="color: #000;">' . $data["guru"]->nama_guru . ' memposting Ujian baru</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Ujian</td>
                                <td> : ' . $this->input->post('nama_ujian') . '</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td>
                                <td> : ' . $mapel->nama_mapel . '</td>
                            </tr>
                            <tr>
                                <td>Mulai</td>
                                <td> : ' . $data_ujian['waktu_mulai'] . '</td>
                            </tr>
                            <tr>
                                <td>Berakhir</td>
                                <td> : ' . $data_ujian['waktu_berakhir'] . '</td>
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
            }

            $this->db->insert('ujian', $data_ujian);
            $this->db->insert_batch('ujian_detail', $data_detail_ujian);

            $ujian_detail = $this->db->get_where('ujian_detail', ['kode_ujian' => $kode_ujian])->result();
            $data_ujian_siswa = [];
            foreach ($ujian_detail as $uj) {
                foreach ($siswa as $su) {
                    array_push($data_ujian_siswa, [
                        'ujian_id' => $uj->id_detail_ujian,
                        'ujian' => $uj->kode_ujian,
                        'siswa' => $su->id_siswa,
                    ]);
                }
            }

            $this->db->insert_batch('ujian_siswa', $data_ujian_siswa);

            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/ujian?pesan=success');
        }
    }

    public function excel_pg()
    {
        $data['ujian'] = $this->db->get_where('ujian', ['guru' => $this->session->userdata('id')])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->form_validation->set_rules('e_nama_ujian', 'Nama Ujian', 'required');
        $this->form_validation->set_rules('e_kelas', 'kelas', 'required');
        $this->form_validation->set_rules('e_mapel', 'Mapel', 'required');
        $this->form_validation->set_rules('e_tgl_mulai', 'tgl mulai', 'required');
        $this->form_validation->set_rules('e_jam_mulai', 'jam mulai', 'required');
        $this->form_validation->set_rules('e_tgl_berakhir', 'tgl berakhir', 'required');
        $this->form_validation->set_rules('e_jam_berakhir', 'jam berakhir', 'required');

        if ($this->form_validation->run() == false) {
            $this->tambah_pg();
        } else {

            $siswa = $this->db->get_where('siswa', ['kelas' => $this->input->post('e_kelas')])->result();
            if (count($siswa) == 0) {
                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops!',
                            text: 'Belum ada siswa dikelas ini',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
                redirect('guru/ujian');
            }
            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'nama_ujian' => $this->input->post('e_nama_ujian'),
                'guru' => $this->session->userdata('id'),
                'kelas' => $this->input->post('e_kelas'),
                'mapel' => $this->input->post('e_mapel'),
                'date_created' => time(),
                'waktu_mulai' => $this->input->post('e_tgl_mulai') . ' ' . $this->input->post('e_jam_mulai'),
                'waktu_berakhir' => $this->input->post('e_tgl_berakhir') . ' ' . $this->input->post('e_jam_berakhir')
            ];

            // var_dump($data_ujian);
            // die;

            $fileName = $_FILES['excel']['name'];

            $config['allowed_types'] = 'xls|xlsx|csv';
            $config['max_size']     = '5048';
            $config['upload_path'] = './assets/app-assets/file-excel/';
            $config['remove_spaces'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('excel')) {
                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops..!',
                            text: 'Gagal Upload Excel',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
                redirect('guru/tambah_pg');
            } else {
                $inputFileName = './assets/app-assets/file-excel/' . $this->upload->data('file_name');

                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);

                $sheet = $objPHPExcel->getSheet(0);
                $higestRow = $sheet->getHighestRow();
                $higestColumn = $sheet->getHighestColumn();

                $data_detail_ujian = array();
                for ($row = 2; $row <= $higestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $higestColumn . $row, NULL, TRUE, FALSE);
                    array_push($data_detail_ujian, array(
                        'kode_ujian' => $kode_ujian,
                        'nama_soal' => $rowData[0][0],
                        'pg_1' => 'A. ' . $rowData[0][1],
                        'pg_2' => 'B. ' . $rowData[0][2],
                        'pg_3' => 'C. ' . $rowData[0][3],
                        'pg_4' => 'D. ' . $rowData[0][4],
                        'pg_5' => 'E. ' . $rowData[0][5],
                        'jawaban' => $rowData[0][6],
                    ));
                }

                // Kirim Email Ke siswa
                $mapel = $this->db->get_where('mapel', ['id_mapel' => $this->input->post('e_mapel')])->row();
                $email_app = $this->db->get('admin')->row();
                foreach ($siswa as $s) {
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
                    $this->email->to($s->email);

                    $this->email->subject('Ujian');
                    $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $s->nama_siswa . ' <br>
                            <span style="color: #000;">' . $data["guru"]->nama_guru . ' memposting Ujian baru</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Ujian</td>
                                <td> : ' . $this->input->post('e_nama_ujian') . '</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td>
                                <td> : ' . $mapel->nama_mapel . '</td>
                            </tr>
                            <tr>
                                <td>Mulai</td>
                                <td> : ' . $data_ujian['waktu_mulai'] . '</td>
                            </tr>
                            <tr>
                                <td>Berakhir</td>
                                <td> : ' . $data_ujian['waktu_berakhir'] . '</td>
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
                }
                $this->db->insert('ujian', $data_ujian);
                $this->db->insert_batch('ujian_detail', $data_detail_ujian);
                $ujian_detail = $this->db->get_where('ujian_detail', ['kode_ujian' => $kode_ujian])->result();
                $data_ujian_siswa = [];
                foreach ($ujian_detail as $uj) {
                    foreach ($siswa as $su) {
                        array_push($data_ujian_siswa, [
                            'ujian_id' => $uj->id_detail_ujian,
                            'ujian' => $uj->kode_ujian,
                            'siswa' => $su->id_siswa,
                        ]);
                    }
                }

                $this->db->insert_batch('ujian_siswa', $data_ujian_siswa);

                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
                redirect('guru/ujian?pesan=success');
            }
        }
    }

    // LIHAT UJIAN PILIHAN GANDA
    public function lihat_ujian($data_id_ujian, $data_id_guru)
    {
        $id_ujian = decrypt_url($data_id_ujian);
        $id_guru = decrypt_url($data_id_guru);

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->db->get_where('ujian', ['id_ujian' => $id_ujian, 'guru' => $id_guru])->row();
        $data['detail_ujian'] = $this->db->get_where('ujian_detail', ['kode_ujian' => $data['ujian']->kode_ujian])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();
        $data['siswa'] = $this->db->get_where('siswa', ['kelas' => $data['ujian']->kelas])->result();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/ujian/pg-lihat', $data);
        $this->load->view('templates/footer');
    }

    public function pg_siswa($data_id_siswa, $data_kode_ujian)
    {
        $id_siswa = decrypt_url($data_id_siswa);
        $kode_ujian = decrypt_url($data_kode_ujian);

        $ujian_siswa = $this->db->get_where('ujian_siswa', ['ujian' => $kode_ujian, 'siswa' => $id_siswa])->result();
        $ujian = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian])->row();

        if (count($ujian_siswa) <= 0) {
            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Warning!',
                            text: 'Siswa ini tidak bisa mengikuti ujian dikarenakan akun terdaftar setelah ujian dibuat',
                            type: 'warning',
                            padding: '2em'
                            });
                        ");
            $url = 'guru/lihat_ujian/' . encrypt_url($ujian->id_ujian) . '/' . encrypt_url($this->session->userdata('id'));
            redirect($url);
        }

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $siswa = $this->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
        $data['siswa'] = $this->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
        $data['ujian'] = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian, 'kelas' => $data['siswa']->kelas])->row();

        $data['detail_ujian'] = $this->db->get_where('ujian_detail', ['kode_ujian' => $data['ujian']->kode_ujian])->result();

        // $data['ujian_siswa'] = $this->db->get_where('ujian_siswa', ['ujian' => $data['ujian']->kode_ujian, 'siswa' => $siswa->id_siswa])->result();
        $data['jawaban_benar'] = $this->db->get_where('ujian_siswa', ['ujian' => $data['ujian']->kode_ujian, 'siswa' => $siswa->id_siswa, 'benar' => 1])->result();
        $data['jawaban_salah'] = $this->db->get_where('ujian_siswa', ['ujian' => $data['ujian']->kode_ujian, 'siswa' => $siswa->id_siswa, 'benar' => 0])->result();
        $data['tidak_dijawab'] = $this->db->get_where('ujian_siswa', ['ujian' => $data['ujian']->kode_ujian, 'siswa' => $siswa->id_siswa, 'benar' => 2])->result();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/ujian/pg-siswa', $data);
        $this->load->view('templates/footer');
    }

    // ESSAY
    public function tambah_essay()
    {
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->db->get_where('ujian', ['guru' => $this->session->userdata('id')])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->form_validation->set_rules('nama_ujian', 'Nama Ujian', 'required');
        $this->form_validation->set_rules('kelas', 'kelas', 'required');
        $this->form_validation->set_rules('mapel', 'Mapel', 'required');
        $this->form_validation->set_rules('tgl_mulai', 'tgl mulai', 'required');
        $this->form_validation->set_rules('jam_mulai', 'jam mulai', 'required');
        $this->form_validation->set_rules('tgl_berakhir', 'tgl berakhir', 'required');
        $this->form_validation->set_rules('jam_berakhir', 'jam berakhir', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar/guru', $data);
            $this->load->view('guru/ujian/essay-tambah');
            $this->load->view('templates/footer');
        } else {


            $siswa = $this->db->get_where('siswa', ['kelas' => $this->input->post('kelas')])->result();
            if (count($siswa) == 0) {
                $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Oops!',
                            text: 'Belum ada siswa dikelas ini',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
                redirect('guru/ujian');
            }
            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'nama_ujian' => $this->input->post('nama_ujian'),
                'guru' => $this->session->userdata('id'),
                'kelas' => $this->input->post('kelas'),
                'mapel' => $this->input->post('mapel'),
                'date_created' => time(),
                'waktu_mulai' => $this->input->post('tgl_mulai') . ' ' . $this->input->post('jam_mulai'),
                'waktu_berakhir' => $this->input->post('tgl_berakhir') . ' ' . $this->input->post('jam_berakhir'),
                'jenis_ujian' => 1
            ];
            // END DATA UJIAN


            // DATA DETAIL UJIAN ESSAY
            $nama_soal = $this->input->post('soal');
            $data_detail_essay = array();
            $index = 0;
            foreach ($nama_soal as $nama) {
                array_push($data_detail_essay, array(
                    'kode_ujian' => $kode_ujian,
                    'soal' => $nama,
                ));

                $index++;
            }

            // END DATA DETAIL UJIAN ESSAY

            // Kirim Email Ke siswa
            $mapel = $this->db->get_where('mapel', ['id_mapel' => $this->input->post('mapel')])->row();
            $email_app = $this->db->get('admin')->row();
            foreach ($siswa as $s) {
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
                $this->email->to($s->email);

                $this->email->subject('Ujian');
                $this->email->message('
                    <div style="color: #000; padding: 10px;">
                        <div
                            style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                            CBT Malela</div>
                        <small style="color: #000;">V 1.0 by Abduloh</small>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $s->nama_siswa . ' <br>
                            <span style="color: #000;">' . $data["guru"]->nama_guru . ' memposting Ujian baru</span></p>
                        <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Ujian</td>
                                <td> : ' . $this->input->post('nama_ujian') . '</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td>
                                <td> : ' . $mapel->nama_mapel . '</td>
                            </tr>
                            <tr>
                                <td>Mulai</td>
                                <td> : ' . $data_ujian['waktu_mulai'] . '</td>
                            </tr>
                            <tr>
                                <td>Berakhir</td>
                                <td> : ' . $data_ujian['waktu_berakhir'] . '</td>
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
            }

            $this->db->insert('ujian', $data_ujian);
            $this->db->insert_batch('essay_detail', $data_detail_essay);

            $essay_detail = $this->db->get_where('essay_detail', ['kode_ujian' => $kode_ujian])->result();
            $data_ujian_siswa = [];
            foreach ($essay_detail as $ed) {
                foreach ($siswa as $su) {
                    array_push($data_ujian_siswa, [
                        'essay_id' => $ed->id_essay_detail,
                        'ujian' => $ed->kode_ujian,
                        'siswa' => $su->id_siswa,
                    ]);
                }
            }

            $this->db->insert_batch('essay_siswa', $data_ujian_siswa);

            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            redirect('guru/ujian?pesan=success');
        }
    }
    // LIHAT UJIAN ESSAY
    public function lihat_essay($data_id_ujian, $data_id_guru)
    {
        $id_ujian = decrypt_url($data_id_ujian);
        $id_guru = decrypt_url($data_id_guru);

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->db->get_where('ujian', ['id_ujian' => $id_ujian, 'guru' => $id_guru])->row();
        $data['essay_detail'] = $this->db->get_where('essay_detail', ['kode_ujian' => $data['ujian']->kode_ujian])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();
        $data['siswa'] = $this->db->get_where('siswa', ['kelas' => $data['ujian']->kelas])->result();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/ujian/essay-lihat', $data);
        $this->load->view('templates/footer');
    }

    // ESSAY SISWA
    public function essay_siswa($data_id_siswa, $data_kode_ujian)
    {
        $id_siswa = decrypt_url($data_id_siswa);
        $kode_ujian = decrypt_url($data_kode_ujian);

        $essay_siswa = $this->db->get_where('essay_siswa', ['ujian' => $kode_ujian, 'siswa' => $id_siswa])->result();
        $ujian = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian])->row();

        if (count($essay_siswa) <= 0) {
            $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Warning!',
                            text: 'Siswa ini tidak bisa mengikuti ujian dikarenakan akun terdaftar setelah ujian dibuat',
                            type: 'warning',
                            padding: '2em'
                            });
                        ");
            $url = 'guru/lihat_essay/' . encrypt_url($ujian->id_ujian) . '/' . encrypt_url($this->session->userdata('id'));
            redirect($url);
        }

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian, 'guru' => $this->session->userdata('id')])->row();
        $data['essay_detail'] = $this->db->get_where('essay_detail', ['kode_ujian' => $data['ujian']->kode_ujian])->result();
        $data['essay_siswa'] = $this->db->get_where('essay_siswa', ['ujian' => $kode_ujian, 'siswa' => $id_siswa])->result();
        $data['guru'] = $this->db->get_where('guru', ['id_guru' => $this->session->userdata('id')])->row();
        $data['siswa'] = $this->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();

        $data['mapel'] = $this->db->get('mapel')->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['guru_kelas'] = $this->db->get_where('guru_kelas', ['guru' => $this->session->userdata('id')])->result();
        $data['guru_mapel'] = $this->db->get_where('guru_mapel', ['guru' => $this->session->userdata('id')])->result();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar/guru', $data);
        $this->load->view('guru/ujian/essay-siswa', $data);
        $this->load->view('templates/footer');
    }

    public function nilai_essay()
    {
        $kode_ujian = $this->input->post('kode_ujian');
        $siswa = $this->input->post('siswa');
        $ujian = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian])->row();

        $essay_detail = $this->db->get_where('essay_siswa', ['ujian' => $kode_ujian, 'siswa' => $siswa])->result();
        foreach ($essay_detail as $ed) {
            $score = $this->input->post("$ed->id_essay_siswa");
            $this->db->set('score', $score);
            $this->db->where('id_essay_siswa', $ed->id_essay_siswa);
            $this->db->update('essay_siswa');
        }
        $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dinilai',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        redirect('guru/lihat_essay/' . encrypt_url($ujian->id_ujian) . '/' . encrypt_url($this->session->userdata('id')));
    }

    // HAPUS UJIAN
    public function hapus_ujian($data_id_ujian, $data_id_guru)
    {
        $id_ujian = decrypt_url($data_id_ujian);
        $id_guru = decrypt_url($data_id_guru);

        $ujian = $this->db->get_where('ujian', ['id_ujian' => $id_ujian])->row();

        if ($ujian->jenis_ujian == 1) {
            $this->db->delete('essay_siswa', ['ujian' => $ujian->kode_ujian]);
            $this->db->delete('essay_detail', ['kode_ujian' => $ujian->kode_ujian]);
            $this->db->delete('ujian', ['kode_ujian' => $ujian->kode_ujian]);
        } else {
            $this->db->delete('ujian_siswa', ['ujian' => $ujian->kode_ujian]);
            $this->db->delete('ujian_detail', ['kode_ujian' => $ujian->kode_ujian]);
            $this->db->delete('ujian', ['kode_ujian' => $ujian->kode_ujian]);
        }

        $this->session->set_flashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dihapus',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        redirect('guru/ujian');
    }


    // END UJIAN =================================================
}
