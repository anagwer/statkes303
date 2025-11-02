<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alkes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('Ketersediaan_model');
        $this->load->helper('file');
    }

    public function index() {
        $data['items'] = $this->Ketersediaan_model->get_all('alkes');
        $data['title'] = 'Ketersediaan Alkes';
        $data['jenis'] = 'alkes';
        $this->load->view('layouts/header', $data);
        $this->load->view('alkes');
        $this->load->view('layouts/footer');
    }

    public function create() {
        if (!$this->session->userdata('role') == 'admin') show_error('Akses ditolak!', 403);

        if ($this->input->post()) {
            $foto = null;
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/img/alkes/';
                $config['allowed_types'] = 'jpg|jpeg|png|jfif|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = 'alkes_' . time();
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('foto')) {
                    $foto = $this->upload->data('file_name');
                }
            }

            $data = [
                'jenis' => 'alkes',
                'nama' => $this->input->post('nama'),
                'stok' => $this->input->post('stok'),
                'satuan' => $this->input->post('satuan'),
                'keterangan' => $this->input->post('keterangan'),
                'foto' => $foto
            ];

            if ($this->Ketersediaan_model->insert($data)) {
                $this->session->set_flashdata('success', 'Alkes berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan alkes!');
            }
            redirect('alkes');
        }
    }

    public function edit($id) {
        if (!$this->session->userdata('role') == 'admin') show_error('Akses ditolak!', 403);

        if ($this->input->post()) {
            $item = $this->Ketersediaan_model->get_by_id($id);
            $foto = $item->foto;

            if (!empty($_FILES['foto']['name'])) {
                // Hapus foto lama
                if ($foto && file_exists(FCPATH . 'assets/img/alkes/' . $foto)) {
                    unlink(FCPATH . 'assets/img/alkes/' . $foto);
                }
                $config['upload_path'] = './assets/img/alkes/';
                $config['allowed_types'] = 'jpg|jpeg|png|jfif|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = 'alkes_' . time();
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('foto')) {
                    $foto = $this->upload->data('file_name');
                }
            }

            $data = [
                'nama' => $this->input->post('nama'),
                'stok' => $this->input->post('stok'),
                'satuan' => $this->input->post('satuan'),
                'keterangan' => $this->input->post('keterangan'),
                'foto' => $foto
            ];

            if ($this->Ketersediaan_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Alkes berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate alkes!');
            }
            redirect('alkes');
        }
    }

    public function delete($id) {
        if (!$this->session->userdata('role') == 'admin') show_error('Akses ditolak!', 403);

        $item = $this->Ketersediaan_model->get_by_id($id);
        if ($item && $item->foto && file_exists(FCPATH . 'assets/img/alkes/' . $item->foto)) {
            unlink(FCPATH . 'assets/img/alkes/' . $item->foto);
        }
        if ($this->Ketersediaan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Alkes berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus alkes!');
        }
        redirect('alkes');
    }
}
