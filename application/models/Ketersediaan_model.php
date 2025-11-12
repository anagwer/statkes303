<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ketersediaan_model extends CI_Model {

    public function get_all($jenis) {
        return $this->db->get_where('ketersediaan', ['jenis' => $jenis])->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('ketersediaan', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('ketersediaan', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('ketersediaan', $data);
    }

    public function delete($id) {
        return $this->db->delete('ketersediaan', ['id' => $id]);
    }

    // Fungsi baru untuk export Excel
    public function get_for_export($jenis = null) {
        $this->db->select('nama, stok, satuan, expired, keterangan, created_at, updated_at');
        $this->db->from('ketersediaan');

        if ($jenis) {
            $this->db->where('jenis', $jenis);
        }

        $this->db->order_by('created_at', 'DESC'); // Urutkan berdasarkan waktu dibuat
        return $this->db->get()->result();
    }
}
