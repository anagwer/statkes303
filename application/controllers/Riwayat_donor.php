<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_donor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('RiwayatDonor_model');
        $this->load->helper('file');
        $this->load->helper('download'); // Helper untuk download
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $data['riwayat'] = $this->RiwayatDonor_model->get_all($user_id, $role);
        $data['users'] = $this->RiwayatDonor_model->get_users();
        $data['title'] = 'Riwayat Donor';
        $this->load->view('layouts/header', $data);
        $this->load->view('riwayat_donor');
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
                'tanggal_donor' => $this->input->post('tanggal_donor'),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            if ($this->RiwayatDonor_model->insert($data)) {
                $this->session->set_flashdata('success', 'Riwayat donor berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan riwayat donor!');
            }
            redirect('riwayat_donor');
        }

        $data['users'] = $this->RiwayatDonor_model->get_users();
        $data['title'] = 'Tambah Riwayat Donor';
        $this->load->view('layouts/header', $data);
        $this->load->view('riwayat_donor'); // Pastikan view ini tidak digunakan di index, bisa diganti atau hapus
        $this->load->view('layouts/footer');
    }

    public function edit($id) {
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        $riwayat = $this->RiwayatDonor_model->get_by_id($id);
        if (!$riwayat) show_404();

        if ($this->input->post()) {
            $data = [
                'id_user' => $this->input->post('id_user'),
                'keterangan' => $this->input->post('keterangan'),
                'tanggal_donor' => $this->input->post('tanggal_donor'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            if ($this->RiwayatDonor_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Riwayat donor berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate riwayat donor!');
            }
            redirect('riwayat_donor');
        }

        $data['riwayat'] = $riwayat;
        $data['users'] = $this->RiwayatDonor_model->get_users();
        $data['title'] = 'Edit Riwayat Donor';
        $this->load->view('layouts/header', $data);
        $this->load->view('riwayat_donor'); // Pastikan view ini tidak digunakan di index, bisa diganti atau hapus
        $this->load->view('layouts/footer');
    }

    public function delete($id) {
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        if ($this->RiwayatDonor_model->delete($id)) {
            $this->session->set_flashdata('success', 'Riwayat donor berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus riwayat donor!');
        }
        redirect('riwayat_donor');
    }

    public function export_excel() {
        // Hanya admin yang bisa export
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        $user_filter = $this->input->get('id_user');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $data_export = $this->RiwayatDonor_model->get_for_export($user_filter, $start_date, $end_date);

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
        $activeSheet->setCellValue('E1', 'Tanggal Donor');
        $activeSheet->setCellValue('F1', 'Keterangan');
        $activeSheet->setCellValue('G1', 'Created At');

        // --- Tambahkan baris-baris ini untuk membuat header bold dan center ---
        $headerRange = 'A1:G1'; // Tentukan rentang sel header
        $activeSheet->getStyle($headerRange)->getFont()->setBold(true); // Buat font tebal
        $activeSheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Rata tengah horizontal
        // --- Selesai tambahan ---

        $row = 2;
        $no = 1;
        foreach ($data_export as $item) {
            $activeSheet->setCellValue('A' . $row, $no++);
            $activeSheet->setCellValue('B' . $row, $item->nip);
            $activeSheet->setCellValue('C' . $row, $item->nama);
            $activeSheet->setCellValue('D' . $row, $item->jabatan);
            $activeSheet->setCellValue('E' . $row, $item->tanggal_donor ? date('d-m-Y', strtotime($item->tanggal_donor)) : '');
            $activeSheet->setCellValue('F' . $row, $item->keterangan);
            $activeSheet->setCellValue('G' . $row, $item->created_at);
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach(range('A','G') as $col) {
            $activeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'riwayat_donor_export_' . date('Y-m-d_H-i-s') . '.xlsx';

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
