<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function getUserByEmail($email)
    {
        return $this->db->get_where('user',['email' => $email])->row_array();
    }

    public function insertToken($data, $user_token)
    {
        $this->db->insert('user', $data);
        $this->db->insert('user_token', $user_token);
    }

    public function getUserToken($token)
    {
        return $this->db->get_where('user_token',['token' =>$token])->row_array();
    }

    public function setVerify($email)
    {
        $this->db->set('is_active', 1);                    
        $this->db->where('email', $email);                    
        $this->db->update('user');
        
        $this->db->delete('user_token', ['email' => $email]);
    }

    public function setExpiredVerify($email)
    {
        $this->db->delete('user', ['email' => $email]);
        $this->db->delete('user_token', ['email' => $email]);
    }
}