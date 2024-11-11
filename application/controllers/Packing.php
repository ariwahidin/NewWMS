<?php defined('BASEPATH') or exit('No direct script access allowed');

class Packing extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['packing_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function list()
    {
        $data = array(
            'title' => 'Packing ',
            'packing' => $this->packing_m->getSummaryPacking()
        );
        $this->render('packing/list_packing/index', $data);
    }

    public function createNew()
    {
        $data = array(
            'title' => isset($_GET['pack']) ? 'Packing ' . $_GET['pack'] : 'Packing'
        );
        $this->render('packing/scan', $data);
    }

    public function store()
    {
        $post = $this->input->post();
        $shipment_number = $post['shipmentNo'];
        $shipment = $this->packing_m->getShipmentComplete($shipment_number);

        if ($shipment->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment number not already exists to packing'
            );
            echo json_encode($response);
            return;
        }

        $shipment_id = $shipment->row()->shipment_id;
        $packing = $this->packing_m->getPackingHeaderByShipment($shipment_number);

        if ($packing->num_rows() > 0) {
            $this->storeDetail($packing->row());
            return;
        }

        $this->db->trans_start();

        $transID = $this->trans_m->getTransID('Packing');

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'PG'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 packing_number FROM packing_header 
                    WHERE packing_number LIKE ? 
                    ORDER BY packing_number DESC";
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
        $packing_number = $prefix . $currentYearMonth . $newNumber;

        $dataPackingHeader = array(
            'trans_id' => $transID,
            'packing_number' => $packing_number,
            'shipment_id' => $shipment_id,
            'shipment_number' => $shipment_number,
            'is_complete' => 'N',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username'],
        );
        $this->db->insert('packing_header', $dataPackingHeader);
        $packing_id = $this->db->insert_id();

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
                'message' => 'Packing has been created'
            );
            echo json_encode($response);
            exit;
        }
    }

    private function storeDetail($packing)
    {
        $post = $this->input->post();
        $shipment_id = $packing->shipment_id;
        $shipment_number = $packing->shipment_number;
        $item_code = $post['itemCode'];
        $item = $this->packing_m->getItemShipment($shipment_number, $item_code);




        if ($item->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item Code not already exists'
            );
            echo json_encode($response);
            return;
        }

        $item_packed = $this->packing_m->getItemPacked($shipment_number, $item_code);


        $in_qty = $post['qty'];
        $exists_qty = $item_packed->row()->total_qty ?? 0;
        $predic_qty = $in_qty + $exists_qty;
        $req_qty = $item->row()->total_qty;

        if ($predic_qty > $req_qty) {
            $response = array(
                'success' => false,
                'message' => 'The qty of this item exceeds the shipping request'
            );
            echo json_encode($response);
            exit;
        }

        $this->db->trans_start();
        $dataPackingHeader = array(
            'packing_id' => $packing->id,
            'packing_number' => $packing->packing_number,
            'shipment_id' => $shipment_id,
            'shipment_number' => $shipment_number,
            'ctn' => $post['cartonNo'],
            'item_code' => $item_code,
            'qty' => $post['qty'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_data']['username'],
        );
        $this->db->insert('packing_detail', $dataPackingHeader);

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
                'message' => 'Item has been successfully created'
            );
            echo json_encode($response);
            exit;
        }
    }

    public function getItemToPacking()
    {
        $post = $this->input->post();
        $shipment_number = $post['shipment_number'];
        $item_code = $post['item_code'];
        $item = $this->packing_m->getItemShipment($shipment_number, $item_code);


        if ($item->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Item Code not already exists'
            );
            echo json_encode($response);
            return;
        }

        $response = array(
            'success' => true,
            'data' => $item->row()
        );

        echo json_encode($response);
    }

    public function getItemPackingDetail()
    {
        $post = $this->input->post();
        $shipment_number = $post['shipment_number'];
        $item = $this->packing_m->getPackingDetailByShipment($shipment_number);
        $qty = $this->packing_m->getQtyProgress($shipment_number);
        $response = array(
            'success' => true,
            'data' => $item->result(),
            'qty' => $qty->row()
        );

        echo json_encode($response);
    }

    public function deleteItem()
    {
        $post = $this->input->post('item');
        $id = $post['id'];
        $delete = $this->packing_m->deleteItem($id);
        if ($this->db->affected_rows() > 0) {
            $response = array(
                'success' => true,
                'message' => 'Item has been deleted succesfully'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Failed to delete this item'
            );
        }
        echo json_encode($response);
    }

    public function getItemShipment()
    {
        $post = $this->input->post();
        $shipment_number = $post['shipment_number'];
        $item = $this->packing_m->getItemShipmentByShipment($shipment_number);
        $response = array(
            'success' => true,
            'data' => $item->result()
        );
        echo json_encode($response);
    }


    public function create() {}
}