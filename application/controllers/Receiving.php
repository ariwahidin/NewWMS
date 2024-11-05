<?php defined('BASEPATH') or exit('No direct script access allowed');

class Receiving extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'ekspedisi_m', 'receiving_m', 'trans_m', 'item_m', 'supplier_m']);
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
            'title' => isset($_GET['edit']) && $_GET['ib'] ? 'Receiving ' . $_GET['ib'] : 'Create Receiving',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'supplier' => $this->supplier_m->getAllItem(),
            'type' => $this->order_m->getOrderType()
        );

        if (isset($_GET['edit']) && isset($_GET['ib'])) {
            $ib_no = $_GET['ib'];
            $order = $this->receiving_m->getReceive($ib_no);
            if ($order->num_rows() > 0) {
                $data['order'] = $order->row();
            } else {
                echo "Not Found";
                exit;
            }
        }


        $this->render('receiving/index', $data);
    }

    public function getItems()
    {

        $shipment_current = null;

        if (isset($_POST['ib_no'])) {
            $shipment_current = $this->receiving_m->getReceiveDetail($_POST['ib_no'])->result_array();
        }

        $listDO = $this->item_m->getAllItem()->result_array();

        $data = array(
            'shipments' => $listDO,
            'shipment_current' => $shipment_current
        );

        echo json_encode($data);
    }

    public function createProccess()
    {
        // var_dump($_POST);
        // var_dump($_SESSION);
        // die;

        if (!isset($_POST['items']) || count($this->input->post('items')) < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item cannot be empty'
            );
            echo json_encode($response);
            exit;
        }

        $po_number = $this->input->post('header')['poNumber'];

        $check = $this->db->get_where('receive_header', array('po_number' => $po_number, 'receiving_status' => 'Received'));
        if ($check->num_rows() > 0) {
            $response = array(
                'success' => false,
                'message' => 'This shipment has already been received (PO : ' . $po_number . ')'
            );
            echo json_encode($response);
            exit;
        }

        // Memulai transaksi
        $this->db->trans_start();

        // Ambil data header dari input POST
        $order_ids = $this->input->post('items');
        $header = $this->input->post('header');

        //get TransID
        $transID = $this->trans_m->getTransID('Receiving');

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

        $dataInsertHeader = array(
            'trans_id' => $transID,
            'receive_number' => $nomorSuratJalan,
            'ship_reff' => $header['loadNumber'],
            'po_number' => $header['poNumber'],
            'sj_number' => $header['SJNumber'],
            'invoice_number' => $header['invoiceNumber'],
            'receive_date' => $header['orderDate'],
            'receive_time' => $header['orderTime'],
            'unloading_date' => $header['spkDate'],
            'supplier_id' => $header['supplierID'],
            'transporter_id' => $header['transporterID'],
            'truck_type' => $header['truckType'],
            'receiving_status' => $header['receiveStatus'],
            'truck_arival_date' => $header['truckArivalDate'],
            'truck_arival_time' => $header['truckArivalTime'],
            'start_loading' => $header['startLoading'],
            'finish_loading' => $header['finishLoading'],
            'truck_no' => $header['truckNo'],
            'driver_name' => $header['driverName'],
            'driver_phone' => $header['driverPhone'],
            'container_no' => $header['containerNo'],
            'remarks' => $header['remarks'],
            'created_by' => $_SESSION['user_data']['username']
        );

        // var_dump($dataInsertHeader);
        // die;


        $this->db->insert('receive_header', $dataInsertHeader);
        $last_id = $this->db->insert_id();

        foreach ($order_ids as $order_id) {
            $dataInsertDetail = array(
                'receive_id' => $last_id,
                'receive_number' => $nomorSuratJalan,
                // 'lpn_number' => $this->receiving_m->generate_lpn(),
                'item_code' => $order_id['item_code'],
                'qty' => $order_id['quantity'],
                'receive_location' => $order_id['rcv_loc'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'created_by' => $_SESSION['user_data']['username']
            );

            // var_dump($dataInsertDetail);

            $this->db->insert('receive_detail', $dataInsertDetail);

            $receive_detail_id = $this->db->insert_id();
            $dataInsertInventory = array(
                'location' => $order_id['rcv_loc'],
                'item_code' => $order_id['item_code'],
                'on_hand' => $order_id['quantity'],
                'allocated' => 0,
                'available' => 0,
                'in_transit' => $order_id['quantity'],
                'receive_date' => $header['orderDate'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'is_pick' => 'N',
                'receive_id' => $last_id,
                'receive_detail_id' => $receive_detail_id,
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('inventory', $dataInsertInventory);
        }

        // die;
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

    public function editProccess()
    {
        // var_dump($_POST);
        // die;


        // check input item < 0
        if (!isset($_POST['items']) || count($this->input->post('items')) < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item cannot be empty'
            );
            echo json_encode($response);
            exit;
        }

        // check into db if complted
        $check = $this->db->get_where('receive_header', array('receive_number' => $this->input->post('header')['spkNumber'], 'is_complete' => 'Y'));
        if ($check->num_rows() > 0) {
            $response = array(
                'success' => false,
                'message' => 'Inbound number already completed.'
            );
            echo json_encode($response);
            exit;
        }

        $order_ids = $this->input->post('items');
        $header = $this->input->post('header');

        $spk_number = $header['spkNumber'];

        $this->load->database();

        // Memulai transaksi
        $this->db->trans_start();

        // Ambil data header dari input POST

        // Periksa apakah `spk_number` ada di database
        $sqlCheck = "SELECT id FROM receive_header WHERE receive_number = ?";
        $existingSpk = $this->db->query($sqlCheck, array($spk_number))->row();



        if (!$existingSpk) {
            // Jika nomor surat jalan tidak ditemukan
            echo json_encode(array('success' => false, 'message' => 'Nomor Inbound tidak ditemukan.'));
            return;
        }


        $spk_id = $existingSpk->id;

        // Update data header
        $dataUpdateHeader = array(
            'ship_reff' => $header['loadNumber'],
            'po_number' => $header['poNumber'],
            'sj_number' => $header['SJNumber'],
            'invoice_number' => $header['invoiceNumber'],
            'receive_date' => $header['orderDate'],
            'receive_time' => $header['orderTime'],
            'unloading_date' => $header['spkDate'],
            'supplier_id' => $header['supplierID'],
            'transporter_id' => $header['transporterID'],
            'truck_type' => $header['truckType'],
            'receiving_status' => $header['receiveStatus'],
            'truck_arival_date' => $header['truckArivalDate'],
            'truck_arival_time' => $header['truckArivalTime'],
            'start_loading' => $header['startLoading'],
            'finish_loading' => $header['finishLoading'],
            'truck_no' => $header['truckNo'],
            'driver_name' => $header['driverName'],
            'driver_phone' => $header['driverPhone'],
            'container_no' => $header['containerNo'],
            'remarks' => $header['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $_SESSION['user_data']['username']
        );

        $this->db->where('id', $spk_id);
        $this->db->update('receive_header', $dataUpdateHeader);

        // Delete data detail
        $this->db->where('receive_id', $spk_id);
        $this->db->delete('receive_detail');

        // Delete data inventory
        $this->db->where('receive_id', $spk_id);
        $this->db->delete('inventory');

        foreach ($order_ids as $order_id) {
            $dataInsertDetail = array(
                'receive_id' => $spk_id,
                'receive_number' => $spk_number,
                // 'lpn_number' => $order_id['lpn_number'] == 'auto' ? $this->receiving_m->generate_lpn() : $order_id['lpn_number'],
                'item_code' => $order_id['item_code'],
                'qty' => $order_id['quantity'],
                'receive_location' => $order_id['rcv_loc'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('receive_detail', $dataInsertDetail);

            $receive_detail_id = $this->db->insert_id();
            $dataInsertInventory = array(
                'location' => $order_id['rcv_loc'],
                'item_code' => $order_id['item_code'],
                'on_hand' => $order_id['quantity'],
                'allocated' => 0,
                'available' => 0,
                'in_transit' => $order_id['quantity'],
                'receive_date' => $header['orderDate'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'is_pick' => 'N',
                'receive_id' => $spk_id,
                'receive_detail_id' => $receive_detail_id,
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('inventory', $dataInsertInventory);
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            // Kembalikan nomor surat jalan yang telah diedit ke frontend
            echo json_encode(array('success' => true, 'nomor_surat_jalan' => $spk_number));
        }
    }

    public function receivingList()
    {
        $data = array(
            'title' => 'Receiving',
            'receive' => $this->receiving_m->receiveList()
        );
        $this->render('receiving/list_receiving/index', $data);
    }

    public function putaway()
    {
        $data = array(
            'title' => 'Putaway',
        );
        $this->render('receiving/putaway', $data);
    }

    public function completeReceive() {}
}
