<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	private $header_data = array();

	public function __construct()
	{
		parent::__construct();

		//checking if user is logged in & has manage_registration = 1 permission
		$user = role_has_access(1);
		if(!$user['response'])
		{
			redirect(base_url(), 'refresh');
			exit;
		}

		$this->header_data['controller'] = 'users';
		$this->header_data['title'] = 'All users';
		$this->header_data['menu'] = 'all_users';
		$this->header_data['error'] = '';
		$this->header_data['user_data'] = $user['data'];
	}


	public function index()
	{		
		$this->header_data['users'] = $this->db->get('users')->result();

		$roles = $this->db->get('user_roles')->result();
		$r = array();
		foreach ($roles as $role) {
			$r[$role->id] = $role->role_name;
		}
		$this->header_data['roles'] = $r;

		$this->load->view('common/header_view', $this->header_data);
		$this->load->view('manage_users/users/index_view', $this->header_data);
		$this->load->view('common/footer_view', $this->header_data);
	}


	public function add_new_form()
	{
		$user_roles = $this->db->get('user_roles')->result();

		$html = '<form class="ajax_form" action="'.base_url().'users/save_add_new_form_data" method="post">
			<div class="form-group">
         		<label>User Name</label>
           		<input type="text" name="name" class="form-control" placeholder="Enter User Name" required>
            </div>
            <div class="form-group">
         		<label>User Email</label>
           		<input type="email" name="email" class="form-control" placeholder="Enter User Email" required>
            </div>
            <div class="form-group">
         		<label>User Password</label>
           		<input type="password" name="password" class="form-control" placeholder="Enter Password" autocomplete="new-password" required>
            </div>';

      	$html .= '<div class="form-group">
				<label>Select User Role</label>
				<select name="role" class="form-control" required>
					<option value="">-- select --</option>';
			foreach ($user_roles as $roles) {
				$html .= '<option value="'.$roles->id.'">'.$roles->role_name.'</option>';
			}

		$html .= '</select>
			</div>
			<div class="form-group">
				<label>Select Status</label>
				<select class="form-control" name="status" required>
					<option value="1">Active</option>
					<option value="0">Block</option>
				</select>
			</div>
            <button type="submit" style="margin: 0 auto;display: block;" class="btn btn-primary">Submit</button>
		</form>';

		echo $html;
	}


	public function save_add_new_form_data()
	{
		$this->form_validation->set_rules('name', 'User Name', 'required');
		$this->form_validation->set_rules('email', 'User Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'User Password', 'required');
		$this->form_validation->set_rules('role', 'Select User Role', 'required');
		$this->form_validation->set_rules('status', 'Select Status', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
			exit;
		}

		$data = array(
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'role_id' => $this->input->post('role'),
			'is_active' => $this->input->post('status')
		);
		$this->db->insert('users', $data);

		$this->session->set_flashdata('success', 'Data saved successfully!');
	}


	public function edit_form($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('users', 'refresh');
			exit;
		}

		$data = $this->db->get_where('users', array('id' => $id))->row();

		$user_roles = $this->db->get('user_roles')->result();

		$html = '<form class="ajax_form" action="'.base_url().'users/save_edit_form_data/'.$id.'" method="post">
			<div class="form-group">
         		<label>User Name</label>
           		<input type="text" name="name" class="form-control" value="'.$data->name.'" placeholder="Enter User Name" required>
            </div>
            <div class="form-group">
         		<label>User Email</label>
           		<input type="email" name="email" class="form-control" value="'.$data->email.'" placeholder="Enter User Email" required>
            </div>
            <div class="form-group">
         		<label>User Password</label>
           		<input type="password" name="password" class="form-control" placeholder="Enter Password" autocomplete="new-password">
            </div>';

      	$html .= '<div class="form-group">
				<label>Select User Role</label>
				<select name="role" class="form-control" required>
					<option value="">-- select --</option>';
			foreach ($user_roles as $roles) {
				if($data->role_id == $roles->id)
					$html .= '<option value="'.$roles->id.'" selected>'.$roles->role_name.'</option>';
				else
					$html .= '<option value="'.$roles->id.'">'.$roles->role_name.'</option>';
			}

		$html .= '</select>
			</div>
			<div class="form-group">
				<label>Select Status</label>
				<select class="form-control" name="status" required>';

		if($data->is_active)
			$html .= '<option value="1" selected>Active</option><option value="0">Block</option>';
		else
			$html .= '<option value="1">Active</option><option value="0" selected>Block</option>';
		
		$html .= '</select>
			</div>
            <button type="submit" style="margin: 0 auto;display: block;" class="btn btn-primary">Submit</button>
		</form>';

		echo $html;
	}


	public function save_edit_form_data($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('users', 'refresh');
			exit;
		}

		$user = $this->db->get_where('users', array('id' => $id))->row();

		$this->form_validation->set_rules('name', 'User Name', 'required');
		if($user->email != $this->input->post('email'))
		{
			$this->form_validation->set_rules('email', 'User Email', 'required|valid_email|is_unique[users.email]');
		}
		$this->form_validation->set_rules('role', 'Select User Role', 'required');
		$this->form_validation->set_rules('status', 'Select Status', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
			exit;
		}

		$pass = $this->input->post('password');
		$data = array(
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'password' => (!empty($pass)) ? password_hash($pass, PASSWORD_DEFAULT) : $user->password,
			'role_id' => $this->input->post('role'),
			'is_active' => $this->input->post('status')
		);
		$this->db->where('id', $id);
		$this->db->update('users', $data);

		$this->session->set_flashdata('success', 'Data updated successfully!');

		redirect('users', 'refresh');
	}


	public function delete_data($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('users', 'refresh');
			exit;
		}

		$this->db->where('id', $id);
		$this->db->delete('users');

		$this->session->set_flashdata('success', 'Data deleted successfully!');
	}
}
