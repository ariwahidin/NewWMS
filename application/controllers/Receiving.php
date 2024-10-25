<?php defined('BASEPATH') or exit('No direct script access allowed');

class Receiving extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'ekspedisi_m', 'receiving_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }


    public function index()
    {
        $data = array(
            'title' => 'Create Receiving',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'type' => $this->order_m->getOrderType()
        );

        // if (isset($_GET['edit']) && isset($_GET['spk'])) {
        //     $spk = $_GET['spk'];
        //     $order = $this->order_m->getDO($spk);
        //     if ($order->num_rows() > 0) {
        //         $data['order'] = $order->row();
        //     } else {
        //         echo "Not Found";
        //         exit;
        //     }
        // }

        $this->render('receiving/index', $data);
    }



    public function createProccess()
    {
        // var_dump($_POST);
        // var_dump($_SESSION);
        // die;



        $this->load->database();

        // Memulai transaksi
        $this->db->trans_start();

        // Ambil data header dari input POST
        $order_ids = $this->input->post('items');
        $header = $this->input->post('header');

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'IB'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 receive_number FROM receive_header 
                    WHERE receive_number LIKE ? 
                    ORDER BY receive_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->receive_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $nomorSuratJalan = $prefix . $currentYearMonth . $newNumber;

        // Simpan data surat jalan ke database menggunakan raw query
        $insertHeaderSQL = "INSERT INTO receive_header (receive_number, load_number, receive_date, receive_time, spk_date, ship_mode, order_type, 
                truck_arival_date, truck_arival_time, start_loading, finish_loading, dispath_proccess, load_status, 
                transporter, truck_type, truck_no, driver_name, driver_phone, total_cbm, charge_by, remarks, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($insertHeaderSQL, array(
            $nomorSuratJalan,
            $header['loadNumber'],
            $header['orderDate'],
            $header['orderTime'],
            $header['spkDate'],
            $header['shipMode'],
            $header['orderTypeID'],
            ($header['truckArivalDate'] == '') ? null : $header['truckArivalDate'],
            $header['truckArivalTime'],
            $header['startLoading'],
            $header['finishLoading'],
            $header['dispathProccess'],
            $header['loadStatus'],
            $header['transporterID'],
            $header['truckType'],
            $header['truckNo'],
            $header['driverName'],
            $header['driverPhone'],
            $header['totalCBM'],
            $header['chargeBy'],
            $header['remarks'],
            $_SESSION['user_data']['username']
        ));

        $last_id = $this->db->insert_id();

        // Insert order ke tabel order_d menggunakan raw query
        foreach ($order_ids as $order_id) {
            $item_code = $order_id['item_code'];
            $qty = $order_id['quantity'];
            $rcv_loc = $order_id['rcv_loc'];
            $status = $order_id['status'];
            $insertDetailSQL = "INSERT INTO receive_detail (receive_id, receive_number, item_code, qty, receive_location, status ) VALUES (?, ?, ?, ?, ?, ?)";
            $this->db->query($insertDetailSQL, array($last_id, $nomorSuratJalan, $item_code, $qty, $rcv_loc, $status));
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            // Kembalikan nomor surat jalan ke frontend
            echo json_encode(array('success' => true, 'nomor_surat_jalan' => $nomorSuratJalan));
        }
    }


    public function receivingList() {
        $data = array(
            'title' => 'Receiving List',
            'receive' => $this->receiving_m->receiveList()
        );
        $this->render('receiving/list_receiving/index', $data);
    }

    public function putaway(){
        $data = array(
            'title' => 'Putaway',
        );
        $this->render('receiving/putaway', $data);
    }

}
