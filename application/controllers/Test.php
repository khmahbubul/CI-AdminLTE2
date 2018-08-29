<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		$header_data = array();
		$header_data['title'] = 'Log in';
		$header_data['menu'] = '';

		$this->load->view('login_view', $header_data);
	}
}
