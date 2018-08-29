<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url(), 'refresh');
			exit;
		}
	}


	public function index()
	{
		$id = $this->session->userdata('id');

		$header_data = array();
		$header_data['title'] = 'Dashboard';
		$header_data['menu'] = 'dashboard';
		$header_data['error'] = '';

		$header_data['user_data'] = $this->db->get_where('users', array('id' => $id))->row();

		$this->load->view('common/header_view', $header_data);
		$this->load->view('dashboard/dashboard_view', $header_data);
		$this->load->view('common/footer_view', $header_data);
	}
}
