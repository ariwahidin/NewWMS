<?php defined('BASEPATH') or exit('No direct script access allowed');

class ShippingLoading extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['packing_m', 'shipment_m', 'picking_m', 'shipping_load_m']);
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
            'title' => 'Shipping Loading',
        );
        $this->render('shipping_loading/index', $data);
    }


    // public function searchShipment()
    // {
    //     $post  = $this->input->post(null, true);

    //     $shipment_number = $post['shipment_number'];
    //     $item_code = $post['item_code'];
    //     $items = $this->packing_scan_m->getPickingDetailByShipment($shipment_number, $item_code);

    //     if ($items->num_rows() < 1) {
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Shipment number not found'
    //         );
    //         echo json_encode($response);
    //         return;
    //     }

    //     $response = array(
    //         'success' => true,
    //         'data' => $items->result()
    //     );

    //     echo json_encode($response);
    // }

    // public function savePacking()
    // {
    //     $post = $this->input->post();
    //     // var_dump($post);

    //     $shipment_number = $post['shipment_number'];
    //     $item_code = $post['item_code'];
    //     $qty_in = $post['qty_in'];
    //     $qty_uom = $post['qty_uom'];
    //     $qty = $qty_in * $qty_uom;
    //     $uom = $post['uom'];
    //     $items = $this->packing_scan_m->getPickingDetailByShipment($shipment_number, $item_code);

    //     if ($items->num_rows() < 1) {
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Shipment number not found'
    //         );
    //         echo json_encode($response);
    //         return;
    //     }

    //     $itemIsFull = $this->packing_scan_m->checkPackingIsFull($shipment_number, $item_code);

    //     foreach ($itemIsFull->result() as $is) {
    //         if ($is->qty_pack + $qty > $is->qty_pick) {
    //             $response = array(
    //                 'success' => false,
    //                 'message' => 'Item' . $item_code . ' is full packed'
    //             );
    //             echo json_encode($response);
    //             return;
    //         }
    //     }


    //     $this->db->trans_start();

    //     date_default_timezone_set('Asia/Jakarta');

    //     $dataToInsert = array(
    //         'shipment_number' => $shipment_number,
    //         'item_code' => $item_code,
    //         'qty_in' => $qty_in,
    //         'qty_uom' => $qty_uom,
    //         'uom' => $uom,
    //         'qty' => $qty,
    //         'carton' => $post['ctn_no'],
    //         'created_at' => date('Y-m-d H:i:s'),
    //         'created_by' => $this->session->userdata('user_data')['username']
    //     );

    //     $this->db->insert('packing_detail', $dataToInsert);
    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === FALSE) {
    //         $this->db->trans_rollback();
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Failed to save'
    //         );
    //         echo json_encode($response);
    //         return;
    //     } else {
    //         $this->db->trans_commit();
    //         $response = array(
    //             'success' => true,
    //             'message' => 'Successfully saved'
    //         );
    //         echo json_encode($response);
    //         return;
    //     }
    // }

    // public function getPackingDetail()
    // {
    //     $post = $this->input->post();

    //     $shipment_number = $post['shipment_number'];
    //     // $item_code = $post['item_code'];
    //     // $qty_in = $post['qty_in'];
    //     // $qty_uom = $post['qty_uom'];
    //     // $qty = $qty_in * $qty_uom;
    //     // $uom = $post['uom'];
    //     $items = $this->packing_scan_m->getPackingDetailByShipment($shipment_number);

    //     $picked = $this->packing_scan_m->getPickItemsByShipment($shipment_number);

    //     $progress = 0;
    //     $qty_picked = 0;
    //     $qty_packed = 0;

    //     if ($picked->num_rows() < 1) {
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Shipment number not found'
    //         );
    //         echo json_encode($response);
    //         return;
    //     }

    //     foreach ($picked->result() as $pick) {
    //         $qty_picked += $pick->qty_pick;
    //         $qty_packed += $pick->qty_pack;
    //     }
    //     $progress = ($qty_packed / $qty_picked) * 100;



    //     $progress = number_format($progress, 2);

    //     $response = array(
    //         'success' => true,
    //         'data' => $items->result(),
    //         'progress' => $progress
    //     );

    //     echo json_encode($response);
    // }

    // public function removePackingDetail()
    // {
    //     $post = $this->input->post();
    //     $id = $post['id'];
    //     $delete = $this->db->delete('packing_detail', array('id' => $id));
    //     if ($this->db->affected_rows() > 0) {
    //         $response = array(
    //             'success' => true,
    //             'message' => 'Item has been deleted succesfully'
    //         );
    //     } else {
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Failed to delete this item'
    //         );
    //     }
    //     echo json_encode($response);
    // }

    // public function getItemsPicking()
    // {
    //     $post = $this->input->post();
    //     $shipment_number = $post['shipment_number'];
    //     $items = $this->packing_scan_m->getPickItemsByShipment($shipment_number);
    //     $response = array(
    //         'success' => true,
    //         'data' => $items->result()
    //     );
    //     echo json_encode($response);
    // }

    // public function editPacking()
    // {
    //     date_default_timezone_set('Asia/Jakarta');
    //     $post = $this->input->post();
    //     $dataUpdate = array(
    //         'item_code' => $post['edit_item_code'],
    //         'qty_in' => $post['edit_qty_in'],
    //         'qty_uom' => $post['edit_qty_uom'],
    //         'qty' => $post['edit_qty_in'] * $post['edit_qty_uom'],
    //         'uom' => $post['edit_uom'],
    //         'carton' => $post['edit_ctn_no'],
    //         'updated_at' => date('Y-m-d H:i:s'),
    //         'updated_by' => $this->session->userdata('user_data')['username']
    //     );

    //     $this->db->where(array('id' => $post['edit_id']));
    //     $this->db->update('packing_detail', $dataUpdate);

    //     if ($this->db->affected_rows() > 0) {
    //         $response = array(
    //             'success' => true,
    //             'message' => 'Data updated successfully'
    //         );
    //     } else {
    //         $response = array(
    //             'success' => true,
    //             'message' => 'Failed update data'
    //         );
    //     }

    //     echo json_encode($response);
    // }
}
