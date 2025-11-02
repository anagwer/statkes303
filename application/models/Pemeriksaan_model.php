<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemeriksaan_model extends CI_Model {

    public function get_all() {
				$this->db->select('
						p.*,
						u.nama as nama_anggota,
						u.jabatan,
						u.foto
				');
				$this->db->from('pemeriksaan p');
				$this->db->join('users u', 'p.anggota = u.id', 'left');
				return $this->db->get()->result();
		}

    public function get_by_id($id) {
        return $this->db->get_where('pemeriksaan', ['id' => $id])->row();
    }

    public function get_users() {
        return $this->db->get_where('users', ['role !=' => 'admin'])->result(); // opsional: hanya user biasa
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
}
