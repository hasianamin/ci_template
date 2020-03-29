<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getUserData()
    {
        return $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    }

    public function getUserMenu()
    {
        return $this->db->get('user_menu')->result_array();
    }

    public function getSubMenu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
        FROM `user_sub_menu` JOIN `user_menu`
        ON `user_sub_menu`.`menu_id` = `user_menu`.`id`";

        return $this->db->query($query)->result_array();
        
    }

    public function insertMenu($menu)
    {
        $this->db->insert('user_menu',['menu' => $menu]);
            
    }

    public function deleteMenuById($id)
    {
        $this->db->delete('user_menu',['id' => $id]);
    }

    public function deleteSubMenuById($id)
    {
        $this->db->delete('user_sub_menu',['id' => $id]);
    }

    public function editMenuById($id)
    {
        $data = [
            "menu" => $this->input->post('menu', true)
        ];

        $this->db->where('id',$this->input->post('id'));
        $this->db->update('user_menu',$data);
    }

    public function insertSubMenu($data)
    {
        $this->db->insert('user_sub_menu',$data);
            
    }

    public function editSubMenuById($id)
    {
        $data = [
            "title" => $this->input->post('title', true),
            "menu_id" => $this->input->post('menu_id', true),
            "url" => $this->input->post('url', true),
            "icon" => $this->input->post('icon', true),
            "is_active" => $this->input->post('is_active', true)
        ];

        $this->db->where('id',$this->input->post('id'));
        $this->db->update('user_sub_menu',$data);
    }

}