<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	private $header_data = array();

	public function __construct()
	{
		parent::__construct();

		$this->header_data['controller'] = 'dashboard';
		$this->header_data['title'] = 'Dashboard';
		$this->header_data['menu'] = 'dashboard';
		$this->header_data['error'] = '';

		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url(), 'refresh');
			exit;
		}

		$id = $this->session->userdata('id');
		$this->header_data['user_data'] = $this->db->get_where('users', array('id' => $id))->row();
	}


	public function index()
	{
		$this->load->view('common/header_view', $this->header_data);
		$this->load->view('dashboard/dashboard_view', $this->header_data);
		$this->load->view('common/footer_view', $this->header_data);
	}
}
