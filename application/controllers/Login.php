<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('logged_in'))
		{
			redirect('dashboard', 'refresh');
			exit;
		}
	}


	public function index()
	{
		$header_data = array();
		$header_data['title'] = 'Log in';
		$header_data['menu'] = '';
		$header_data['error'] = '';

		$this->load->view('login_view', $header_data);
	}


	public function do_login()
	{
		$header_data = array();
		$header_data['title'] = 'Log in';
		$header_data['menu'] = '';
		$header_data['error'] = '';

		$this->form_validation->set_rules('user_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('user_password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('login_view', $header_data);
			return;
		}
		
		$data = array();
		$data['email'] = $this->input->post('user_email');
		$data['password'] = $this->input->post('user_password');

		$results = $this->db->get_where('users', array('email' => $data['email'], 'is_active' => 1))->result_array();

		if(count($results) != 1)
		{
			$header_data['error'] = 'Email is not registered yet!';
			$this->load->view('login_view', $header_data);
			return;
		}

		if(!password_verify($data['password'], $results[0]['password']))
		{
			$header_data['error'] = 'Invalid password!';
			$this->load->view('login_view', $header_data);
			return;
		}

		$newdata = array(
			'id'  => $results[0]['id'],
			'logged_in' => TRUE
		);
		$this->session->set_userdata($newdata);

		redirect('dashboard', 'refresh');
	}
}
