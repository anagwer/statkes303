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
		if ($this->session->userdata('role') != 'admin') {
			show_error('Akses ditolak!', 403);
		}

		if ($this->input->post()) {
			$this->load->library('upload');

			// Handle upload bukti
			$bukti_name = null;
			if (!empty($_FILES['bukti']['name'])) {
				$config['upload_path'] = './assets/uploads/bukti_sakit/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = 5120; // 5MB
				$config['encrypt_name'] = TRUE;

				$this->upload->initialize($config);

				if ($this->upload->do_upload('bukti')) {
					$upload_data = $this->upload->data();
					$bukti_name = $upload_data['file_name'];
				} else {
					$this->session->set_flashdata('error', 'Gagal upload bukti: ' . $this->upload->display_errors());
					redirect('riwayat_sakit');
				}
			}

			$data = [
				'id_user' => $this->input->post('id_user'),
				'sakit' => $this->input->post('sakit'),
				'tanggal_sakit' => $this->input->post('tanggal_sakit'),
				'bukti' => $bukti_name,
				'rekomendasi' => $this->input->post('rekomendasi'),
				'obat' => $this->input->post('obat'),
				'keterangan' => $this->input->post('keterangan'),
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
        if ($this->session->userdata('role') != 'admin') {
			show_error('Akses ditolak!', 403);
		}

		$riwayat = $this->RiwayatSakit_model->get_by_id($id);
		if (!$riwayat) show_404();

		if ($this->input->post()) {
			$this->load->library('upload');

			$bukti_name = $riwayat->bukti; // pertahankan file lama jika tidak diupload ulang

			if (!empty($_FILES['bukti']['name'])) {
				// Hapus file lama jika ada
				if (!empty($riwayat->bukti)) {
					$old_file = FCPATH . 'assets/uploads/bukti_sakit/' . $riwayat->bukti;
					if (file_exists($old_file)) unlink($old_file);
				}

				$config['upload_path'] = './assets/uploads/bukti_sakit/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = 5120;
				$config['encrypt_name'] = TRUE;

				$this->upload->initialize($config);

				if ($this->upload->do_upload('bukti')) {
					$upload_data = $this->upload->data();
					$bukti_name = $upload_data['file_name'];
				} else {
					$this->session->set_flashdata('error', 'Gagal upload bukti: ' . $this->upload->display_errors());
					redirect('riwayat_sakit/edit/' . $id);
				}
			}

			$data = [
				'id_user' => $this->input->post('id_user'),
				'sakit' => $this->input->post('sakit'),
				'tanggal_sakit' => $this->input->post('tanggal_sakit'),
				'bukti' => $bukti_name,
				'rekomendasi' => $this->input->post('rekomendasi'),
				'obat' => $this->input->post('obat'),
				'keterangan' => $this->input->post('keterangan'),
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
		$activeSheet->setCellValue('E1', 'Penyakit');        // BARU
		$activeSheet->setCellValue('F1', 'Tanggal Sakit');
		$activeSheet->setCellValue('G1', 'Bukti');           // BARU (nama file)
		$activeSheet->setCellValue('H1', 'Rekomendasi');     // BARU
		$activeSheet->setCellValue('I1', 'Obat');            // BARU
		$activeSheet->setCellValue('J1', 'Keterangan');
		$activeSheet->setCellValue('K1', 'Created At');

		// Atur header bold & center (A1:K1)
		$headerRange = 'A1:K1'; // Tentukan rentang sel header
        $activeSheet->getStyle($headerRange)->getFont()->setBold(true); // Buat font tebal
        $activeSheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Rata tengah horizontal
		
        $row = 2;
        $no = 1;
        foreach ($data_export as $item) {
            $activeSheet->setCellValue('E' . $row, $item->sakit);
			$activeSheet->setCellValue('F' . $row, $item->tanggal_sakit ? date('d-m-Y', strtotime($item->tanggal_sakit)) : '');
			$activeSheet->setCellValue('G' . $row, $item->bukti); // hanya nama file
			$activeSheet->setCellValue('H' . $row, $item->rekomendasi);
			$activeSheet->setCellValue('I' . $row, $item->obat);
			$activeSheet->setCellValue('J' . $row, $item->keterangan);
			$activeSheet->setCellValue('K' . $row, $item->created_at);
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
