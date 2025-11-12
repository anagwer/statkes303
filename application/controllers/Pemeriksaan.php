<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemeriksaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('Pemeriksaan_model');
        $this->load->helper('download'); // Helper untuk download
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $data['items'] = $this->Pemeriksaan_model->get_all($user_id, $role);
        $data['title'] = 'Data Pemeriksaan Kesehatan';
				
        $data['users'] = $this->Pemeriksaan_model->get_users();
        $this->load->view('layouts/header', $data);
        $this->load->view('pemeriksaan');
        $this->load->view('layouts/footer');
    }

    public function create() {
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        if ($this->input->post()) {
            $data = [
                'anggota' => $this->input->post('anggota'),
                'tb' => $this->input->post('tb'), // Tambahkan ini
                'bb' => $this->input->post('bb'), // Tambahkan ini
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
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        if ($this->input->post()) {
            $data = [
                'anggota' => $this->input->post('anggota'),
                'tb' => $this->input->post('tb'), // Tambahkan ini
                'bb' => $this->input->post('bb'), // Tambahkan ini
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
        // Hanya admin yang bisa akses
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        if ($this->Pemeriksaan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data pemeriksaan berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data!');
        }
        redirect('pemeriksaan');
    }

    public function export_excel() {
        // Hanya admin yang bisa export
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        $user_filter = $this->input->get('id_user');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $data_export = $this->Pemeriksaan_model->get_for_export($user_filter, $start_date, $end_date);

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
        $activeSheet->setCellValue('E1', 'Tanggal Pemeriksaan');
        $activeSheet->setCellValue('F1', 'TB (cm)'); // Tambahkan ini
        $activeSheet->setCellValue('G1', 'BB (kg)'); // Tambahkan ini
        $activeSheet->setCellValue('H1', 'Gula Darah');
        $activeSheet->setCellValue('I1', 'Kolestrol');
        $activeSheet->setCellValue('J1', 'Asam Urat');
        $activeSheet->setCellValue('K1', 'Tekanan Darah');
        $activeSheet->setCellValue('L1', 'Nadi');
        $activeSheet->setCellValue('M1', 'Saturasi O2');
        $activeSheet->setCellValue('N1', 'RR');
        $activeSheet->setCellValue('O1', 'Suhu');
        $activeSheet->setCellValue('P1', 'Keterangan');
        $activeSheet->setCellValue('Q1', 'Created At');

        // --- Tambahkan baris-baris ini untuk membuat header bold dan center ---
        $headerRange = 'A1:Q1'; // Tentukan rentang sel header
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
            $activeSheet->setCellValue('E' . $row, $item->created_at ? date('d-m-Y', strtotime($item->created_at)) : '');
            $activeSheet->setCellValue('F' . $row, $item->tb); // Tambahkan ini
            $activeSheet->setCellValue('G' . $row, $item->bb); // Tambahkan ini
            $activeSheet->setCellValue('H' . $row, $item->gula);
            $activeSheet->setCellValue('I' . $row, $item->kolestrol);
            $activeSheet->setCellValue('J' . $row, $item->asam);
            $activeSheet->setCellValue('K' . $row, $item->tekanan);
            $activeSheet->setCellValue('L' . $row, $item->nadi);
            $activeSheet->setCellValue('M' . $row, $item->saturasi);
            $activeSheet->setCellValue('N' . $row, $item->rr);
            $activeSheet->setCellValue('O' . $row, $item->suhu);
            $activeSheet->setCellValue('P' . $row, $item->keterangan);
            $activeSheet->setCellValue('Q' . $row, $item->created_at);
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach(range('A','Q') as $col) { // Ubah rentang ke Q
            $activeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'data_pemeriksaan_export_' . date('Y-m-d_H-i-s') . '.xlsx';

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
