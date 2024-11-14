<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_m');
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
            'title' => 'MASTER WAREHOUSE',
        );
        $this->render('master/warehouse/index', $data);
    }

    public function fetch_data()
    {
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $search = $this->input->post('search')['value'];

        $data = $this->warehouse_m->get_items($limit, $start, $search);
        $totalData = $this->warehouse_m->count_items();

        $output = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($this->warehouse_m->count_items($search)),
            "data" => $data
        ];
        echo json_encode($output);
    }

    public function create()
    {
        $data = array(
            'title' => 'ADD NEW WAREHOUSE',
        );
        $this->render('master/warehouse/create', $data);
    }

    public function store()
    {

        $data = [
            'code' => $this->input->post('code'),
            'desc' => $this->input->post('desc'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_data')['username']
        ];
        $this->warehouse_m->insert_item($data);

        redirect('warehouse/index');
    }

    public function edit($id)
    {
        echo "Permission Denied";
        exit;
        $data['item'] = $this->warehouse_m->get_item($id);
        $this->load->view('warehouse/edit', $data);
    }

    public function update($id)
    {
        echo "Permission Denied";
        exit;
        $data = [
            'item_code' => $this->input->post('item_code'),
            'item_name' => $this->input->post('item_name')
        ];
        $this->warehouse_m->update_item($id, $data);
        redirect('master_item');
    }

    public function delete($id)
    {
        echo "Permission Denied";
        exit;
        $this->warehouse_m->delete_item($id);
        redirect('master_item');
    }
}
