<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function getUserData()
    {
        return $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    }

    public function setNewPassword($password_hash)
    {
        $this->db->set('password',$password_hash);
        $this->db->where('email',$this->session->userdata('email'));
        $this->db->update('user');
    }
}