<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		$this->load->view('common/header_view');
		$this->load->view('common/content_view');
		$this->load->view('common/footer_view');
	}
}
