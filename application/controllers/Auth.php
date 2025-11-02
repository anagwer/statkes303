<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
				$this->load->library('session');
        $this->load->model('User_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('login');
    }

    public function login() {
        $nip = $this->input->post('nip');
        $password = $this->input->post('password');

        $user = $this->User_model->login($nip, $password);
        if ($user) {
            $data = [
                'id' => $user->id,
                'nip' => $user->nip,
                'nama' => $user->nama,
                'role' => $user->role,
                'foto' => $user->foto,
                'logged_in' => true
            ];
            $this->session->set_userdata($data);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'NIP atau Password salah!');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
