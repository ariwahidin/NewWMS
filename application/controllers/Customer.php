<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_m');
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
            'title' => 'MASTER CUSTOMER',
        );
        $this->render('master/customer/index', $data);
    }

    public function fetch_data()
    {
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $search = $this->input->post('search')['value'];

        $data = $this->customer_m->get_items($limit, $start, $search);
        $totalData = $this->customer_m->count_items();

        $output = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($this->customer_m->count_items($search)),
            "data" => $data
        ];
        echo json_encode($output);
    }

    public function create()
    {
        echo "Permission Denied";
        exit;
        $data = array(
            'title' => 'ADD NEW CUSTOMER',
        );
        $this->render('master/customer/create', $data);
    }

    public function store()
    {
        echo "Permission Denied";
        exit;
        $data = [
            'code' => $this->input->post('customer_code'),
            'name' => $this->input->post('customer_name'),
            'address' => $this->input->post('address')
        ];
        $this->customer_m->insert_item($data);
        redirect('customer/index');
    }

    public function edit($id)
    {
        echo "Permission Denied";
        exit;
        $data['item'] = $this->customer_m->get_item($id);
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
        $this->customer_m->update_item($id, $data);
        redirect('master_item');
    }

    public function delete($id)
    {
        echo "Permission Denied";
        exit;
        $this->customer_m->delete_item($id);
        redirect('master_item');
    }
}
