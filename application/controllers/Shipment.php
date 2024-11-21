<?php defined('BASEPATH') or exit('No direct script access allowed');

class Shipment extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'ekspedisi_m', 'receiving_m', 'trans_m', 'item_m', 'customer_m', 'shipment_m', 'picking_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function List()
    {
        $isConfirm = null;

        if (isset($_GET['includeConfirm']) && $_GET['includeConfirm'] == 'true') {
            $isConfirm = true;
        }

        $shipment = $this->shipment_m->getShipment(null, $isConfirm);

        $data = array(
            'title' => 'Shipment',
            'shipment' => $shipment
        );
        $this->render('shipment/list_shipment/index', $data);
    }

    public function index()
    {
        $data = array(
            'title' => isset($_GET['edit']) && $_GET['ob'] ? 'Shipment ' . $_GET['ob'] : 'Create Shipment',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'customer' => $this->customer_m->getAllItem(),
            'type' => $this->order_m->getOrderType()
        );

        if (isset($_GET['edit']) && isset($_GET['ob'])) {
            $ob_no = $_GET['ob'];
            $order = $this->shipment_m->getShipment($ob_no);
            if ($order->num_rows() > 0) {
                $data['order'] = $order->row();
            } else {
                echo "Not Found";
                exit;
            }
        }
        $this->render('shipment/index', $data);
    }

    public function getItems()
    {

        $shipment_current = null;

        if (isset($_POST['ob_no'])) {
            $shipment_current = $this->shipment_m->getShipmentDetail($_POST['ob_no'])->result_array();
        }

        $listDO = $this->shipment_m->getAllItemAvailable()->result_array();

        $data = array(
            'shipments' => $listDO,
            'shipment_current' => $shipment_current
        );

        echo json_encode($data);
    }

    public function createProccess()
    {
        date_default_timezone_set('Asia/Jakarta');
        $shipReff = $this->input->post('header')['shipReff'];
        $whs_code = $_SESSION['user_data']['warehouse'];

        $check = $this->db->get_where('shipment_header', array('ship_reff' => $shipReff));
        if ($check->num_rows() > 0) {
            $response = array(
                'success' => false,
                'message' => 'Shipment Document already exist'
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($_POST['items']) || count($this->input->post('items')) < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item cannot be empty'
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
        $transID = $this->trans_m->getTransID('Shipment');

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'OB'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 shipment_number FROM shipment_header 
                    WHERE shipment_number LIKE ? 
                    ORDER BY shipment_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->shipment_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $nomorSuratJalan = $prefix . $currentYearMonth . $newNumber;

        $dataInsertHeader = array(
            'whs_code' => $whs_code,
            'trans_id' => $transID,
            'shipment_number' => $nomorSuratJalan,
            'ship_reff' => $header['shipReff'],
            'do_number' => $header['shipReff'],

            'sj_number' => $header['SJNumber'],
            'is_complete' => 'N',
            'shipment_date' => $header['shipmentDate'],
            'shipment_time' => $header['shipmentTime'],

            'start_loading' => $header['startLoading'],
            'finish_loading' => $header['finishLoading'],
            'ship_request_date' => $header['shipRequestDate'],
            'customer_id' => $header['customerId'],
            'transporter_id' => $header['transporterID'],

            'print_do_date' => $header['printDODate'],
            'print_do_time' => $header['printDOTime'],
            'truck_type' => $header['truckType'],
            'truck_arival_date' => $header['truckArivalDate'],
            'truck_arival_time' => $header['truckArivalTime'],

            'truck_no' => $header['truckNo'],
            'driver_name' => $header['driverName'],
            'driver_phone' => $header['driverPhone'],
            'remarks' => $header['remarks'],
            'created_by' => $_SESSION['user_data']['username']
        );

        $this->db->insert('shipment_header', $dataInsertHeader);
        $last_id = $this->db->insert_id();

        foreach ($order_ids as $order_id) {

            // Konversi quantity uom to pcs
            $uoms = explode(',', $order_id['uom']);
            $uom = $uoms[0];
            $qty_in = $order_id['quantity'];
            $qty_uom = (float)$uoms[1];
            $qty = $qty_in * $qty_uom;
            

            $dataInsertDetail = array(
                'whs_code' => $whs_code,
                'shipment_id' => $last_id,
                'shipment_number' => $nomorSuratJalan,
                'item_code' => $order_id['item_code'],
                'qty_in' => $qty_in,
                'qty_uom' => $qty_uom,
                'uom' => $uom,
                'qty' => $qty,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $_SESSION['user_data']['username']
            );
            $this->db->insert('shipment_detail', $dataInsertDetail);
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Shipment creation failed.'));
        } else {
            // Kembalikan nomor surat jalan ke frontend
            echo json_encode(array('success' => true, 'message' => 'Shipment created successfully.', 'nomorSuratJalan' => $nomorSuratJalan));
        }
    }

    private function getAvailableInventory($arrayShipmentDetail)
    {
        $whs_code = $_SESSION['user_data']['warehouse'];
        $this->db->where('whs_code', $whs_code);
        $this->db->where('item_code', $arrayShipmentDetail['item_code']);
        $this->db->where('available >', '0');
        $this->db->where('is_pick =', 'Y');
        $this->db->order_by('receive_date', 'asc');
        $query = $this->db->get('inventory');

        $qty_request = (float)$arrayShipmentDetail['qty'];

        foreach ($query->result() as $row) {
            $qty_pick = $row->available < $qty_request ? $row->available : $qty_request;

            $this->allocatedInventory($row, $qty_pick);
            $row->shipment_id = $arrayShipmentDetail['shipment_id'];
            $row->shipment_detail_id = $arrayShipmentDetail['shipment_detail_id'];
            $row->shipment_number = $arrayShipmentDetail['shipment_number'];
            $row->qty_picking = (float)$qty_pick;

            $this->createInventoryShipDock($row);
            $qty_request -= $qty_pick;

            if ($qty_request == 0) {
                break;
            }
        }
    }

    private function allocatedInventory($row, $qty_pick)
    {
        $this->db->where('id', $row->id);
        $this->db->set('allocated', 'allocated + ' . $qty_pick, FALSE);
        $this->db->set('available', 'available - ' . $qty_pick, FALSE);
        $this->db->update('inventory');
    }

    private function createInventoryShipDock($row)
    {
        // var_dump($row);
        // die;
        $row_insert = array(
            'whs_code' => $row->whs_code,
            'location' => 'SHIPDOCK',
            'item_code'  => $row->item_code,
            'on_hand' => 0,
            'allocated' => 0,
            'available' => 0,
            'in_transit' => $row->qty_picking,
            'receive_date' => $row->receive_date,
            'expiry_date' => $row->expiry_date,
            'qa' => $row->qa,
            'lpn_id' => $row->lpn_id,
            'lpn_number' => $row->lpn_number,
            'is_pick' => 'N',
            'shipment_id' => $row->shipment_id,
            'shipment_detail_id' => $row->shipment_detail_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username']
        );
        $this->db->insert('inventory', $row_insert);
    }

    private function createPickingDetail($row)
    {
        var_dump($row);
        die;

        $row_insert = array(
            'shipment_id' => $row->shipment_id,
            'shipment_number' => $row->shipment_number,
            'inventory_id' => $row->id,
            'location' => $row->location,
            'lpn_id' => $row->lpn_id,
            'lpn_number' => $row->lpn_number,
            'item_code'  => $row->item_code,
            'qty' => $row->qty_picking,
            'expiry_date' => $row->expiry_date,
            'qa' => $row->qa,
            'receive_date' => $row->receive_date,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username']
        );


        $this->db->insert('picking_detail', $row_insert);
    }

    public function editProccess()
    {

        if (!isset($_POST['items']) || count($this->input->post('items')) < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item cannot be empty'
            );
            echo json_encode($response);
            exit;
        }

        $this->db->trans_start();

        $order_ids = $this->input->post('items');
        $header = $this->input->post('header');
        $shipmentNumber = $this->input->post('header')['shipmentNumber'];

        $check = $this->db->get_where('shipment_header', array('shipment_number' => $shipmentNumber));
        if ($check->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment Number ' . $shipmentNumber . ' not found'
            );
            echo json_encode($response);
            exit;
        }
        $shipment_id = $check->row()->id;

        // Update data header
        $dataUpdateHeader = array(
            'shipment_date' => $header['shipmentDate'],
            'shipment_time' => $header['shipmentTime'],

            'start_loading' => $header['startLoading'],
            'finish_loading' => $header['finishLoading'],
            'ship_request_date' => $header['shipRequestDate'],
            'customer_id' => $header['customerId'],
            'transporter_id' => $header['transporterID'],

            'print_do_date' => $header['printDODate'],
            'print_do_time' => $header['printDOTime'],

            'truck_type' => $header['truckType'],

            'truck_arival_date' => $header['truckArivalDate'],
            'truck_arival_time' => $header['truckArivalTime'],

            'truck_no' => $header['truckNo'],
            'driver_name' => $header['driverName'],
            'driver_phone' => $header['driverPhone'],
            'remarks' => $header['remarks'],
            'updated_by' => $_SESSION['user_data']['username'],
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_header', $dataUpdateHeader);

        // Delete data detail
        $this->db->where('shipment_id', $shipment_id);
        $this->db->delete('shipment_detail');

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
                'shipment_id' =>  $shipment_id,
                'shipment_number' => $shipmentNumber,
                'item_code' => $order_id['item_code'],
                'qty_in' => $qty_in,
                'qty_uom' => $qty_uom,
                'uom' => $uom,
                'qty' => $qty,
                'created_by' => $_SESSION['user_data']['username']
            );
            $this->db->insert('shipment_detail', $dataInsertDetail);
        }

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Something went wrong.'));
        } else {
            // Kembalikan nomor surat jalan yang telah diedit ke frontend
            echo json_encode(array('success' => true, 'message' => 'Shipment has been updated.'));
        }
    }
}
