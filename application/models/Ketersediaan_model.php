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
}
