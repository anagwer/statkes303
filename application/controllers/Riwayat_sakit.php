<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_sakit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('RiwayatSakit_model');
        $this->load->helper('file');
        $this->load->helper('download'); 
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $data['riwayat'] = $this->RiwayatSakit_model->get_all($user_id, $role);
        $data['users'] = $this->RiwayatSakit_model->get_users();
        $data['title'] = 'Riwayat Sakit';
        $this->load->view('layouts/header', $data);
        $this->load->view('riwayat_sakit');
        $this->load->view('layouts/footer');
    }

    public function create() {
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        if ($this->input->post()) {
            $data = [
                'id_user' => $this->input->post('id_user'),
                'keterangan' => $this->input->post('keterangan'),
                'tanggal_sakit' => $this->input->post('tanggal_sakit'),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            if ($this->RiwayatSakit_model->insert($data)) {
                $this->session->set_flashdata('success', 'Riwayat sakit berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan riwayat sakit!');
            }
            redirect('riwayat_sakit');
        }

        $data['users'] = $this->RiwayatSakit_model->get_users();
        $data['title'] = 'Tambah Riwayat Sakit';
        $this->load->view('layouts/header', $data);
        $this->load->view('riwayat_sakit_form'); // Pastikan view ini tidak digunakan di index, bisa diganti atau hapus
        $this->load->view('layouts/footer');
    }

    public function edit($id) {
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        $riwayat = $this->RiwayatSakit_model->get_by_id($id);
        if (!$riwayat) show_404();

        if ($this->input->post()) {
            $data = [
                'id_user' => $this->input->post('id_user'),
                'keterangan' => $this->input->post('keterangan'),
                'tanggal_sakit' => $this->input->post('tanggal_sakit'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            if ($this->RiwayatSakit_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Riwayat sakit berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate riwayat sakit!');
            }
            redirect('riwayat_sakit');
        }

        $data['riwayat'] = $riwayat;
        $data['users'] = $this->RiwayatSakit_model->get_users();
        $data['title'] = 'Edit Riwayat Sakit';
        $this->load->view('layouts/header', $data);
        $this->load->view('riwayat_sakit_form'); // Pastikan view ini tidak digunakan di index, bisa diganti atau hapus
        $this->load->view('layouts/footer');
    }

    public function delete($id) {
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        if ($this->RiwayatSakit_model->delete($id)) {
            $this->session->set_flashdata('success', 'Riwayat sakit berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus riwayat sakit!');
        }
        redirect('riwayat_sakit');
    }

    public function export_excel() {
        // Hanya admin yang bisa export
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        $user_filter = $this->input->get('id_user');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $data_export = $this->RiwayatSakit_model->get_for_export($user_filter, $start_date, $end_date);

        // Muat kelas utama PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';

        // Buat objek Spreadsheet langsung dengan Fully Qualified Class Name
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Header Kolom
        $activeSheet->setCellValue('A1', 'No');
        $activeSheet->setCellValue('B1', 'NIP');
        $activeSheet->setCellValue('C1', 'Nama');
        $activeSheet->setCellValue('D1', 'Jabatan');
        $activeSheet->setCellValue('E1', 'Tanggal Sakit');
        $activeSheet->setCellValue('F1', 'Keterangan');
        $activeSheet->setCellValue('G1', 'Created At');

		$headerRange = 'A1:G1'; // Tentukan rentang sel header
        $activeSheet->getStyle($headerRange)->getFont()->setBold(true); // Buat font tebal
        $activeSheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Rata tengah horizontal
		
        $row = 2;
        $no = 1;
        foreach ($data_export as $item) {
            $activeSheet->setCellValue('A' . $row, $no++);
            $activeSheet->setCellValue('B' . $row, $item->nip);
            $activeSheet->setCellValue('C' . $row, $item->nama);
            $activeSheet->setCellValue('D' . $row, $item->jabatan);
            $activeSheet->setCellValue('E' . $row, $item->tanggal_sakit ? date('d-m-Y', strtotime($item->tanggal_sakit)) : '');
            $activeSheet->setCellValue('F' . $row, $item->keterangan);
            $activeSheet->setCellValue('G' . $row, $item->created_at);
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach(range('A','G') as $col) {
            $activeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'riwayat_sakit_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Buat writer langsung dengan Fully Qualified Class Name
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit; // Penting untuk menghentikan eksekusi setelah download
    }
}
