<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PutawayScan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'ekspedisi_m', 'receiving_m', 'trans_m', 'supplier_m', 'putaway_m', 'item_m', 'lpn_m', 'putaway_scan_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function getPutawayHeader()
    {
        $putaway = $this->putaway_m->getPutawayHeaderByReceive($this->input->get('rcv'))->row();

        if (!$putaway) {
            $response = array(
                'success' => false,
                'message' => 'Putaway not found'
            );
            echo json_encode($response);
            return;
        }

        if ($putaway->is_ready_putaway == 'N') {
            $response = array(
                'success' => false,
                'message' => 'Putaway not ready'
            );
            echo json_encode($response);
            return;
        }

        $receive_detail = $this->receiving_m->getReceiveDetail($putaway->receive_number)->result();

        $response = array(
            'success' => true,
            'data' => $putaway,
            'detail' => $receive_detail
        );

        echo json_encode($response);
    }

    public function index()
    {
        $data = array(
            'title' => 'Putaway',
        );
        $this->render('receiving/rf/input_receipt', $data);
    }


    public function receive()
    {

        if (!isset($_POST['receiveNumber'])) {
            echo "Not Found";
            exit;
        }

        $receive_number = $_POST['receiveNumber'];
        $this->db->where('receive_number', $receive_number);
        $this->db->where('is_complete', 'N');
        $putaway =$this->db->get('putaway_header')->row();

        if (!$putaway) {
            echo "Putaway not found";
            exit;
        }



        $data = array(
            'title' => 'Putaway',
            'putaway' => $putaway
        );
        $this->render('receiving/rf/putaway_form', $data);
    }

    public function getItemReceiveToScan()
    {
        $post = $this->input->post();
        $items = $this->putaway_scan_m->checkItemScaned($post['receiveNumber']);
        $response = array(
            'success' => true,
            'data' => $items->result()
        );
        echo json_encode($response);
    }

    public function getItems()
    {
        $post = $this->input->post();

        $items = $this->putaway_scan_m->getItem($post);

        if ($items->num_rows() < 1) {
            echo json_encode(array('success' => false, 'message' => 'Item not found'));
            return;
        }

        $response = array(
            'success' => true,
            'data' => $items->result()
        );

        echo json_encode($response);
        return;
    }


    public function proccessPutaway()
    {
        $post = $this->input->post();



        date_default_timezone_set('Asia/Jakarta');

        $item_code = $post['itemCode'];
        $receive_number = $post['receiveNumber'];
        $receive_id = $post['receive_id'];
        $receive_detail_id = $post['receive_detail_id'];
        $qty_in = $post['qty_in'];
        $qty_uom = $post['qty_uom'];
        $uom = $post['uom'];
        $qty = (float) $qty_in * (float) $qty_uom;
        $from_location = $post['rcv_loc'];
        $to_location = $post['put_loc'];
        $putaway_number = $post['putaway_number'];
        $whs_code = $_SESSION['user_data']['warehouse'];

        $Scanned = $this->putaway_scan_m->checkItemScaned($receive_number, $receive_detail_id)->row();
        $qtyScanned = $Scanned->qty_scan;
        $qtyPredicted = $qtyScanned + $qty;
        $reqQty = $Scanned->req_qty;

        if ($qtyPredicted > $reqQty) {
            echo json_encode(array('success' => false, 'message' => 'Quantity exceeded'));
            return;
        }




        // var_dump($post);
        // die;

        // check if putaway number exist with query builder
        $this->db->where('putaway_number', $putaway_number);
        $putaway = $this->db->get('putaway_header')->row();

        if (!$putaway) {
            echo json_encode(array('success' => false, 'message' => 'Putaway number not found'));
            return;
        }

        $putaway_id = $putaway->id;

        $this->db->trans_start();

        // check before delete
        $this->db->where('putaway_id', $putaway_id);
        $check = $this->db->get('inventory');

        // delete before insert
        $sqlDelete = "DELETE FROM putaway_detail WHERE putaway_id = ? and to_location is null";
        $this->db->query($sqlDelete, array($putaway_id));

        // insert to putaway detail
        $data_insert = array(
            'putaway_id' => $putaway_id,
            'receive_detail_id' => $receive_detail_id,
            'putaway_number' => $putaway_number,
            'whs_code' => $whs_code,
            'item_code' => $item_code,
            'qty_in' => $qty_in,
            'qty_uom' => $qty_uom,
            'uom' => $uom,
            'qty' => $qty,
            'from_location' => $from_location,
            'to_location' => $to_location,
            'created_by' => $_SESSION['user_data']['username'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_by' => $_SESSION['user_data']['username'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->db->insert('putaway_detail', $data_insert);
        $putaway_detail_id = $this->db->insert_id();

        // update inventory receiving area
        $this->db->set('allocated', 'allocated + ' . (float)$qty, FALSE);
        $this->db->set('available', 'available - ' . (float)$qty, FALSE);
        $this->db->where('receive_detail_id', $receive_detail_id);
        $this->db->update('inventory');

        $sqlReceiveDetail = "SELECT a.*, b.receive_date from receive_detail a
        INNER JOIN receive_header b ON a.receive_id = b.id WHERE a.id = ?";

        $receive_detail = $this->db->query($sqlReceiveDetail, array($receive_detail_id))->row();

        // insert putaway location to inventory
        $data_insert_inventory = array(
            'whs_code' => $whs_code,
            'location' => $to_location,
            'item_code' => $item_code,
            'on_hand' => 0,
            'allocated' => 0,
            'available' => 0,
            'in_transit' => $qty,
            'receive_date' => $receive_detail->receive_date,
            'expiry_date' => $receive_detail->expiry_date,
            'qa' => $receive_detail->qa,
            'is_pick' => $to_location == 'CROSDOCK' ? 'N' : 'Y',
            'putaway_id' => $putaway_id,
            'putaway_detail_id' => $putaway_detail_id,
            'created_by' => $_SESSION['user_data']['username']
        );
        $this->db->insert('inventory', $data_insert_inventory);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('success' => false, 'message' => 'Transaction Failed'));
        } else {
            echo json_encode(array('success' => true, 'message' => 'Transaction Success.'));
        }
    }

    public function getItemPutaway()
    {
        $receive_number = $this->input->post('receiveNumber');

        $scanned = $this->putaway_scan_m->checkItemScaned($receive_number)->result();

        $qty_req = 0;
        $qty_scan = 0;
        foreach ($scanned as $key => $value) {
            $qty_req += $value->req_qty;
            $qty_scan += $value->qty_scan;
        }

        $percentProgreess = ($qty_scan / $qty_req) * 100;

        $items = $this->putaway_scan_m->getItemPutawayByReceiveNumber($receive_number);
        $response = array(
            'success' => true,
            'items' => $items->result(),
            'percent' => (int) $percentProgreess
        );
        echo json_encode($response);
    }

    public function deleteItemPutaway()
    {
        $post = $this->input->post();
        $items = $post['items'];
        $receive_detail_id = $items['receive_detail_id'];
        $putaway_detail_id = $items['id'];
        $qty = $items['qty'];

        $this->db->trans_start();

        // update inventory receiving area
        $this->db->set('allocated', 'allocated - ' . (float)$qty, FALSE);
        $this->db->set('available', 'available + ' . (float)$qty, FALSE);
        $this->db->where('receive_detail_id', $receive_detail_id);
        $this->db->update('inventory');

        // delete inventory putaway location
        $this->db->where('putaway_detail_id', $putaway_detail_id);
        $this->db->delete('inventory');

        // delete putaway detail
        $this->db->where('id', $putaway_detail_id);
        $this->db->delete('putaway_detail');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('success' => false, 'message' => 'Transaction Failed'));
        } else {
            echo json_encode(array('success' => true, 'message' => 'Transaction Success.'));
        }
    }



























    public function getReceiveDetailByLpn()
    {
        $receiv_number = $this->input->get('rcv');
        $lpn = $this->input->get('lpn');
        $receive_detail = $this->putaway_m->getReceiveDetailByLpn($receiv_number, $lpn)->result();

        if (!$receive_detail) {
            $response = array(
                'success' => false,
                'message' => 'Lpn not found'
            );
            echo json_encode($response);
            return;
        }

        $response = array(
            'success' => true,
            'detail' => $receive_detail
        );
        echo json_encode($response);
    }

    public function getItemToPutaway()
    {
        $post = $this->input->post();

        // var_dump($post);

        $item = $this->putaway_m->getItemToPutaway($post['receiveNumber'], $post['putawayNumber'], $post['lpnNumber'])->row();
        if (!$item) {
            $response = array(
                'success' => false,
                'message' => 'Item not found'
            );
            echo json_encode($response);
            return;
        }
        $response = array(
            'success' => true,
            'data' => $item
        );
        echo json_encode($response);
    }
}
