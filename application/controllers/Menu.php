<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        
        $this->load->model('Menu_model');
    }

    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->Menu_model->getUserData();
        
        $data['menu'] = $this->Menu_model->getUserMenu();

        $this->form_validation->set_rules('menu','Menu','required');
        
        if ($this->form_validation->run() == FALSE ) {
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/index',$data);
            $this->load->view('templates/footer');
        }
        else{
            $menu = $this->input->post('menu');
            $this->Menu_model->insertMenu($menu);
            $this->session->set_flashdata('message', 
            "<div class='row mt-3'>
                <div class='col-md-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Congratulation you just create a new menu
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('menu');
        }

    }

    public function subMenu()
    {
        $data['title'] = 'SubMenu Management';
        $data['user'] = $this->Menu_model->getUserData();
        
        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->Menu_model->getSubMenu();
        $data['menu'] = $this->Menu_model->getUserMenu();

        
        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('menu_id','Menu','required');
        $this->form_validation->set_rules('url','URL','required');
        $this->form_validation->set_rules('icon','Icon','required');

        if($this->form_validation->run() == false){
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/submenu',$data);
            $this->load->view('templates/footer');
        }
        else{
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->Menu_model->insertSubMenu($data);
            $this->session->set_flashdata('message', 
            "<div class='row mt-3'>
                <div class='col-md-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Congratulation you just create a new submenu
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('menu/submenu');
        }

    }

    public function deleteMenu($id)
    {
        $this->Menu_model->deleteMenuById($id);
        $this->session->set_flashdata('message',
        "<div class='row mt-3'>
            <div class='col-md-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                You just delete a menu
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            </div>
        </div>");
        redirect('menu');

    }

    public function deleteSubMenu($id)
    {
        $this->Menu_model->deleteSubMenuById($id);
        $this->session->set_flashdata('message',
        "<div class='row mt-3'>
            <div class='col-md-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                You just delete a submenu
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            </div>
        </div>");
        redirect('menu/submenu');

    }

    public function editMenu($id)
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->Menu_model->getUserData();

        $data['menu'] = $this->Menu_model->getUserMenu();

        $this->form_validation->set_rules('menu','Menu','required');
        
        if ($this->form_validation->run() == FALSE ) {
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/index',$data);
            $this->load->view('templates/footer');
        }
        else{
            $this->Menu_model->editMenuById($id);
            $this->session->set_flashdata('message',
            "<div class='row mt-3'>
                <div class='col-md-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    You just edit a menu
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('menu');
        }
    }
    
    public function editSubMenu($id)
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->Menu_model->getUserData();

        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->Menu_model->getSubMenu();
        $data['menu'] = $this->Menu_model->getUserMenu();

        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('menu_id','Menu_id','required');
        $this->form_validation->set_rules('url','url','required');
        $this->form_validation->set_rules('icon','icon','required');
        
        if ($this->form_validation->run() == FALSE ) {
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/submenu',$data);
            $this->load->view('templates/footer');
        }
        else{
            $this->Menu_model->editSubMenuById($id);
            $this->session->set_flashdata('message',
            "<div class='row mt-3'>
                <div class='col-md-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    You just edit a submenu
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('menu/submenu');
        }
    }  

}