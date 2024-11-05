<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PutawayScan extends CI_Controller
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
            'title' => 'Scan Putaway',
        );
        $this->render('receiving/putaway', $data);
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

    public function putawayProccess()
    {
        var_dump($this->input->post());
    }
}
