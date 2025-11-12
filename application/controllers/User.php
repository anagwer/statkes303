<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('User_model');
        $this->load->helper('file');

        $this->load->helper('form'); // Tambahkan ini
    }

    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $data['title'] = 'Manajemen User';
        $this->load->view('layouts/header', $data);
        $this->load->view('user');
        $this->load->view('layouts/footer');
    }

    public function create() {
        if ($this->input->post()) {
            $nip = $this->input->post('nip');
            if (!$this->User_model->is_nip_unique($nip)) {
                $this->session->set_flashdata('error', 'NIP sudah digunakan!');
                redirect('user');
            }

            // Handle upload foto
            $foto_name = null;
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path']   = './assets/img/profil/';
                $config['allowed_types'] = 'jpg|jpeg|png|jfif';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = time(); // sementara
                $config['overwrite']     = true;

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $foto_name = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('user');
                }
            }

            $data = [
                'nip' => $nip,
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
				'goldar' => $this->input->post('goldar'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'role' => $this->input->post('role'),
                'foto' => $foto_name // simpan nama file
            ];

            if ($this->User_model->insert_user($data)) {
                // Ambil ID terakhir, lalu rename foto jadi <id>.jpg
                $user_id = $this->db->insert_id();
                if ($foto_name) {
                    $new_name = $user_id . '.jpg';
                    rename('./assets/img/profil/' . $foto_name, './assets/img/profil/' . $new_name);
                    // Update nama file di DB
                    $this->User_model->update_user($user_id, ['foto' => $new_name]);
                }
                $this->session->set_flashdata('success', 'User berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan user!');
            }
            redirect('user');
        }

        $data['title'] = 'Tambah User';
        $this->load->view('layouts/header', $data);
        $this->load->view('user');
        $this->load->view('layouts/footer');
    }

    public function edit($id) {
        $user = $this->User_model->get_user_by_id($id);
        if (!$user) show_404();

        if ($this->input->post()) {
            $nip = $this->input->post('nip');
            if (!$this->User_model->is_nip_unique($nip, $id)) {
                $this->session->set_flashdata('error', 'NIP sudah digunakan!');
                redirect('user/' . $id);
            }

            // Handle foto baru
            $foto_name = $user->foto; // pertahankan foto lama jika tidak diubah
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path']   = './assets/img/profil/';
                $config['allowed_types'] = 'jpg|jpeg|png|jfif';
                $config['max_size']      = 2048;
                $config['file_name']     = $id; // langsung pakai ID
                $config['overwrite']     = true;

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $foto_name = $upload_data['file_name'];

                    // Pastikan ekstensi .jpg
                    if (pathinfo($foto_name, PATHINFO_EXTENSION) !== 'jpg') {
                        rename('./assets/img/profil/' . $foto_name, './assets/img/profil/' . $id . '.jpg');
                        $foto_name = $id . '.jpg';
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('user/' . $id);
                }
            }

            $data = [
                'nip' => $nip,
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
				'goldar' => $this->input->post('goldar'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'role' => $this->input->post('role'),
                'foto' => $foto_name
            ];

            if (!empty($this->input->post('password'))) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            if ($this->User_model->update_user($id, $data)) {
                $this->session->set_flashdata('success', 'User berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate user!');
            }
            redirect('user');
        }

        $data['user'] = $user;
        $data['title'] = 'Edit User';
        $this->load->view('layouts/header', $data);
        $this->load->view('user');
        $this->load->view('layouts/footer');
    }

    public function delete($id) {
        $user = $this->User_model->get_user_by_id($id);
        if ($user && $user->foto) {
            $foto_path = FCPATH . 'assets/img/profil/' . $user->foto;
            if (file_exists($foto_path)) {
                unlink($foto_path);
            }
        }
        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user!');
        }
        redirect('user');
    }
}
