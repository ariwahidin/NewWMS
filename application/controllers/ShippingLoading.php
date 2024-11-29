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
        $transporter = $this->db->get('ekspedisi')->result();
        $data = array(
            'transporter' => $transporter,
            'title' => 'Shipping Loading',
        );
        $this->render('shipping_loading/index', $data);
    }


    public function getShipment()
    {
        $post  = $this->input->post(null, true);

        $shipment_number = $post['shipment_number'];
        $shipment = $this->shipping_load_m->getShipment($shipment_number);

        $response = array(
            'success' => true,
            'data' => $shipment->row()
        );

        echo json_encode($response);
    }

    public function saveTransporter()
    {
        date_default_timezone_set('Asia/Jakarta');
        $post  = $this->input->post(null, true);
        $data = array(
            'transporter_id' => $post['transporter'],
            'driver_name' => $post['driver_name'],
            'driver_phone' => $post['driver_tlp'],
            'truck_no' => $post['no_truck'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_data')['username'],
        );
        $this->db->where('id', $post['shipment_id']);
        $this->db->update('shipment_header', $data);
        if ($this->db->affected_rows() > 0) {
            $response = array(
                'success' => true,
                'message' => 'Update transporter shipment successfully'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Update transporter shipment failed'
            );
        }
        echo json_encode($response);
    }

    public function getCartonList()
    {
        $post  = $this->input->post(null, true);
        $shipment_number = $post['shipment_number'];
        $carton_list = $this->shipping_load_m->getCartonList($shipment_number);

        $response = array(
            'success' => true,
            'data' => $carton_list->result()
        );

        echo json_encode($response);
    }

    public function saveLoading()
    {
        date_default_timezone_set('Asia/Jakarta');
        $post = $this->input->post(null, true);

        $carton_list = $this->shipping_load_m->getCartonList($post['shipment_number']);

        if ($carton_list->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment ' . $post['shipment_number']  . ' not found'
            );
            echo json_encode($response);
            return;
        }

        $cartonNotExist = $this->db->get_where('packing_detail', array('shipment_number' => $post['shipment_number'], 'carton' => $post['carton_no']));

        if ($cartonNotExist->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Carton ' . $post['carton_no']  . ' not found'
            );
            echo json_encode($response);
            return;
        }


        foreach ($carton_list->result() as $carton) {
            if ($carton->qty_carton_in + (float)$post['qty_carton'] > $carton->qty_carton && $carton->carton == $post['carton_no']) {
                $response = array(
                    'success' => false,
                    'message' => 'Qty in carton ' . $carton->carton . ' is cannot exceed ' . $carton->qty_carton
                );
                echo json_encode($response);
                return;
            };
        }





        $this->db->trans_start();
        date_default_timezone_set('Asia/Jakarta');

        $dataInsert = array(
            'shipment_number' => $post['shipment_number'],
            'container_no' => $post['container_no'],
            'carton_no' => $post['carton_no'],
            'qty_carton' => $post['qty_carton'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_data')['username'],
        );

        $this->db->insert('shipping_load_d', $dataInsert);

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

    public function getContainerDetail()
    {
        $post = $this->input->post();

        $shipment_number = $post['shipment_number'];
        $container = $this->shipping_load_m->getContainerDetail($shipment_number);
        $carton_list = $this->shipping_load_m->getCartonList($shipment_number);

        $progress = 0;
        $qty_carton = 0;
        $qty_carton_in = 0;

        if ($carton_list->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment number not found'
            );
            echo json_encode($response);
            return;
        }

        foreach ($carton_list->result() as $carton) {
            $qty_carton += $carton->qty_carton;
            $qty_carton_in += $carton->qty_carton_in;
        }

        $progress = ($qty_carton_in / $qty_carton) * 100;



        $progress = number_format($progress, 2);

        $response = array(
            'success' => true,
            'data' => $container->result(),
            'progress' => $progress
        );

        echo json_encode($response);
    }

    public function removeContainerDetail()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $delete = $this->db->delete('shipping_load_d', array('id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response = array(
                'success' => true,
                'message' => 'Conatiner has been deleted succesfully'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Failed to delete this item'
            );
        }
        echo json_encode($response);
    }

    public function confirmLoading()
    {
        date_default_timezone_set('Asia/Jakarta');
        $post = $this->input->post(null, true);


        $isComplete = $this->db->get_where('shipping_load_h', array('shipment_number' => $post['shipment_number'], 'is_complete' => 'Y'))->num_rows();

        if ($isComplete > 0) {
            $response = array(
                'success' => false,
                'message' => 'Shipment ' . $post['shipment_number']  . ' already complete'
            );
            echo json_encode($response);
            return;
        }

        $carton_list = $this->shipping_load_m->getCartonList($post['shipment_number']);

        if ($carton_list->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment ' . $post['shipment_number']  . ' not found'
            );
            echo json_encode($response);
            return;
        }

        $carton_list = $this->shipping_load_m->getCartonList($post['shipment_number']);

        $progress = 0;
        $qty_carton = 0;
        $qty_carton_in = 0;

        if ($carton_list->num_rows() < 1) {
            $response = array(
                'success' => false,
                'message' => 'Shipment number not found'
            );
            echo json_encode($response);
            return;
        }

        foreach ($carton_list->result() as $carton) {
            $qty_carton += $carton->qty_carton;
            $qty_carton_in += $carton->qty_carton_in;
        }

        $progress = ($qty_carton_in / $qty_carton) * 100;

        if ($progress < 100) {
            $response = array(
                'success' => false,
                'message' => 'Shipment ' . $post['shipment_number']  . ' not complete'
            );
            echo json_encode($response);
            return;
        }



        $this->db->trans_start();
        $shipment_number = $post['shipment_number'];
        $shipment = $this->db->get_where('shipment_header', array('shipment_number' => $shipment_number));
        date_default_timezone_set('Asia/Jakarta');

        $shipment_id = $shipment->row()->id;
        $transID = $this->trans_m->getTransID('Shipping Loading');
        $prefix = 'SHP';
        $currentYearMonth = date('ym');
        $sql = "SELECT TOP 1 shipping_load_number FROM shipping_load_h 
                    WHERE shipping_load_number LIKE ? 
                    ORDER BY shipping_load_number DESC";
        $lastEntry = $this->db->query($sql, array($prefix . $currentYearMonth . '%'))->row();

        if ($lastEntry) {
            $lastNumber = (int)substr($lastEntry->shipping_load_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        $shipping_load_number = $prefix . $currentYearMonth . $newNumber;


        $this->db->trans_start();

        $dataInsertHeader = array(
            'trans_id' => $transID,
            'shipping_load_number' => $shipping_load_number,
            'shipment_id' => $shipment_id,
            'shipment_number' => $shipment_number,
            'is_complete' => 'Y',
            'created_by' => $_SESSION['user_data']['username'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $this->db->insert('shipping_load_h', $dataInsertHeader);
        $shipping_load_id = $this->db->insert_id();

        $sqlShippingDetail = "UPDATE shipping_load_d 
                                SET shipping_load_id = ? , 
                                    shipping_load_number = ? ,
                                    updated_at = ? ,
                                    updated_by = ?
                                WHERE shipment_number = ? AND created_by = ?";
        $dataUpdateShippingDetail = array(
            $shipping_load_id,
            $shipping_load_number,
            date('Y-m-d H:i:s'),
            $_SESSION['user_data']['username'],
            $shipment_number,
            $_SESSION['user_data']['username']
        );
        $this->db->query($sqlShippingDetail, $dataUpdateShippingDetail);

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
