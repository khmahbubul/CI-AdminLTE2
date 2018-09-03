<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_roles extends CI_Controller {

	private $header_data = array();

	public function __construct()
	{
		parent::__construct();

		$this->header_data['controller'] = 'user_roles';
		$this->header_data['title'] = 'All user roles';
		$this->header_data['menu'] = 'all_user_roles';
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
		$this->header_data['user_roles'] = $this->db->get('user_roles')->result();

		$this->load->view('common/header_view', $this->header_data);
		$this->load->view('manage_users/user_roles/index_view', $this->header_data);
		$this->load->view('common/footer_view', $this->header_data);
	}


	public function add_new_form()
	{
		$permissions = $this->db->get('permissions')->result();

		$html = '<form action="'.base_url().'user_roles/save_add_new_form_data" method="post">
			<div class="form-group">
         		<label>Role Name</label>
           		<input type="text" name="name" class="form-control" placeholder="Enter Role Name" required>
            </div>
            <div class="form-group">
            	<label>Give Permissions</label>';
		
		foreach ($permissions as $permission)
		{
			$html .= '<div class="checkbox">
				<label>
					<input type="checkbox" name="permissions[]" value="'.$permission->id.'"> '.$permission->permission_name.'
				</label>
			</div>';
		}

        $html .= '</div>
        		<button type="submit" style="margin: 0 auto;display: block;" class="btn btn-primary">Submit</button>
			</form>';

		echo $html;
	}


	public function save_add_new_form_data()
	{
		$this->form_validation->set_rules('name', 'Role Name', 'required|is_unique[user_roles.role_name]');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error', validation_errors());
			redirect('user_roles', 'refresh');
			exit;
		}

		$data = array(
			'role_name' => $this->input->post('name')
		);
		$this->db->insert('user_roles', $data);
		$id = $this->db->insert_id();

		if(isset($_POST['permissions']))
		{
			$ps = $_POST['permissions'];
			foreach ($ps as $key => $value) {
				if(is_numeric($value))
				{
					$this->db->insert('role_permissions', array('role_id' => $id, 'permission_id' => $value));
				}
			}
		}

		$this->session->set_flashdata('success', 'Data saved successfully!');

		redirect('user_roles', 'refresh');
	}


	public function edit_form($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('user_roles', 'refresh');
			exit;
		}

		$role = $this->db->get_where('user_roles', array('id' => $id))->row();
		$permissions = $this->db->get('permissions')->result();

		$this->db->select('permission_id');
		$role_permissions = $this->db->get_where('role_permissions', array('role_id' => $id))->result();

		$p = array();
		foreach ($role_permissions as $role_permission) {
			$p[$role_permission->permission_id] = true;
		}

		$html = '<form action="'.base_url().'user_roles/save_edit_form_data/'.$id.'" method="post">
			<div class="form-group">
         		<label>Role Name</label>
           		<input type="text" name="name" class="form-control" value="'.$role->role_name.'" placeholder="Enter Role Name" required>
            </div>
            <div class="form-group">
            	<label>Give Permissions</label>';
		
		foreach ($permissions as $permission)
		{
			$html .= '<div class="checkbox">';

			if(isset($p[$permission->id]))
				$html .= '<label>
					<input type="checkbox" name="permissions[]" value="'.$permission->id.'" checked> '.$permission->permission_name.'
				</label>';
			else
				$html .= '<label>
					<input type="checkbox" name="permissions[]" value="'.$permission->id.'"> '.$permission->permission_name.'
				</label>';
			$html .= '</div>';
		}

        $html .= '</div>
        		<button type="submit" style="margin: 0 auto;display: block;" class="btn btn-primary">Submit</button>
			</form>';

		echo $html;
	}


	public function save_edit_form_data($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('user_roles', 'refresh');
			exit;
		}

		$role = $this->db->get_where('user_roles', array('id' => $id))->row();
		if($role->role_name != $this->input->post('name'))
		{
			$this->form_validation->set_rules('name', 'Role Name', 'required|is_unique[user_roles.role_name]');

			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('error', validation_errors());
				redirect('user_roles', 'refresh');
				exit;
			}
		}

		$data = array(
			'role_name' => $this->input->post('name')
		);
		$this->db->where('id', $id);
		$this->db->update('user_roles', $data);

		$this->db->where('role_id', $id);
		$this->db->delete('role_permissions');

		if(isset($_POST['permissions']))
		{
			$ps = $_POST['permissions'];
			foreach ($ps as $key => $value) {
				if(is_numeric($value))
				{
					$this->db->insert('role_permissions', array('role_id' => $id, 'permission_id' => $value));
				}
			}
		}

		$this->session->set_flashdata('success', 'Data updated successfully!');

		redirect('user_roles', 'refresh');
	}


	public function delete_data($id = null)
	{
		if(!is_numeric($id))
		{
			redirect('user_roles', 'refresh');
			exit;
		}

		$this->db->where('id', $id);
		$this->db->delete('user_roles');

		$this->session->set_flashdata('success', 'Data deleted successfully!');
	}
}
