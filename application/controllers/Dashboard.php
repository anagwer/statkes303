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
    }

    public function index() {
        $data['title'] = 'Dashboard';

        // Statistik
        $data['total_users'] = $this->db->count_all('users');
        $data['total_obat'] = $this->db->get_where('ketersediaan', ['jenis' => 'obat'])->num_rows();
        $data['total_alkes'] = $this->db->get_where('ketersediaan', ['jenis' => 'alkes'])->num_rows();

        // Obat kadaluarsa dalam 30 hari
        $data['obat_expired_soon'] = $this->db->where('jenis', 'obat')
            ->where('expired IS NOT NULL')
            ->where('expired <=', date('Y-m-d', strtotime('+30 days')))
            ->where('expired >=', date('Y-m-d'))
            ->get('ketersediaan')->num_rows();

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

        $this->load->view('layouts/header', $data);
        $this->load->view('index');
        $this->load->view('layouts/footer');
    }
}
