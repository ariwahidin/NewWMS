<?php defined('BASEPATH') or exit('No direct script access allowed');

class Picking extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['picking_m', 'order_m', 'truck_m', 'ekspedisi_m', 'customer_m', 'shipment_m']);
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
        $check = $this->db->get_where('shipment_header', array('shipment_number' => $shipment_number));
        if ($check->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment Number ' . $shipment_number . ' not found'
            );
            echo json_encode($response);
            exit;
        }
        $shipment_id = $check->row()->id;
        $picking_detail = $this->db->get_where('picking_detail', array('shipment_id' => $shipment_id));

        if ($picking_detail->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Picking detail not found'
            );
            echo json_encode($response);
            exit;
        }

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


        $dataUpdatePicking = array(
            'picking_id' => $picking_id,
            'picking_number' => $picking_number,
        );

        $this->db->where('shipment_id', $shipment_id);
        $this->db->update('picking_detail', $dataUpdatePicking);


        $dataUpdateShipmentHeader = array(
            'is_complete' => 'Y',
        );

        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_header', $dataUpdateShipmentHeader);


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

    public function completePicking()
    {
        $post = $this->input->post();
        $pick_no = $post['pick_no'];
        $picking = $this->db->get_where('picking_header', array('picking_number' => $pick_no));

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

        $this->db->trans_start();
        $data = array(
            'is_complete' => 'Y',
            'complete_at' => date('Y-m-d H:i:s'),
            'complete_by' => $_SESSION['user_data']['username'],
        );
        $this->db->where('id', $picking_id);
        $this->db->update('picking_header', $data);

        $picking_detail = $this->db->get_where('picking_detail', array('picking_id' => $picking_id));
        foreach ($picking_detail->result() as $row) {
            $this->db->where('id', $row->inventory_id);
            $this->db->set('allocated', 'allocated - ' . $row->qty, FALSE);
            $this->db->set('on_hand', 'on_hand - ' . $row->qty, FALSE);
            $this->db->update('inventory');
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

        // var_dump($_POST);
        // die;
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
