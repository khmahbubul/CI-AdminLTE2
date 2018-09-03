<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {

	private $header_data = array();

	public function __construct()
	{
		parent::__construct();

		$this->header_data['controller'] = 'permissions';
		$this->header_data['title'] = 'All permissions';
		$this->header_data['menu'] = 'all_permissions';
		$this->header_data['error'] = '';

		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url(), 'refresh');
			exit;
		}
	}


	public function index()
	{
		$id = $this->session->userdata('id');
		
		$this->header_data['user_data'] = $this->db->get_where('users', array('id' => $id))->row();
		$this->header_data['permissions'] = $this->db->get('permissions')->result();

		$this->load->view('common/header_view', $this->header_data);
		$this->load->view('manage_users/permissions/index_view', $this->header_data);
		$this->load->view('common/footer_view', $this->header_data);
	}


	public function add_new_form()
	{
		$html = '<form action="'.base_url().'permissions/save_add_new_form_data" method="post">
			<div class="form-group">
         		<label>Permission Name</label>
           		<input type="text" name="pname" class="form-control" placeholder="Enter Permission Name" required>
            </div>
            <button type="submit" style="margin: 0 auto;display: block;" class="btn btn-primary">Submit</button>
		</form>';

		echo $html;
	}


	public function save_add_new_form_data()
	{
		$this->form_validation->set_rules('pname', 'Permission Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			redirect('permissions', 'refresh');
			exit;
		}

		$data = array(
			'permission_name' => $this->input->post('pname')
		);
		$this->db->insert('permissions', $data);

		$this->session->set_flashdata('success', 'Data saved successfully!');

		redirect('permissions', 'refresh');
	}


	public function edit_form($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('permissions', 'refresh');
			exit;
		}

		$data = $this->db->get_where('permissions', array('id' => $id))->row();

		$html = '<form action="'.base_url().'permissions/save_edit_form_data/'.$id.'" method="post">
			<div class="form-group">
         		<label>Permission Name</label>
           		<input type="text" name="pname" class="form-control" value="'.$data->permission_name.'" placeholder="Enter Permission Name" required>
            </div>
            <button type="submit" style="margin: 0 auto;display: block;" class="btn btn-primary">Submit</button>
		</form>';

		echo $html;
	}


	public function save_edit_form_data($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('permissions', 'refresh');
			exit;
		}

		$this->form_validation->set_rules('pname', 'Permission Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			redirect('permissions', 'refresh');
			exit;
		}

		$data = array(
			'permission_name' => $this->input->post('pname')
		);
		$this->db->where('id', $id);
		$this->db->update('permissions', $data);

		$this->session->set_flashdata('success', 'Data updated successfully!');

		redirect('permissions', 'refresh');
	}


	public function delete_data($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('permissions', 'refresh');
			exit;
		}

		$this->db->where('id', $id);
		$this->db->delete('permissions');

		$this->session->set_flashdata('success', 'Data deleted successfully!');
	}
}
