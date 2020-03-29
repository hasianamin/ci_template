<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');

        $this->load->model('Auth_model');
        
    }

    public function index()
    {
        if($this->session->userdata('email')){
            redirect('user');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        
        //validasi
        if($this->form_validation->run() == FALSE){
            $data['title'] = 'WPU User Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        }
        else{
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->Auth_model->getUserByEmail($email);

        //cek user
        if($user){
            //jika user aktif
            if($user['is_active'] == 1){
                //cek password
                if(password_verify($password,$user['password'])){
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                    ];
                    $this->session->set_userdata($data);
                    if($user['role_id'] == 1){
                        redirect('admin');
                    }
                    else{
                        redirect('user');
                    }
                }
                else{
                    $this->session->set_flashdata('fail', 
                    "<div class='row mt-3'>
                        <div class='col'>
                            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Login failed, wrong password
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>");
                    redirect('auth');
                }

            }
            else{
                $this->session->set_flashdata('fail', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Login failed, email has not activated yet
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth');
            }

        }
        else{
            $this->session->set_flashdata('fail', 
            "<div class='row mt-3'>
                <div class='col'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Login failed, email has not registered yet
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('auth');
        }
    }
    
    public function registration()
    {
        if($this->session->userdata('email')){
            redirect('user');
        }
        
        //validation
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]',
            [
                'is_unique' => 'This email has already registered'
            ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]',
            [
                'matches' => 'Password not match',
                'min_length' => 'Password too short'
            ]);
        $this->form_validation->set_rules('password2', 'Rewrite Password', 'required|trim|matches[password1]');


        if($this->form_validation->run() == FALSE){
            $data['title']= 'WPU User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        }
        else{
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'),PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            // prepare token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];
            
            $this->Auth_model->insertToken($data, $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', 
            "<div class='row mt-3'>
                <div class='col'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Congratulation you just create an account, Please verify you email
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('auth');
        }

    }

    //untuk kirim email
    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'aminhasian14@gmail.com',
            'smtp_pass' => 'gabbydemana',
            'smtp_port' => 465,
            'mailtype' =>  'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];

        $this->load->library('email',$config);
        $this->email->initialize($config); 

        $this->email->from('aminhasian@gmail.com','Hasian Amin');
        $this->email->to($this->input->post('email'));

        if($type == 'verify'){
            
            $this->email->subject('Account verification');
            $this->email->message('Click this link to verify your account: <a href="'.base_url().'auth/verify?email='.$this->input->post('email').'&token='.urlencode($token).'">Activate</a>');
        }
        elseif($type == 'forgot'){
            $this->email->subject('Reset Password');
            $this->email->message('Click this link to reset your password: <a href="'.base_url().'auth/resetpassword?email='.$this->input->post('email').'&token='.urlencode($token).'">Reset Password</a>');
        }
        
        if($this->email->send()){
            return true;
        }
        else{
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->Auth_model->getUserByEmail($email);
        if($user){
            $user_token = $this->Auth_model->getUserToken($token);
            if($user_token){
                if(time() - $user_token['date_created'] < 86400){
                    $this->Auth_model->setVerify($email);
                    
                    $this->session->set_flashdata('message', 
                    "<div class='row mt-3'>
                        <div class='col'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            ".$email." has been activated. Please login 
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>");
                    redirect('auth');
                }
                else{
                    $this->Auth_model->setExpiredVerify($email);
                    $this->session->set_flashdata('message', 
                    "<div class='row mt-3'>
                        <div class='col'>
                            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Account activation failed! Token expired.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>");
                    redirect('auth');
                }
            }
            else{
                $this->session->set_flashdata('message', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Account activation failed! Invalid token.
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth');
            }

        }
        else{
            $this->session->set_flashdata('message', 
            "<div class='row mt-3'>
                <div class='col'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Account activation failed! Wrong email.
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                </div>
            </div>");
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', 
        "<div class='row mt-3'>
            <div class='col'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                You have been logged out
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            </div>
        </div>");
        redirect('auth');
    }

    public function blocked()
    {
        $data['title']= '403 - Access Forbidden';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/blocked');
        $this->load->view('templates/auth_footer');
    }

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email','Email','required|trim|valid_email');

        if($this->form_validation->run() == false){
            $data['title'] = 'WPU User Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot_password');
            $this->load->view('templates/auth_footer');
        }
        else{
            $email = $this->input->post('email');
            $user = $this->db->get_where('user',['email' => $email, 'is_active' => 1])->row_array();

            if($user){
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token',$user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Password already reset, please check your email
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth/forgotpassword');

            }
            else{
                $this->session->set_flashdata('message', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Email is not registered yet or not activated!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user',['email' => $email])->row_array();

        if($user){
            $user_token = $this->db->get_where('user_token',['token' => $token])->row_array();
            if($user_token){
                if(time() - $user_token['date_created'] > 86400){
                    $this->Auth_model->setExpiredVerify($email);
                    $this->session->set_flashdata('message', 
                    "<div class='row mt-3'>
                        <div class='col'>
                            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Reset password failed! Token expired.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>");
                    redirect('auth');
                }
                else{
                    //jika benar
                    $this->session->set_userdata('reset_email', $email);
                    $this->changePassword();
                }
                    
            }
            else{
                $this->session->set_flashdata('message', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Reset password failed, wrong token!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth/forgotpassword');
            }

        }
        else{
            $this->session->set_flashdata('message', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Reset password failed, wrong email!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth/forgotpassword');
        }
    }

    //ubah password
    public function changePassword()
    {
        if(!$this->session->userdata('reset_email')){
            redirect('auth');
        }
        $this->form_validation->set_rules('password1','Password','trim|required|min_length[8]|matches[password2]');
        $this->form_validation->set_rules('password2','Repeat Password','trim|required|min_length[8]|matches[password1]');
        if($this->form_validation->run() == false){
            $data['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change_password');
            $this->load->view('templates/auth_footer');
        }
        else{
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password',$password);
            $this->db->where('email',$email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');
            $this->session->set_flashdata('message', 
                "<div class='row mt-3'>
                    <div class='col'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Password has been changed, please login
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    </div>
                </div>");
                redirect('auth');

        }
    }
}
