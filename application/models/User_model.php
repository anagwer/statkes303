<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function login($nip, $password) {
        $this->db->where('nip', $nip);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function get_all_users() {
        return $this->db->get('users')->result();
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function insert_user($data) {
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id) {
        return $this->db->delete('users', ['id' => $id]);
    }

    public function is_nip_unique($nip, $id = null) {
        $this->db->where('nip', $nip);
        if ($id) $this->db->where('id !=', $id);
        return $this->db->get('users')->num_rows() == 0;
    }
}
