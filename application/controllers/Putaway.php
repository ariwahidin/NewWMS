<?php defined('BASEPATH') or exit('No direct script access allowed');

class Putaway extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['order_m', 'truck_m', 'ekspedisi_m', 'receiving_m', 'trans_m', 'supplier_m', 'putaway_m', 'item_m', 'lpn_m']);
        is_not_logged_in();
    }

    public function render($view, array $data = null)
    {
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer');
    }

    public function putawayList()
    {
        $data = array(
            'title' => 'Putaway',
            'receive' => $this->putaway_m->putawayList()
        );
        $this->render('putaway/index', $data);
    }

    public function desktop()
    {
        $data = array(
            'title' => isset($_GET['edit']) && $_GET['put_no'] ? 'Putaway ' . $_GET['put_no'] : 'Create Putaway',
            'truck' => $this->truck_m->getTruckType(),
            'ekspedisi' => $this->ekspedisi_m->getEkspedisi(),
            'supplier' => $this->supplier_m->getAllItem(),
            'type' => $this->order_m->getOrderType()
        );

        if (isset($_GET['edit']) && isset($_GET['put_no'])) {
            $put_no = $_GET['put_no'];
            $order = $this->putaway_m->getPutaway($put_no);
            if ($order->num_rows() > 0) {
                $data['order'] = $order->row();
            } else {
                echo "Not Found";
                exit;
            }
        }


        $this->render('putaway/desktop', $data);
    }

    public function create()
    {
        $ib_no = $this->input->post('ib_no');

        $receive_h = $this->receiving_m->getReceive($ib_no)->row();
        $receive_d = $this->receiving_m->getReceiveDetail($ib_no)->result();
        $this->db->trans_start();

        $transID = $this->trans_m->getTransID('Putaway');

        // Generate nomor surat jalan dengan format custom SPKASYYMMXXXX
        $prefix = 'PA'; // Awalan tetap
        $currentYearMonth = date('ym'); // Format tahun dan bulan, misalnya 2410 untuk Oktober 2024

        // Mencari nomor urut terakhir dari bulan ini
        $sql = "SELECT TOP 1 putaway_number FROM putaway_header 
                    WHERE putaway_number LIKE ? 
                    ORDER BY putaway_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            // Ambil 4 digit terakhir dari nomor surat jalan terakhir
            $lastNumber = (int)substr($lastEntry->putaway_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan format dengan 4 digit
        } else {
            // Jika belum ada nomor surat jalan bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        // Gabungkan prefix, tahun-bulan, dan nomor urut baru
        $putaway_number = $prefix . $currentYearMonth . $newNumber;

        // Simpan data surat jalan ke database menggunakan raw query
        $insertHeaderSQL = "INSERT INTO putaway_header (trans_id, putaway_number, receive_id, receive_number, is_complete, created_by) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $this->db->query($insertHeaderSQL, array(
            $transID,
            $putaway_number,
            $receive_h->id,
            $receive_h->receive_number,
            'N',
            $_SESSION['user_data']['username']
        ));

        $last_id = $this->db->insert_id();

        $sqlUpdateStatus = "UPDATE receive_header SET is_complete = ? WHERE receive_number = ?";
        $this->db->query($sqlUpdateStatus, array('Y', $ib_no));

        foreach ($receive_d as $key => $value) {
            // update column lpn number in table receive_detail
            $lpn = $this->lpn_m->generate_lpn($value->id);
            $this->db->set('lpn_id', $lpn['lpn_id']);
            $this->db->set('lpn_number', $lpn['lpn_number']);
            $this->db->where('id', $value->id);
            $this->db->update('receive_detail');

            $dataToInsertPutawayDetail = array(
                'putaway_id' => $last_id,
                'receive_detail_id' => $value->id,
                'putaway_number' => $putaway_number,
                'lpn_id' => $lpn['lpn_id'],
                'lpn_number' => $lpn['lpn_number'],
                'item_code' => $value->item_code,
                'qty' => $value->qty,
                'from_location' => $value->receive_location,
                'to_location' => null,
                'created_by' => $_SESSION['user_data']['username']
            );
            $this->db->insert('putaway_detail', $dataToInsertPutawayDetail);
            $putaway_detail_id = $this->db->insert_id();

            // update inventory
            $this->db->set('in_transit', 'in_transit - ' . (float)$value->qty, FALSE);
            $this->db->set('available', 'available + ' . (float)$value->qty, FALSE);
            $this->db->set('lpn_id', $lpn['lpn_id']);
            $this->db->set('lpn_number', $lpn['lpn_number']);
            $this->db->where('receive_detail_id', $value->id);
            $this->db->update('inventory');
        }


        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Mengecek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi kesalahan, rollback
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            // Kembalikan nomor surat jalan ke frontend
            echo json_encode(array('success' => true, 'putaway_number' => $putaway_number));
        }
    }

    public function getItems()
    {

        $shipment_current = null;
        $putaway_detail = null;

        if (isset($_POST['ib_no']) && isset($_POST['put_no'])) {
            $shipment_current = $this->putaway_m->getReceiveDetail($_POST['ib_no'])->result_array();
            $putaway_detail = $this->putaway_m->getPutawayDetail($_POST['put_no'])->result_array();
        }

        $listDO = $this->item_m->getAllItem()->result_array();

        $data = array(
            'shipments' => $listDO,
            'shipment_current' => $shipment_current,
            'putaway_detail' =>  $putaway_detail
        );

        echo json_encode($data);
    }

    public function editProccess()
    {



        $putaway_number = $this->input->post('header')['putawayNumber'];
        // check if putaway number exist with query builder
        $this->db->where('putaway_number', $putaway_number);
        $putaway = $this->db->get('putaway_header')->row();

        if (!$putaway) {
            echo json_encode(array('success' => false, 'message' => 'Putaway number not found'));
            return;
        }

        $putaway_id = $putaway->id;

        $item = $this->input->post('items');

        // check putaway location is verified
        foreach ($item as $i) {
            // replace all spaces
            $i['put_loc'] = str_replace(' ', '', $i['put_loc']);

            // characters must be 8 digits
            if (strlen($i['put_loc']) != 8) {
                echo json_encode(array('success' => false, 'message' => 'Putaway location must be 8 digits'));
                return;
            }
        }

        $this->db->trans_start();

        // check before delete
        $this->db->where('putaway_id', $putaway_id);
        $check = $this->db->get('inventory');
        // balikin dulu kalo ada 
        if ($check->num_rows() > 0) {
            $putaway_detail = $this->db->get_where('putaway_detail', array('putaway_id' => $putaway_id));

            foreach ($putaway_detail->result() as $row) {
                $this->db->set('allocated', 'allocated - ' . (float)$row->qty, FALSE);
                $this->db->set('available', 'available + ' . (float)$row->qty, FALSE);
                $this->db->where('receive_detail_id', $row->receive_detail_id);
                $this->db->update('inventory');
            }
        }


        // delete before insert
        $this->db->where('putaway_id', $putaway_id);
        $this->db->delete('putaway_detail');

        // delete inventory before insert
        $this->db->where('putaway_id', $putaway_id);
        $this->db->delete('inventory');

        // var_dump($item);
        // die;

        // insert into putaway detail
        foreach ($item as $i) {
            // insert to putaway detail
            $data_insert = array(
                'putaway_id' => $putaway_id,
                'receive_detail_id' => $i['receive_detail_id'],
                'putaway_number' => $putaway_number,
                'item_code' => $i['item_code'],
                'qty' => $i['quantity'],
                'from_location' => $i['rcv_loc'],
                'to_location' => $i['put_loc'],
                'lpn_id' => $i['lpn_id'],
                'lpn_number' => $i['lpn_number'],
                'created_by' => $_SESSION['user_data']['username'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_by' => $_SESSION['user_data']['username'],
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('putaway_detail', $data_insert);
            $putaway_detail_id = $this->db->insert_id();

            // update inventory receiving area
            $this->db->set('allocated', 'allocated + ' . (float)$i['quantity'], FALSE);
            $this->db->set('available', 'available - ' . (float)$i['quantity'], FALSE);
            $this->db->where('receive_detail_id', $i['receive_detail_id']);
            $this->db->update('inventory');


            // insert to inventory
            $data_insert_inventory = array(
                'location' => $i['put_loc'],
                'item_code' => $i['item_code'],
                'on_hand' => $i['quantity'],
                'allocated' => 0,
                'available' => 0,
                'in_transit' => $i['quantity'],
                'receive_date' => $i['receive_date'],
                'expiry_date' => $i['expiry'],
                'qa' => $i['qa'],
                'lpn_id' => $i['lpn_id'],
                'lpn_number' => $i['lpn_number'],
                'putaway_id' => $putaway_id,
                'putaway_detail_id' => $putaway_detail_id,
                'created_by' => $_SESSION['user_data']['username']
            );
            $this->db->insert('inventory', $data_insert_inventory);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            echo json_encode(array('success' => true, 'message' => 'Transaksi sukses.'));
        }
    }

    public function completePutaway()
    {

        $putaway_number = $this->input->post('putaway_number');
        $header = $this->db->get_where('putaway_header', array('putaway_number' => $putaway_number))->row();
        if ($header->is_complete == 'Y') {
            echo json_encode(array('success' => false, 'message' => 'Putaway number already completed.'));
            return;
        }

        $receive_number = $header->receive_number;

        // sum qty in receive detail
        $this->db->select_sum('qty');
        $this->db->where('receive_number', $receive_number);
        $total_qty = $this->db->get('receive_detail')->row()->qty;

        // sum qty in putaway detail
        $this->db->select_sum('qty');
        $this->db->where('putaway_number', $putaway_number);
        // and to location = '' and to location != null
        $this->db->where('to_location !=', '');
        $this->db->where('to_location !=', null);
        $total_putaway_qty = $this->db->get('putaway_detail')->row()->qty;


        if ($total_qty != $total_putaway_qty) {
            echo json_encode(array('success' => false, 'message' => 'Qty in Putaway Detail != Qty in Receive Detail'));
            return;
        }

        $this->db->trans_start();

        $putaway = $this->db->get_where('putaway_header', array('putaway_number' => $putaway_number))->row();
        $putaway_id = $putaway->id;

        $putaway_detail = $this->db->get_where('putaway_detail', array('putaway_id' => $putaway_id))->result();
        foreach ($putaway_detail as $key => $value) {
            $this->db->set('in_transit', 'in_transit - ' . (float)$value->qty, FALSE);
            $this->db->set('available', 'available + ' . (float)$value->qty, FALSE);
            $this->db->set('lpn_id', $value->lpn_id);
            $this->db->set('lpn_number', $value->lpn_number);
            $this->db->where('putaway_detail_id', $value->id);
            $this->db->update('inventory');
        }


        $receive_detail = $this->db->get_where('receive_detail', array('receive_id' => $putaway->receive_id))->result();
        foreach ($receive_detail as $key => $value) {
            $this->db->set('allocated', 'allocated - ' . (float)$value->qty, FALSE);
            $this->db->set('on_hand', 'on_hand - ' . (float)$value->qty, FALSE);
            $this->db->where('receive_detail_id', $value->id);
            $this->db->update('inventory');

            $check = $this->db->get_where('inventory', array('receive_detail_id' => $value->id))->row();
            if ($check->on_hand == 0 && $check->allocated == 0 && $check->in_transit == 0 && $check->available == 0) {
                $this->db->where('receive_detail_id', $value->id);
                $this->db->delete('inventory');
            }
        }

        $this->db->set('is_complete', 'Y');
        $this->db->where('id', $putaway_id);
        $this->db->update('putaway_header');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('success' => false, 'message' => 'Transaksi gagal.'));
        } else {
            echo json_encode(array('success' => true, 'message' => 'Transaksi sukses.'));
        }
    }

    public function printPutawaySheet()
    {

        $rcv_number = $this->input->get('rcv_no');
        $rcv = $this->receiving_m->getReceive($rcv_number)->row();
        $rcv_detail = $this->receiving_m->getReceiveDetail($rcv_number)->result_array();

        $data = array(
            'rcv' => $rcv,
            'rcv_detail' => $rcv_detail
        );
        $this->load->view('putaway/putaway_sheet', $data);
    }

    public function partialProccess()
    {
        $post = $this->input->post();
        $items = $post['items'];
        $partial = true;
        $location_filled = true;

        foreach ($items as $key => $value) {
            // check partial is checked
            if ($value['partial'] == 'false') {
                $partial = false;
                break;
            }

            // check location is filled
            if ($value['put_loc'] == '') {
                $location_filled = false;
                break;
            }

            // check location must 8 digit
            if (strlen($value['put_loc']) != 8) {
                $location_filled = false;
                break;
            }
        }

        if (!$partial) {
            $response = array(
                'success' => false,
                'message' => 'Please select at least one item to be partial.'
            );
            echo json_encode($response);
            return;
        }

        if (!$location_filled) {
            $response = array(
                'success' => false,
                'message' => 'Please fill location for item to be partial, must 8 digit.'
            );
            echo json_encode($response);
            return;
        }

        var_dump($post);
    }
}
