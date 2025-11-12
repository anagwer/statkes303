<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RiwayatDonor_model extends CI_Model {

    public function get_all($user_id = null, $role = null) {
        $this->db->select('rd.*, u.nama as nama_anggota, u.nip, u.jabatan, u.foto');
        $this->db->from('riwayat_donor rd');
        $this->db->join('users u', 'u.id = rd.id_user', 'left');

        // Jika bukan admin, hanya tampilkan data user sendiri
        if ($role !== 'admin' && $user_id) {
            $this->db->where('rd.id_user', $user_id);
        }

        $this->db->order_by('rd.tanggal_donor', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('riwayat_donor', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('riwayat_donor', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('riwayat_donor', $data);
    }

    public function delete($id) {
        return $this->db->delete('riwayat_donor', ['id' => $id]);
    }

    public function get_users() {
        return $this->db->get_where('users', ['role !=' => 'admin'])->result(); // hanya user biasa
    }

    // Fungsi baru untuk export Excel
    public function get_for_export($user_filter = null, $start_date = null, $end_date = null) {
        $this->db->select('u.nip, u.nama, u.jabatan, rd.tanggal_donor, rd.keterangan, rd.created_at');
        $this->db->from('riwayat_donor rd');
        $this->db->join('users u', 'u.id = rd.id_user', 'inner'); // inner join untuk memastikan data user valid

        if ($user_filter) {
            $this->db->where('rd.id_user', $user_filter);
        }
        if ($start_date) {
            $this->db->where('rd.tanggal_donor >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('rd.tanggal_donor <=', $end_date);
        }

        $this->db->order_by('rd.tanggal_donor', 'DESC');
        return $this->db->get()->result();
    }
}
