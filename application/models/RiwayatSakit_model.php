<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RiwayatSakit_model extends CI_Model {

    public function get_all($user_id, $role) {
		$this->db->select('rs.*, u.nama as nama_anggota, u.nip, u.jabatan, u.foto');
		$this->db->from('riwayat_sakit rs');
		$this->db->join('users u', 'u.id = rs.id_user', 'left');
		
		if ($role !== 'admin') {
			$this->db->where('rs.id_user', $user_id);
		}
		
		$this->db->order_by('rs.tanggal_sakit', 'DESC');
		return $this->db->get()->result();
	}

    public function get_by_id($id) {
        return $this->db->get_where('riwayat_sakit', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('riwayat_sakit', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('riwayat_sakit', $data);
    }

    public function delete($id) {
        return $this->db->delete('riwayat_sakit', ['id' => $id]);
    }

    public function get_users() {
        return $this->db->get_where('users', ['role !=' => 'admin'])->result(); // hanya user biasa
    }

    // Fungsi baru untuk export Excel
    public function get_for_export($user_id = null, $start = null, $end = null) {
		$this->db->select('rs.*, u.nama, u.nip, u.jabatan');
		$this->db->from('riwayat_sakit rs');
		$this->db->join('users u', 'u.id = rs.id_user', 'left');
		
		if ($user_id) $this->db->where('rs.id_user', $user_id);
		if ($start) $this->db->where('rs.tanggal_sakit >=', $start);
		if ($end) $this->db->where('rs.tanggal_sakit <=', $end);
		
		return $this->db->get()->result();
	}
}
