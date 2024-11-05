<?php defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['inventory_m']);
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
            'title' => 'Inventory',
            'by_item' => $this->inventory_m->InventoryByItem(),
            'by_location' => $this->inventory_m->InventoryByLocation(),
            'by_detail' => $this->inventory_m->InventoryByDetail(),
        );
        $this->render('inventory/index', $data);
    }
}
