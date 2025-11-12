<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('Ketersediaan_model');
        $this->load->helper('file');
        $this->load->helper('download'); // Helper untuk download
    }

    public function index() {
        $data['items'] = $this->Ketersediaan_model->get_all('obat');
        $data['title'] = 'Ketersediaan Obat';
        $data['jenis'] = 'obat';
        $this->load->view('layouts/header', $data);
        $this->load->view('obat');
        $this->load->view('layouts/footer');
    }

    public function create() {
        if ($this->session->userdata('role') != 'admin') show_error('Akses ditolak!', 403);

        if ($this->input->post()) {
            $foto = null;
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/img/profil/';
                $config['allowed_types'] = 'jpg|jpeg|png|jfif|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = 'obat_' . time();
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('foto')) {
                    $foto = $this->upload->data('file_name');
                }
            }

            $data = [
                'jenis' => 'obat',
                'nama' => $this->input->post('nama'),
                'stok' => $this->input->post('stok'),
                'satuan' => $this->input->post('satuan'),
                'expired' => $this->input->post('expired'),
                'keterangan' => $this->input->post('keterangan'),
                'foto' => $foto
            ];

            if ($this->Ketersediaan_model->insert($data)) {
                $this->session->set_flashdata('success', 'Obat berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan obat!');
            }
            redirect('obat');
        }
    }

    public function edit($id) {
        if ($this->session->userdata('role') != 'admin') show_error('Akses ditolak!', 403);

        if ($this->input->post()) {
            $item = $this->Ketersediaan_model->get_by_id($id);
            $foto = $item->foto;

            if (!empty($_FILES['foto']['name'])) {
                // Hapus foto lama
                if ($foto && file_exists(FCPATH . 'assets/img/profil/' . $foto)) {
                    unlink(FCPATH . 'assets/img/profil/' . $foto);
                }
                $config['upload_path'] = './assets/img/profil/';
                $config['allowed_types'] = 'jpg|jpeg|png|jfif|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = 'obat_' . time();
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('foto')) {
                    $foto = $this->upload->data('file_name');
                }
            }

            $data = [
                'nama' => $this->input->post('nama'),
                'stok' => $this->input->post('stok'),
                'satuan' => $this->input->post('satuan'),
                'expired' => $this->input->post('expired'),
                'keterangan' => $this->input->post('keterangan'),
                'foto' => $foto
            ];

            if ($this->Ketersediaan_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Obat berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate obat!');
            }
            redirect('obat');
        }
    }

    public function delete($id) {
        if ($this->session->userdata('role') != 'admin') show_error('Akses ditolak!', 403);

        $item = $this->Ketersediaan_model->get_by_id($id);
        if ($item && $item->foto && file_exists(FCPATH . 'assets/img/profil/' . $item->foto)) {
            unlink(FCPATH . 'assets/img/profil/' . $item->foto);
        }
        if ($this->Ketersediaan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Obat berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus obat!');
        }
        redirect('obat');
    }

    public function export_excel() {
        // Hanya admin yang bisa export
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak!', 403);
        }

        // Filter berdasarkan jenis (obat)
        $jenis = 'obat';
        $data_export = $this->Ketersediaan_model->get_for_export($jenis);

        // Muat kelas utama PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';

        // Buat objek Spreadsheet langsung dengan Fully Qualified Class Name
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Header Kolom
        $activeSheet->setCellValue('A1', 'No');
        $activeSheet->setCellValue('B1', 'Nama');
        $activeSheet->setCellValue('C1', 'Stok');
        $activeSheet->setCellValue('D1', 'Satuan');
        $activeSheet->setCellValue('E1', 'Expired');
        $activeSheet->setCellValue('F1', 'Keterangan');
        $activeSheet->setCellValue('G1', 'Created At');
        $activeSheet->setCellValue('H1', 'Updated At');

        // --- Tambahkan baris-baris ini untuk membuat header bold dan center ---
        $headerRange = 'A1:H1'; // Tentukan rentang sel header
        $activeSheet->getStyle($headerRange)->getFont()->setBold(true); // Buat font tebal
        $activeSheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Rata tengah horizontal
        // --- Selesai tambahan ---

        $row = 2;
        $no = 1;
        foreach ($data_export as $item) {
            $activeSheet->setCellValue('A' . $row, $no++);
            $activeSheet->setCellValue('B' . $row, $item->nama);
            $activeSheet->setCellValue('C' . $row, $item->stok);
            $activeSheet->setCellValue('D' . $row, $item->satuan);
            $activeSheet->setCellValue('E' . $row, $item->expired ? date('d-m-Y', strtotime($item->expired)) : '');
            $activeSheet->setCellValue('F' . $row, $item->keterangan);
            $activeSheet->setCellValue('G' . $row, $item->created_at);
            $activeSheet->setCellValue('H' . $row, $item->updated_at);
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach(range('A','H') as $col) {
            $activeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'ketersediaan_obat_export_' . date('Y-m-d_H-i-s') . '.xlsx';

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
