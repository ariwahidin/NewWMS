<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InventoryMng extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'transfer_m', 'inv_mng_m', 'ekspedisi_m', 'receiving_m', 'trans_m', 'supplier_m', 'putaway_m', 'item_m', 'lpn_m', 'putaway_scan_m', 'inventory_m']);
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
            'title' => 'Inventory Management',
        );
        $this->render('inventory_mng/index', $data);
    }

    public function chooseeInv()
    {
        $post = $this->input->post();

        if (!isset($post['inventory']) || !$post) {
            echo "Access Denied";
            return;
        }

        $inventory = $post['inventory'];

        if ($inventory == 1) {
            $data = array(
                'title' => 'Transfer Loc.',
            );
            $this->render('inventory_mng/form_internal', $data);
            return;
        }

        // var_dump($post);
    }

    public function transferLoc()
    {
        $post = $this->input->post();
        if (!isset($post['location']) || !$post) {
            echo "Access Denied";
            return;
        }

        $location = $post['location'];
        $lpn_number = $post['lpn'];
        $item_code = $post['item_code'];

        if ($location == '' && $lpn_number == '' && $item_code == '') {
            echo "Field cannot be empty";
            return;
        }

        $items = $this->inv_mng_m->getItemToTransfer($location, $lpn_number, $item_code)->result();

        $data = array(
            'title' => 'Transfer Loc.',
            'items' => $items
        );
        $this->render('inventory_mng/form_internal_item', $data);
    }

    public function getItemToTransfer()
    {
        $post = $this->input->post();

        // var_dump($post);
        // exit;

        if (!isset($post['s_location']) || !$post) {
            $response = array(
                'success' => false,
                'message' => 'Location not found'
            );
            echo json_encode($response);
            return;
        }

        $location = $post['s_location'];
        $lpn_number = $post['s_lpn'];
        $item_code = $post['s_item_code'];

        if ($location == '' && $lpn_number == '' && $item_code == '') {
            $response = array(
                'success' => false,
                'message' => 'Field cannot be empty'
            );
            echo json_encode($response);
            return;
        }

        $items = $this->inv_mng_m->getItemToTransfer($location, $lpn_number, $item_code)->result();

        $response = array(
            'success' => true,
            'items' => $items
        );

        echo json_encode($response);
    }

    public function proccessTransfer()
    {
        $post = $this->input->post();
        // var_dump($post);
        $inventory_id = $post['inventory_id'];
        $qty_tf = $post['qty_in'];
        $to_location = $post['to_location'];
        $inventory = $this->inv_mng_m->getInventoryById($inventory_id)->row();
        $qty_avail = $inventory->available;

        if ($qty_avail < $qty_tf) {
            $response = array(
                'success' => false,
                'message' => 'Qty transfer exceeds available qty'
            );
            echo json_encode($response);
            return;
        }

        if ($to_location == '') {
            $response = array(
                'success' => false,
                'message' => 'Location cannot be empty'
            );
            echo json_encode($response);
            return;
        }

        if ($qty_tf < 1) {
            $response = array(
                'success' => false,
                'message' => 'Qty transfer must be greater than 0'
            );
            echo json_encode($response);
            return;
        }

        if ($to_location == $inventory->location) {
            $response = array(
                'success' => false,
                'message' => 'Location must be different from current location'
            );
            echo json_encode($response);
            return;
        }

        // location must be 8 character
        if (strlen($to_location) != 8) {
            $response = array(
                'success' => false,
                'message' => 'Location must be 8 characters'
            );
            echo json_encode($response);
            return;
        }

        $this->db->trans_start();

        $new_transfer_number = $this->transfer_m->generate_transfer_number();
        $trans_name = 'Transfer Location';
        $trans_id = $this->trans_m->getTransID($trans_name);

        $lpn_id = $inventory->lpn_id;
        $lpn_number = $inventory->lpn_number;

        if ($qty_tf < $inventory->available) {
            $old_lpn = $this->db->get_where('lpn', array('lpn_number' => $lpn_number))->row();
            $receive_detail_id = $old_lpn->receive_detail_id;
            $new_lpn = $this->lpn_m->generate_lpn($receive_detail_id);
            $lpn_id = $new_lpn['lpn_id'];
            $lpn_number = $new_lpn['lpn_number'];
        }

        $dataInsertTransfer = array(
            'transfer_number' => $new_transfer_number,
            'trans_id' => $trans_id,
            'grn_id' => $inventory->grn_id,
            'grn_number' => $inventory->grn_number,
            'whs_code_before' => $inventory->whs_code,
            'whs_code_after' => $inventory->whs_code,
            'location_before' => $inventory->location,
            'location_after' => $to_location,
            'available_before' => $inventory->available,
            'qty_transfer' => $qty_tf,
            'available_after' => (float)$inventory->available - (float)$qty_tf,
            'lpn_id_before' => $inventory->lpn_id,
            'lpn_id_after' => $lpn_id,
            'lpn_number_before' => $inventory->lpn_number,
            'lpn_number_after' => $lpn_number,
            'created_by' => $_SESSION['user_data']['username']
        );

        $this->db->insert('inventory_transfer', $dataInsertTransfer);

        // insert to inventory
        $data_insert_inventory = array(
            'whs_code' => $inventory->whs_code,
            'location' => $to_location,
            'grn_id' => $inventory->grn_id,
            'grn_number' => $inventory->grn_number,
            'item_code' => $inventory->item_code,
            'on_hand' => $qty_tf,
            'allocated' => 0,
            'available' => $qty_tf,
            'in_transit' => 0,
            'receive_date' => $inventory->receive_date,
            'expiry_date' => $inventory->expiry_date,
            'lpn_id' => $lpn_id,
            'lpn_number' => $lpn_number,
            'qa' => $inventory->qa,
            'is_pick' => $to_location == 'CROSDOCK' ? 'N' : 'Y',
            'created_by' => $_SESSION['user_data']['username']
        );

        $this->db->insert('inventory', $data_insert_inventory);

        // update inventory receiving area
        $this->db->set('on_hand', 'on_hand - ' . (float)$qty_tf, FALSE);
        $this->db->set('available', 'available - ' . (float)$qty_tf, FALSE);
        $this->db->where('id', $inventory_id);
        $this->db->update('inventory');

        $dataMovement = array(
            'whs_code' => $inventory->whs_code,
            'trans_id' => $trans_id,
            'grn_id' => $inventory->grn_id,
            'grn_number' => $inventory->grn_number,
            'lpn_id' => $lpn_id,
            'lpn_number' => $lpn_number,
            'item_code' => $inventory->item_code,
            'from_location' => $inventory->location,
            'to_location' => $to_location,
            'qty' => $qty_tf,
            'reff_no' => $new_transfer_number,
            'type' => $trans_name,
            'created_by' => $_SESSION['user_data']['username']
        );
        $this->db->insert('inventory_movement', $dataMovement);


        // var_dump($inventory);
        // var_dump($dataInsertTransfer);
        // var_dump($data_insert_inventory);
        // var_dump($dataMovement);
        // exit;

        $this->inventory_m->deleteZeroInventory();

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaction failed.'));
        } else {
            // Kembalikan nomor surat jalan yang telah diedit ke frontend
            echo json_encode(array('success' => true, 'message' => 'Transaction success.'));
        }
    }
}
