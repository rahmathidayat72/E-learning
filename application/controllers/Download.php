<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Download extends CI_Controller
{
    public function file($nama)
    {
        force_download('./assets/app-assets/file/' . $nama, NULL);
    }

    public function excel_pg()
    {
        force_download('./assets/app-assets/file-excel/template.xlsx', NULL);
    }

    public function nilai_pg($data_kode_ujian, $data_id_siswa)
    {
    	$kode_ujian = decrypt_url($data_kode_ujian);
    	$id_siswa = decrypt_url($data_id_siswa);

    	$siswa = $this->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
    	$kelas = $this->db->get_where('kelas', ['id_kelas' => $siswa->kelas])->row();

    	$ujian =  $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian])->row();

    	$guru = $this->db->get_where('guru', ['id_guru' => $ujian->guru])->row();
    	$mapel = $this->db->get_where('mapel', ['id_mapel' => $ujian->mapel])->row();

        $nama_file = 'nilai_siswa';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
        $mpdf->SetHTMLHeader('<div style="text-align: left; margin-left: 20px; font-weight: bold;">
        <img src="'. base_url('assets/app-assets/img/cbt-malela-blue.png') .'" width="120px" style="margin-top: 20px;" alt="logo-aplikasi">
        </div>', 'O');
        $mpdf->SetHTMLHeader('<div style="text-align: left; margin-left: 20px; font-weight: bold;">
        <img src="'. base_url('assets/app-assets/img/cbt-malela-blue.png') .'" width="120px" style="margin-top: 20px;" alt="logo-aplikasi">
        </div>', 'E');

        $mpdf->SetHTMLFooter('
        <table border="0" width="100%" style="vertical-align: bottom; font-family: serif; 
            font-size: 8pt; color: #000000; font-weight: bold; font-style: italic; border: none;">
            <tr border="0">
                <td width="33%" style="text-align: left; border: none;">{DATE j-m-Y}</td>
                <td width="33%" align="center" style="border: none;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; border: none;">CBT-MALELA V2 by Abduloh</td>
            </tr>
        </table>');  // Note that the second parameter is optional : default = 'O' for ODD

        $mpdf->SetHTMLFooter('
        <table border="0" width="100%" style="vertical-align: bottom; font-family: serif; 
            font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr border="0">
                <td width="33%"><span style="font-weight: bold; font-style: italic;">CBT-MALELA V2 By Abduloh/span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: left; ">{DATE j-m-Y}</td>
            </tr>
        </table>', 'E');

        $html = '
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Report</title>
                <style>
                    body{
                        font-family: sans-serif;
                    }
                </style>
            </head>

            <body>
            <h2 style="text-align: center;">CBT-MALELA V2 BY ABDULOH<br><small>Built With Codeignite 3 & PHP 7</small></h2>
                <p style="text-align: center;">jln. Nakula RT017 RW 005 Pasirjaya Karawang</p>
                <hr>
                <h3 style="text-align: center;">Nilai Ujian Siswa</h3>
                <table border="0" cellpadding="3">
                	<tr>
                		<td>NAMA UJIAN</td>
                		<td> : <strong>'. $ujian->nama_ujian .'</strong></td>
                	</tr>
                	<tr>
                		<td>JENIS UJIAN</td>
                		<td> : <strong>Pilihan Ganda</strong></td>
                	</tr>
                	<tr>
                		<td>KELAS</td>
                		<td> : <strong>'. $kelas->nama_kelas .'</strong></td>
                	</tr>
                	<tr>
                		<td>MATA PELAJARAN</td>
                		<td> : <strong>'. $mapel->nama_mapel .'</strong></td>
                	</tr>
                	<tr>
                		<td>GURU</td>
                		<td> : <strong>'. $guru->nama_guru .'</strong></td>
                	</tr>
                	<tr>

                	</tr>
                </table>
            </body>
            </html>';

        $mpdf->WriteHTML($html);
        $mpdf->Output("$nama_file.pdf", \Mpdf\Output\Destination::INLINE);
    }
}
