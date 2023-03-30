<?php

class Presensi extends CI_Model
{

    public function getpresensi()
    {
        $this->db->select('*');
        $this->db->from('event');
        $this->db->order_by('id_event', 'DESC');
        return $this->db->get()->result();
    }

    public function presensibyno($data)
    {
        return $this->db->get_where('event', ['no_event' => $data])->row();
    }

    public function getall()
    {
        $this->db->select("*");
        $this->db->from('absen');
        $this->db->join('event', 'event.no_event = absen.no_event');
        return $this->db->get()->result();
    }

    public function getbynoevent($data)
    {
        $this->db->select("*");
        $this->db->from('absen');
        $this->db->join('event', 'event.no_event = absen.no_event');
        $this->db->where('absen.no_event', $data);
        return $this->db->get()->result();
    }

    public function absensiswa($data1)
    {
        $this->db->select("*");
        $this->db->from('absen');
        $this->db->join('event', 'event.no_event = absen.no_event');
        $this->db->where('absen.no_event', $data1);
        return $this->db->get()->result();
    }

    public function absensiswa1($data1, $data2)
    {
        $this->db->select("*");
        $this->db->from('absen');
        $this->db->join('event', 'event.no_event = absen.no_event');
        $this->db->where('absen.no_event', $data1);
        $this->db->where('absen.nim_siswa', $data2);
        return $this->db->get()->row();
    }

    public function insert_bulk($data)
    {
        return $this->db->insert_batch('absen', $data);
    }
}
