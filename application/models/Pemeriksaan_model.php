<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemeriksaan_model extends CI_Model {

    public function get_all($user_id = null, $role = null) {
        $this->db->select('
            p.*,
            u.nama as nama_anggota,
            u.nip,
            u.jabatan,
            u.foto
        ');
        $this->db->from('pemeriksaan p');
        $this->db->join('users u', 'p.anggota = u.id', 'left');

        // Jika bukan admin, hanya tampilkan data user sendiri
        if ($role !== 'admin' && $user_id) {
            $this->db->where('p.anggota', $user_id);
        }

        $this->db->order_by('p.created_at', 'DESC'); // Urutkan terbaru dulu
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('pemeriksaan', ['id' => $id])->row();
    }

    public function get_users() {
        return $this->db->get_where('users', ['role !=' => 'admin'])->result(); // hanya user biasa
    }

    public function insert($data) {
        return $this->db->insert('pemeriksaan', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pemeriksaan', $data);
    }

    public function delete($id) {
        return $this->db->delete('pemeriksaan', ['id' => $id]);
    }

    // Fungsi baru untuk export Excel
    public function get_for_export($user_filter = null, $start_date = null, $end_date = null) {
        $this->db->select('u.nip, u.nama, u.jabatan, p.created_at, p.gula, p.kolestrol, p.asam, p.tekanan, p.nadi, p.saturasi, p.rr, p.suhu, p.keterangan');
        $this->db->from('pemeriksaan p');
        $this->db->join('users u', 'u.id = p.anggota', 'inner'); // inner join untuk memastikan data user valid

        if ($user_filter) {
            $this->db->where('p.anggota', $user_filter);
        }
        if ($start_date) {
            $this->db->where('DATE(p.created_at) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(p.created_at) <=', $end_date);
        }

        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
