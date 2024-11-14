<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(['user_m', 'warehouse_m']);
	}

	public function login()
	{
		is_logged_in();
		$data = array(
			'warehouse' => $this->warehouse_m->getAllItem(),
		);
		$this->load->view('auth/login', $data);
	}

	public function proses()
	{
		$req = json_decode(file_get_contents('php://input'), true);

		$warehouse = $req['warehouse'];
		$username = $req['username'];
		$password = $req['password'];
		$login = $this->user_m->getUserActive($username, $password);
		if ($login->num_rows() > 0) {
			$user_data = array(
				'user_id' => $login->row()->id,
				'fullname' => $login->row()->fullname,
				'username' => $login->row()->username,
				'role' => $login->row()->role,
				'warehouse' => $warehouse
			);
			$this->session->set_userdata('user_data', $user_data);
			$response = array(
				'success' => true
			);
		} else {
			$response = array(
				'success' => false
			);
		}
		echo json_encode($response);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		$response = array(
			'success' => true
		);
		echo json_encode($response);
	}
}
