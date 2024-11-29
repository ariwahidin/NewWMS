<?php defined('BASEPATH') or exit('No direct script access allowed');

class PickingScan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['packing_m', 'shipment_m', 'picking_m', 'picking_scan_m']);
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
            'title' => 'Scan Picking',
        );
        $this->render('picking_scan/index', $data);
    }


    // public function searchShipment()
    // {
    //     $post  = $this->input->post(null, true);


    //     // var_dump($post);
    //     // die;

    //     $shipment_number = $post['shipment_number'];
    //     $item_code = $post['item_code'];
    //     $items = $this->picking_scan_m->getPickingDetailByShipment($shipment_number, $item_code);

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
    //     $items = $this->picking_scan_m->getPickingDetailByShipment($shipment_number, $item_code);

    //     if ($items->num_rows() < 1) {
    //         $response = array(
    //             'success' => false,
    //             'message' => 'Shipment number not found'
    //         );
    //         echo json_encode($response);
    //         return;
    //     }

    //     $itemIsFull = $this->picking_scan_m->checkPackingIsFull($shipment_number, $item_code);

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

    public function getPickedDetail()
    {
        $post = $this->input->post();

        $shipment_number = $post['shipment_number'];
        // $items = $this->picking_scan_m->getPickedDetail($shipment_number);

        $picked = $this->picking_scan_m->getPickedDetail($shipment_number);

        // $progress = 0;
        // $qty_picked = 0;
        // $qty_packed = 0;

        // if ($picked->num_rows() < 1) {
        //     $response = array(
        //         'success' => false,
        //         'message' => 'Shipment number not found'
        //     );
        //     echo json_encode($response);
        //     return;
        // }

        // foreach ($picked->result() as $pick) {
        //     $qty_picked += $pick->qty_pick;
        //     $qty_packed += $pick->qty_pack;
        // }
        // $progress = ($qty_packed / $qty_picked) * 100;



        // $progress = number_format($progress, 2);

        $response = array(
            'success' => true,
            'data' => $picked->result(),
        );

        echo json_encode($response);
    }

    public function changePickedDetail()
    {
        $post = $this->input->post(null, true);
        $item = $post['item'];
        $item_code = $item['item_code'];

        $inventory = $this->picking_scan_m->getInventoryAvailable($item_code);
        $response = array(
            'success' => true,
            'data' => $inventory->result(),
        );
        echo json_encode($response);
    }

    public function updatePickingDetail()
    {
        date_default_timezone_set('Asia/Jakarta');
        $post = $this->input->post(null, true);
        $old_item = $post['old_item'];

        $new_item = $post['new_item'];
        $qty_pick = $old_item['qty'] < $new_item['available'] ? $old_item['qty'] : $new_item['available'];

        $sumReqItem = $this->picking_scan_m->getSumReqItem($old_item['shipment_number'], $new_item['item_code'])->row()->qty_req;
        $sumPickItem = $this->picking_scan_m->getSumPickItem($old_item['shipment_number'], $new_item['item_code'])->row()->qty_pick;

        if (($sumPickItem - $qty_pick) + $qty_pick > $sumReqItem) {
            $response = array(
                'success' => false,
                'message' => 'Item already picked'
            );
            echo json_encode($response);
            return;
        }


        $this->db->trans_start();

        $dataToInsertPickingDetail = array(
            'picking_id' => $old_item['picking_id'],
            'picking_number' => $old_item['picking_number'],
            'shipment_id' => $old_item['shipment_id'],
            'shipment_number' => $old_item['shipment_number'],
            'shipment_detail_id' => $old_item['shipment_detail_id'],
            'inventory_id' => $new_item['id'],
            'whs_code' => $new_item['whs_code'],
            'location' => $new_item['location'],
            'to_location' => $old_item['to_location'],
            'lpn_id' => $new_item['lpn_id'],
            'lpn_number' => $new_item['lpn_number'],
            'grn_id' => $new_item['grn_id'],
            'grn_number' => $new_item['grn_number'],
            'item_code' => $new_item['item_code'],
            'qty' =>  $qty_pick,
            'qa' => $new_item['qa'],
            'receive_date' => $new_item['receive_date'],
            'expiry_date' => $new_item['expiry_date'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_data')['username'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_data')['username'],
        );

        $this->db->insert('picking_detail', $dataToInsertPickingDetail);
        $picking_detail_id = $this->db->insert_id();

        $dataToInsertInventory = array(
            'whs_code' => $new_item['whs_code'],
            'location' => $old_item['to_location'],
            'grn_id' => $new_item['grn_id'],
            'grn_number' => $new_item['grn_number'],
            'item_code' => $new_item['item_code'],
            'on_hand' => 0,
            'allocated' => 0,
            'available' => 0,
            'in_transit' =>  $qty_pick,
            'receive_date' => $new_item['receive_date'],
            'expiry_date' => $new_item['expiry_date'],
            'qa' => $new_item['qa'],
            'lpn_id' => $new_item['lpn_id'],
            'lpn_number' => $new_item['lpn_number'],
            'is_pick' => 'N',
            'shipment_id' => $old_item['shipment_id'],
            'shipment_detail_id' => $old_item['shipment_detail_id'],
            'picking_id' => $old_item['picking_id'],
            'picking_detail_id' => $picking_detail_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_data')['username'],
        );


        $this->db->insert('inventory', $dataToInsertInventory);

        $shipment_id_shipdock_new = $this->db->insert_id();
        $this->db->where('id', $picking_detail_id);
        $this->db->set('inventory_id_shipdock', $shipment_id_shipdock_new);
        $this->db->update('picking_detail');

        // balikin ke inventory
        $this->db->where('id', $old_item['inventory_id']);
        $this->db->set('allocated', 'allocated - ' . $qty_pick, FALSE);
        $this->db->set('available', 'available + ' . $qty_pick, FALSE);
        $this->db->update('inventory');

        // allocate ke inventory
        $this->db->where('id', $new_item['id']);
        $this->db->set('allocated', 'allocated + ' . $qty_pick, FALSE);
        $this->db->set('available', 'available - ' . $qty_pick, FALSE);
        $this->db->update('inventory');

        // hapus picking detail lama
        $this->db->where('id', $old_item['id']);
        $this->db->delete('picking_detail');

        // hapus shipdock inventory lama
        $this->db->where('id', $old_item['inventory_id_shipdock']);
        $this->db->delete('inventory');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = array(
                'success' => false,
                'message' => 'Failed to save'
            );
            echo json_encode($response);
            return;
        } else {
            $this->db->trans_commit();
            $response = array(
                'success' => true,
                'message' => 'Successfully saved'
            );
            echo json_encode($response);
            return;
        }
    }

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

    public function getItemsToPicking()
    {
        $post = $this->input->post(null, true);
        $shipment_number = $post['shipment_number'];
        $items = $this->picking_scan_m->getPickItemsToPick($shipment_number);
        $response = array(
            'success' => true,
            'data' => $items->result()
        );
        echo json_encode($response);
    }

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
