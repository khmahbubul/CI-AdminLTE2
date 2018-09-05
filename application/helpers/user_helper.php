<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('role_has_access')){
    function role_has_access($permission_id = 0)
    {
        //get main CodeIgniter object
        $ci =& get_instance();

        //load libraries
        $ci->load->database();
        $ci->load->library('session');

        if($ci->session->userdata('logged_in') != TRUE)
            return array('response' => false, 'data' => '');

        //get user role id
        $user = $ci->db->get_where('users', array('id' => $ci->session->userdata('id')))->row();

        if($user->is_active != 1)
            return array('response' => false, 'data' => '');

        //get user role permission
        $response = $ci->db->get_where('role_permissions', array('role_id' => $user->role_id, 'permission_id' => $permission_id));

        if($response->num_rows() == 1)
            return array('response' => true, 'data' => $user);
        else
            return array('response' => false, 'data' => '');
    }
}
