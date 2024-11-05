<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_m');
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
            'title' => 'MASTER SUPPLIER',
        );
        $this->render('master/supplier/index', $data);
    }

    public function fetch_data()
    {
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $search = $this->input->post('search')['value'];

        $data = $this->supplier_m->get_items($limit, $start, $search);
        $totalData = $this->supplier_m->count_items();

        $output = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($this->supplier_m->count_items($search)),
            "data" => $data
        ];
        echo json_encode($output);
    }

    public function create()
    {
        $data = array(
            'title' => 'ADD NEW SUPPLIER',
        );
        $this->render('master/supplier/create', $data);
    }

    public function store()
    {
        $data = [
            'code' => $this->input->post('supplier_code'),
            'name' => $this->input->post('supplier_name'),
            'address' => $this->input->post('address')
        ];
        $this->supplier_m->insert_item($data);
        redirect('supplier/index');
    }

    public function edit($id)
    {
        echo "Permission Denied";
        exit;
        $data['item'] = $this->item_m->get_item($id);
        $this->load->view('master_item/edit', $data);
    }

    public function update($id)
    {
        echo "Permission Denied";
        exit;
        $data = [
            'item_code' => $this->input->post('item_code'),
            'item_name' => $this->input->post('item_name')
        ];
        $this->item_m->update_item($id, $data);
        redirect('master_item');
    }

    public function delete($id)
    {
        $this->item_m->delete_item($id);
        redirect('master_item');
    }
}
