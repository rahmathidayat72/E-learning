<?php

class Kelas extends CI_Model
{
    public function getclass()
    {
        return $this->db->get('kelas')->result();
    }

    public function getclassbycode($data)
    {
        return $this->db->get_where('kelas', ['kode_kelas' => $data])->row();
    }

    public function insert_bulk($data)
    {
        return $this->db->insert_batch('kelas', $data);
    }
}
