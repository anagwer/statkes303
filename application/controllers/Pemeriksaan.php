<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemeriksaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('Pemeriksaan_model');
    }

    public function index() {
        $data['items'] = $this->Pemeriksaan_model->get_all();
        $data['title'] = 'Data Pemeriksaan Kesehatan';
				
        $data['users'] = $this->Pemeriksaan_model->get_users();
        $this->load->view('layouts/header', $data);
        $this->load->view('pemeriksaan');
        $this->load->view('layouts/footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = [
                'anggota' => $this->input->post('anggota'),
                'gula' => $this->input->post('gula'),
                'kolestrol' => $this->input->post('kolestrol'),
                'asam' => $this->input->post('asam'),
                'tekanan' => $this->input->post('tekanan'),
                'nadi' => $this->input->post('nadi'),
                'saturasi' => $this->input->post('saturasi'),
                'rr' => $this->input->post('rr'),
                'suhu' => $this->input->post('suhu'),
                'keterangan' => $this->input->post('keterangan'),
                'inputed_by' => $this->session->userdata('id')
            ];

            if ($this->Pemeriksaan_model->insert($data)) {
                $this->session->set_flashdata('success', 'Data pemeriksaan berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data!');
            }
            redirect('pemeriksaan');
        }

        // Untuk dropdown anggota
        $data['users'] = $this->Pemeriksaan_model->get_users();
        $data['title'] = 'Tambah Pemeriksaan';
        $this->load->view('layouts/header', $data);
        $this->load->view('pemeriksaan');
        $this->load->view('layouts/footer');
    }

    public function edit($id) {
        if ($this->input->post()) {
            $data = [
                'anggota' => $this->input->post('anggota'),
                'gula' => $this->input->post('gula'),
                'kolestrol' => $this->input->post('kolestrol'),
                'asam' => $this->input->post('asam'),
                'tekanan' => $this->input->post('tekanan'),
                'nadi' => $this->input->post('nadi'),
                'saturasi' => $this->input->post('saturasi'),
                'rr' => $this->input->post('rr'),
                'suhu' => $this->input->post('suhu'),
                'keterangan' => $this->input->post('keterangan'),
                'updated_by' => $this->session->userdata('id')
            ];

            if ($this->Pemeriksaan_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data pemeriksaan berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data!');
            }
            redirect('pemeriksaan');
        }

        $data['item'] = $this->Pemeriksaan_model->get_by_id($id);
        $data['users'] = $this->Pemeriksaan_model->get_users();
        $data['title'] = 'Edit Pemeriksaan';
        $this->load->view('layouts/header', $data);
        $this->load->view('pemeriksaan');
        $this->load->view('layouts/footer');
    }

    public function delete($id) {
        if ($this->Pemeriksaan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data pemeriksaan berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data!');
        }
        redirect('pemeriksaan');
    }
}
