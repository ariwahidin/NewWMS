<?php defined('BASEPATH') or exit('No direct script access allowed');

class CrossDock extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'crossdock_m', 'ekspedisi_m', 'receiving_m', 'shipment_m', 'trans_m', 'item_m', 'supplier_m', 'customer_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function create()
    {
        $data = array(
            'title' => isset($_GET['edit']) && $_GET['ib'] ? 'Cross Docking ' . $_GET['ib'] : 'Create Cross Docking',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'supplier' => $this->supplier_m->getAllItem(),
            'customer' => $this->customer_m->getAllItem(),
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
        $this->render('crossdock/create', $data);
    }

    public function getUom()
    {
        $sql = "SELECT * FROM item_uom ORDER BY converted_qty ASC";
        $query = $this->db->query($sql);
        $response = array(
            'success' => true,
            'data' => $query->result_array()
        );
        echo json_encode($response);
    }

    public function edit()
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
        $this->render('receiving/create', $data);
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
        // die;

        if (!isset($_POST['items']) || count($this->input->post('items')) < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item cannot be empty'
            );
            echo json_encode($response);
            exit;
        }

        $po_number = $this->input->post('headerReceive')['poNumber'];

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
        $headerReceive = $this->input->post('headerReceive');

        //get TransID
        $transID = $this->trans_m->getTransID('Receiving');
        $receiveNumber = $this->receiving_m->getReceiveNumber();

        $dataInsertHeaderReceive = array(
            'trans_id' => $transID,
            'receive_number' => $receiveNumber,
            'ship_reff' => $headerReceive['loadNumber'],
            'po_number' => $headerReceive['poNumber'],
            'sj_number' => $headerReceive['SJNumber'],
            'invoice_number' => $headerReceive['invoiceNumber'],
            'receive_date' => $headerReceive['orderDate'],
            'receive_time' => $headerReceive['orderTime'],
            'unloading_date' => $headerReceive['spkDate'],
            'supplier_id' => $headerReceive['supplierID'],
            'transporter_id' => $headerReceive['transporterID'],
            'truck_type' => $headerReceive['truckType'],
            'receiving_status' => $headerReceive['receiveStatus'],
            'truck_arival_date' => $headerReceive['truckArivalDate'],
            'truck_arival_time' => $headerReceive['truckArivalTime'],
            'start_loading' => $headerReceive['startLoading'],
            'finish_loading' => $headerReceive['finishLoading'],
            'truck_no' => $headerReceive['truckNo'],
            'driver_name' => $headerReceive['driverName'],
            'driver_phone' => $headerReceive['driverPhone'],
            'container_no' => $headerReceive['containerNo'],
            'remarks' => $headerReceive['remarks'],
            'is_cross_docking' => 'Y',
            'created_by' => $_SESSION['user_data']['username']
        );


        $this->db->insert('receive_header', $dataInsertHeaderReceive);
        $receive_id = $this->db->insert_id();

        foreach ($order_ids as $order_id) {


            // $lpn = $this->lpn_m->generate_lpn($value->id);
            // $lpn_id = $lpn['lpn_id'];
            // $lpn_number = $lpn['lpn_number'];
            // $this->db->set('lpn_id', $lpn['lpn_id']);
            // $this->db->set('lpn_number', $lpn['lpn_number']);
            // $this->db->where('id', $value->id);
            // $this->db->update('receive_detail');

            // Konversi quantity uom to pcs
            $uoms = explode(',', $order_id['uom']);
            $uom = $uoms[0];
            $qty_in = $order_id['quantity'];
            $qty_uom = (float)$uoms[1];
            $qty = $qty_in * $qty_uom;
            $whs_code = $_SESSION['user_data']['warehouse'];
            $dataInsertDetail = array(
                'whs_code' => $whs_code,
                'receive_id' => $receive_id,
                'receive_number' => $receiveNumber,
                'item_code' => $order_id['item_code'],
                'qty_in' => $qty_in,
                'qty_uom' => $qty_uom,
                'uom' => $uom,
                'qty' => $qty,
                'receive_location' => $order_id['rcv_loc'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('receive_detail', $dataInsertDetail);
            $receive_detail_id = $this->db->insert_id();

            $dataInsertInventory = array(
                'whs_code' => $whs_code,
                'location' => $order_id['rcv_loc'],
                'item_code' => $order_id['item_code'],
                'on_hand' => $qty,
                'allocated' => $qty,
                'available' => 0,
                'in_transit' => 0,
                'receive_date' => $headerReceive['orderDate'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'is_pick' => 'N',
                'receive_id' => $receive_id,
                'receive_detail_id' => $receive_detail_id,
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('inventory', $dataInsertInventory);

        }

        // Shipment
        $transIDShipment = $this->trans_m->getTransID('Shipment');
        $shipmentNumber = $this->shipment_m->getShipmentNumber();

        $headerShipment = $this->input->post('headerShipment');

        $dataInsertHeaderShipment = array(
            'trans_id' => $transIDShipment,
            'shipment_number' => $shipmentNumber,
            'ship_reff' => $headerShipment['shipReff'],

            'sj_number' => $headerShipment['sjNumberShipment'],
            'is_complete' => 'N',
            'is_cross_docking' => 'Y',
            'shipment_date' => $headerShipment['shipmentDate'],
            'shipment_time' => $headerShipment['shipmentTime'],

            'start_loading' => $headerShipment['startLoadingShipment'],
            'finish_loading' => $headerShipment['finishLoadingShipment'],
            'ship_request_date' => $headerShipment['shipRequestDate'],
            'customer_id' => $headerShipment['customerId'],
            'transporter_id' => $headerShipment['transporter_id_shipment'],

            'print_do_date' => $headerShipment['printDODateShipment'],
            'print_do_time' => $headerShipment['printDOTimeShipment'],
            'truck_type' => $headerShipment['truckTypeShipment'],
            'truck_arival_date' => $headerShipment['truckArivalDateShipment'],
            'truck_arival_time' => $headerShipment['truckArivalTimeShipment'],

            'truck_no' => $headerShipment['truckNoShipment'],
            'driver_name' => $headerShipment['driverNameShipment'],
            'driver_phone' => $headerShipment['driverPhoneShipment'],
            'remarks' => $headerShipment['remarksShipment'],
            'created_by' => $_SESSION['user_data']['username']
        );


        $this->db->insert('shipment_header', $dataInsertHeaderShipment);
        $shipment_id = $this->db->insert_id();

        foreach ($order_ids as $order_id) {

            // Konversi quantity uom to pcs
            $uoms = explode(',', $order_id['uom']);
            $uom = $uoms[0];
            $qty_in = $order_id['quantity'];
            $qty_uom = (float)$uoms[1];
            $qty = $qty_in * $qty_uom;
            $whs_code = $_SESSION['user_data']['warehouse'];

            $dataInsertShipmentDetail = array(
                'whs_code' => $whs_code,
                'shipment_id' => $shipment_id,
                'shipment_number' => $shipmentNumber,
                'item_code' => $order_id['item_code'],
                'qty_in' => $qty_in,
                'qty_uom' => $qty_uom,
                'uom' => $uom,
                'qty' => $qty,
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('shipment_detail', $dataInsertShipmentDetail);
            $shipment_detail_id = $this->db->insert_id();

            $dataInsertInventoryCrossDock = array(
                'whs_code' => $whs_code,
                'location' => $order_id['put_loc'],
                'item_code' => $order_id['item_code'],
                'on_hand' => 0,
                'allocated' => 0,
                'available' => 0,
                'in_transit' => $qty,
                'receive_date' => $headerReceive['orderDate'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'is_pick' => 'N',
                'shipment_id' => $shipment_id,
                'shipment_detail_id' => $shipment_detail_id,
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('inventory', $dataInsertInventoryCrossDock);
        }


        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            // Kembalikan nomor surat jalan ke frontend
            echo json_encode(array('success' => true, 'message' => 'Data has been sava successfully'));
        }
    }

    public function editProccess()
    {

        var_dump($_POST);
        die;

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

            // Konversi quantity uom to pcs
            $uoms = explode(',', $order_id['uom']);
            $uom = $uoms[0];
            $qty_in = $order_id['quantity'];
            $qty_uom = (float)$uoms[1];
            $qty = $qty_in * $qty_uom;
            $whs_code = $_SESSION['user_data']['warehouse'];

            $dataInsertDetail = array(
                'whs_code' => $whs_code,
                'receive_id' => $spk_id,
                'receive_number' => $spk_number,
                'item_code' => $order_id['item_code'],
                'qty_in' => $qty_in,
                'qty_uom' => $qty_uom,
                'uom' => $uom,
                'qty' => $qty,
                'receive_location' => $order_id['rcv_loc'],
                'expiry_date' => $order_id['expiry'],
                'qa' => $order_id['qa'],
                'created_by' => $_SESSION['user_data']['username']
            );

            $this->db->insert('receive_detail', $dataInsertDetail);

            $receive_detail_id = $this->db->insert_id();
            $dataInsertInventory = array(
                'whs_code' => $whs_code,
                'location' => $order_id['rcv_loc'],
                'item_code' => $order_id['item_code'],
                'on_hand' => 0,
                'allocated' => 0,
                'available' => 0,
                'in_transit' => $qty,
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

    public function index()
    {

        $isConfirm = null;

        if (isset($_GET['includeConfirm']) && $_GET['includeConfirm'] == 'true') {
            $isConfirm = true;
        }

        $receiving = $this->crossdock_m->list($isConfirm);
        $data = array(
            'title' => 'Cross Docking List',
            'receive' => $receiving
        );
        $this->render('crossdock/list/index', $data);
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
