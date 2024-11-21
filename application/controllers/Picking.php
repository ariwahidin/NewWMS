<?php defined('BASEPATH') or exit('No direct script access allowed');

class Picking extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['picking_m', 'order_m', 'truck_m', 'ekspedisi_m', 'customer_m', 'shipment_m', 'inventory_m']);
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
            'title' => isset($_GET['edit']) && $_GET['pick_no'] ? 'Picking ' . $_GET['pick_no'] : 'Create Picking',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'customer' => $this->customer_m->getAllItem(),
            'type' => $this->order_m->getOrderType()
        );

        if (isset($_GET['edit']) && isset($_GET['pick_no'])) {
            $pick_no = $_GET['pick_no'];
            $order = $this->picking_m->getPickingList($pick_no);
            $shipment_detail = $this->picking_m->getShipmentDetailByPickingNumber($pick_no);
            $picking_detail = $this->picking_m->getPickingDetail($pick_no);
            if ($order->num_rows() > 0) {
                $data['order'] = $order->row();
                $data['shipment_detail'] = $shipment_detail;
                $data['picking_detail'] = $picking_detail;
            } else {
                echo "Not Found";
                exit;
            }
        }
        $this->render('picking/index', $data);
    }

    public function list()
    {
        $data = array(
            'title' => 'Picking',
            'picking' => $this->picking_m->getPickingList()
        );
        $this->render('picking/list_picking/index', $data);
    }

    public function create()
    {
        $post = $this->input->post();
        $shipment_number = $post['ob_no'];
        $shipment_header = $this->db->get_where('shipment_header', array('shipment_number' => $shipment_number))->row();

        if (!$shipment_header) {
            $response = array(
                'success' => false,
                'message' => 'Shipment not found'
            );
            echo json_encode($response);
            exit;
        }

        if ($shipment_header->is_complete == 'Y') {
            $response = array(
                'success' => false,
                'message' => 'Shipment already complete'
            );
            echo json_encode($response);
            exit;
        }

        $shipment_id = $shipment_header->id;
        $trans_id_shipment = $shipment_header->trans_id;



        $whs_code = $_SESSION['user_data']['warehouse'];
        $sql = "SELECT whs_code, item_code, SUM(qty) as qty FROM shipment_detail 
                WHERE shipment_number = ?
                AND whs_code = ?
                GROUP BY whs_code, item_code";
        $shipment_detail = $this->db->query($sql, array($shipment_number, $whs_code));

        // check item in inventory is available > requested qty
        foreach ($shipment_detail->result() as $item) {

            $qty = $item->qty;
            $item_code = $item->item_code;

            // sum available qty
            $this->db->select_sum('available');
            $this->db->where('whs_code', $whs_code);
            $this->db->where('item_code', $item_code);
            $this->db->where('is_pick', 'Y');
            $available = $this->db->get('inventory')->row()->available;

            if ($available == null) {
                $response = array(
                    'success' => false,
                    'message' => 'Item Code ' . $item_code . ' is not available'
                );
                echo json_encode($response);
                exit;
            }

            if ($qty > $available) {
                $response = array(
                    'success' => false,
                    'message' => 'Stock ' . $item_code . ' is not enough'
                );
                echo json_encode($response);
                exit;
            }
        }

        // echo "Success";
        // exit;


        $this->db->trans_start();

        $transID = $this->trans_m->getTransID('Picking');

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'PI'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 picking_number FROM picking_header 
                    WHERE picking_number LIKE ? 
                    ORDER BY picking_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->picking_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $picking_number = $prefix . $currentYearMonth . $newNumber;

        $dataPickingHeader = array(
            'trans_id' => $transID,
            'picking_number' => $picking_number,
            'shipment_id' => $shipment_id,
            'shipment_number' => $shipment_number,
            'is_complete' => 'N',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username'],
        );
        $this->db->insert('picking_header', $dataPickingHeader);

        $picking_id = $this->db->insert_id();


        $shipment_details = $this->db->get_where('shipment_detail', array('shipment_number' => $shipment_number))->result_array();

        foreach ($shipment_details as $row_shipment_detail) {
            $row_shipment_detail['picking_id'] = $picking_id;
            $row_shipment_detail['picking_number'] = $picking_number;
            $this->getAvailableInventory($row_shipment_detail);
        }


        $dataUpdateShipmentHeader = array(
            'is_complete' => 'Y',
        );

        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_header', $dataUpdateShipmentHeader);


        // foreach ($shipment_details as $row2) {
        //     $dataHistory = array(
        //         'trans_id' => $trans_id_shipment,
        //         'reff_no' => $shipment_number,
        //         'location' => 'SHIPPLAN',
        //         'item_code' => $row2['item_code'],
        //         'qty' => $row2['qty'],
        //         'created_by' => $_SESSION['user_data']['username']
        //     );
        //     $this->db->insert('transaction_history', $dataHistory);
        // }


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = array(
                'success' => false,
                'message' => 'Something went wrong'
            );
            echo json_encode($response);
            exit;
        } else {
            $response = array(
                'success' => true,
                'message' => 'Picking has been created'
            );
            echo json_encode($response);
            exit;
        }
    }



    private function getAvailableInventory($row_shipment_detail)
    {

        // var_dump($row_shipment_detail);
        // die;


        $shipment_id = $row_shipment_detail['shipment_id'];
        $shipment_number = $row_shipment_detail['shipment_number'];
        $shipment_detail_id = $row_shipment_detail['id'];
        $picking_id = $row_shipment_detail['picking_id'];
        $picking_number = $row_shipment_detail['picking_number'];

        $item_code = $row_shipment_detail['item_code'];

        $whs_code = $_SESSION['user_data']['warehouse'];
        $this->db->where('whs_code', $whs_code);
        $this->db->where('item_code', $item_code);
        $this->db->where('available >', '0');
        $this->db->where('is_pick =', 'Y');
        $this->db->order_by('receive_date', 'asc');
        $query = $this->db->get('inventory');

        $qty_request = (float)$row_shipment_detail['qty'];

        foreach ($query->result() as $row) {
            $qty_pick = $row->available < $qty_request ? $row->available : $qty_request;
            $row->shipment_id =  $shipment_id;
            $row->shipment_detail_id = $shipment_detail_id;
            $row->shipment_number = $shipment_number;
            $row->qty_picking = (float)$qty_pick;
            $row->picking_id = $picking_id;
            $row->picking_number = $picking_number;

            $this->allocatedInventory($row, $qty_pick);
            $this->createPickingDetail($row);

            $qty_request -= $qty_pick;
            if ($qty_request == 0) {
                break;
            }
        }
    }

    private function createPickingDetail($row)
    {
        // var_dump($row);
        // die;

        $row_insert = array(
            'picking_id' => $row->picking_id,
            'picking_number' => $row->picking_number,
            'shipment_id' => $row->shipment_id,
            'shipment_number' => $row->shipment_number,
            'whs_code' => $row->whs_code,
            'inventory_id' => $row->id,
            'location' => $row->location,
            'to_location' => 'SHIPDOCK',
            'lpn_id' => $row->lpn_id,
            'lpn_number' => $row->lpn_number,
            'grn_id'  => $row->grn_id,
            'grn_number' => $row->grn_number,
            'item_code'  => $row->item_code,
            'qty' => $row->qty_picking,
            'expiry_date' => $row->expiry_date,
            'qa' => $row->qa,
            'receive_date' => $row->receive_date,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username']
        );

        $this->db->insert('picking_detail', $row_insert);

        $row->picking_detail_id = $this->db->insert_id();
        $this->createInventoryShipDock($row);
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
        $row_insert = array(
            'whs_code' => $row->whs_code,
            'location' => 'SHIPDOCK',
            'grn_id'  => $row->grn_id,
            'grn_number' => $row->grn_number,
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
            'picking_id' => $row->picking_id,
            'picking_detail_id' => $row->picking_detail_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username']
        );
        $this->db->insert('inventory', $row_insert);
    }




    public function completePicking()
    {
        $post = $this->input->post();

        // var_dump($post);
        // die;

        $pick_no = $post['pick_no'];
        $picking = $this->db->get_where('picking_header', array('picking_number' => $pick_no));
        $trans_id_picking = $picking->row()->trans_id;

        if ($picking->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Picking Number ' . $pick_no . ' not found'
            );
            echo json_encode($response);
            exit;
        }

        if ($picking->row()->is_complete == 'Y') {
            $response = array(
                'success' => false,
                'message' => 'Picking Number ' . $pick_no . ' already completed'
            );
            echo json_encode($response);
            exit;
        }

        $picking_id = $picking->row()->id;
        $picking_detail = $this->db->get_where('picking_detail', array('picking_id' => $picking_id));

        if ($picking_detail->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Picking Number ' . $pick_no . ' not found'
            );
            echo json_encode($response);
            exit;
        }


        $this->db->trans_start();
        $data = array(
            'is_complete' => 'Y',
            'complete_at' => date('Y-m-d H:i:s'),
            'complete_by' => $_SESSION['user_data']['username'],
        );
        $this->db->where('id', $picking_id);
        $this->db->update('picking_header', $data);

        // var_dump($picking_detail->result());
        // die;

        foreach ($picking_detail->result() as $row) {
            $this->db->where('id', $row->inventory_id);
            $this->db->set('allocated', 'allocated - ' . $row->qty, FALSE);
            $this->db->set('on_hand', 'on_hand - ' . $row->qty, FALSE);
            $this->db->update('inventory');
        }

        foreach ($picking_detail->result() as $row1) {
            $this->db->where('picking_id', $row1->picking_id);
            $this->db->where('picking_detail_id', $row1->id);
            $this->db->set('in_transit', 'in_transit - ' . $row1->qty, FALSE);
            $this->db->set('available', 'available + ' . $row1->qty, FALSE);
            $this->db->set('on_hand', 'on_hand + ' . $row1->qty, FALSE);
            $this->db->update('inventory');
        }



        // pencatatan history
        // foreach ($picking_detail->result() as $row2) {
        //     $dataHistory = array(
        //         'trans_id' => $trans_id_picking,
        //         'reff_no' => $pick_no,
        //         'location' => $row2->to_location,
        //         'item_code' => $row2->item_code,
        //         'lpn_id' => $row2->lpn_id,
        //         'lpn_number' => $row2->lpn_number,
        //         'qty' => $row2->qty,
        //         'created_by' => $_SESSION['user_data']['username']
        //     );
        //     $this->db->insert('transaction_history', $dataHistory);
        // }


        // pencatatan inventory movement
        foreach ($picking_detail->result() as $mv) {
            $dataHistory = array(
                'whs_code' => $mv->whs_code,
                'trans_id' => $trans_id_picking,
                'lpn_id' => $mv->lpn_id,
                'lpn_number' => $mv->lpn_number,
                'item_code' => $mv->item_code,
                'from_location' => $mv->location,
                'to_location' => $mv->to_location,
                'qty' => $mv->qty,
                'reff_no' => $pick_no,
                'type' => 'PICKING',
                'created_by' => $_SESSION['user_data']['username']
            );
            $this->db->insert('inventory_movement', $dataHistory);
        }



        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = array(
                'success' => false,
                'message' => 'Something went wrong'
            );
            echo json_encode($response);
            exit;
        } else {
            $response = array(
                'success' => true,
                'message' => 'Picking has been completed'
            );
            echo json_encode($response);
            exit;
        }
    }

    public function getItems()
    {
        $shipment_current = null;

        if (isset($_POST['ob_no'])) {
            $shipment_current = $this->picking_m->getPickingDetail($_POST['pick_no'])->result_array();
        }

        $listDO = $this->shipment_m->getAllItemAvailable()->result_array();

        $data = array(
            'shipments' => $listDO,
            'shipment_current' => $shipment_current
        );

        echo json_encode($data);
    }

    public function printPickingSheet()
    {

        $pick_no = $this->input->get('pick_no');
        $ship_no = $this->input->get('ship_no');
        $picking = $this->picking_m->getPickingList($pick_no)->row();
        $picking_detail = $this->picking_m->getPickingDetail($pick_no)->result_array();

        $data = array(
            'header' => $this->shipment_m->getShipment($ship_no)->row(),
            'shipment_detail' => $this->shipment_m->getShipmentDetail($ship_no),
            'picking' => $picking,
            'picking_detail' => $picking_detail
        );
        $this->load->view('picking/picking_sheet', $data);
    }
}
