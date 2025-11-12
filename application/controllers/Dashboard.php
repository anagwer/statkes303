<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('User_model');
        $this->load->model('Ketersediaan_model');
        $this->load->model('Pemeriksaan_model');
        $this->load->model('RiwayatDonor_model');
        $this->load->model('RiwayatSakit_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';
        $data['role'] = $this->session->userdata('role'); // Tambahkan role ke data untuk view

        // Statistik Umum (untuk semua role)
        $data['total_donor'] = $this->db->count_all('riwayat_donor');
        $data['total_sakit'] = $this->db->count_all('riwayat_sakit');

        // Data untuk chart: pemeriksaan per bulan (6 bulan terakhir)
        $bulan = [];
        $jumlah = [];
        for ($i = 5; $i >= 0; $i--) {
            $bln = date('Y-m', strtotime("-$i months"));
            $bulan[] = date('M Y', strtotime($bln));
            $jumlah[] = $this->db->like('created_at', $bln)->get('pemeriksaan')->num_rows();
        }
        $data['chart_bulan'] = json_encode($bulan);
        $data['chart_jumlah'] = json_encode($jumlah);

        // Data untuk chart: donor per bulan (6 bulan terakhir)
        $bulan_donor = [];
        $jumlah_donor = [];
        for ($i = 5; $i >= 0; $i--) {
            $bln = date('Y-m', strtotime("-$i months"));
            $bulan_donor[] = date('M Y', strtotime($bln));
            $jumlah_donor[] = $this->db->like('created_at', $bln)->get('riwayat_donor')->num_rows();
        }
        $data['chart_bulan_donor'] = json_encode($bulan_donor);
        $data['chart_jumlah_donor'] = json_encode($jumlah_donor);

        // Data untuk chart: sakit per bulan (6 bulan terakhir)
        $bulan_sakit = [];
        $jumlah_sakit = [];
        for ($i = 5; $i >= 0; $i--) {
            $bln = date('Y-m', strtotime("-$i months"));
            $bulan_sakit[] = date('M Y', strtotime($bln));
            $jumlah_sakit[] = $this->db->like('created_at', $bln)->get('riwayat_sakit')->num_rows();
        }
        $data['chart_bulan_sakit'] = json_encode($bulan_sakit);
        $data['chart_jumlah_sakit'] = json_encode($jumlah_sakit);

        // Statistik Khusus Admin
        if ($data['role'] === 'admin') {
            $data['total_users'] = $this->db->count_all('users');
            $data['total_obat'] = $this->db->get_where('ketersediaan', ['jenis' => 'obat'])->num_rows();
            $data['total_alkes'] = $this->db->get_where('ketersediaan', ['jenis' => 'alkes'])->num_rows();
            $data['obat_expired_soon'] = $this->db->where('jenis', 'obat')
                ->where('expired IS NOT NULL')
                ->where('expired <=', date('Y-m-d', strtotime('+30 days')))
                ->where('expired >=', date('Y-m-d'))
                ->get('ketersediaan')->num_rows();
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('index');
        $this->load->view('layouts/footer');
    }
}
