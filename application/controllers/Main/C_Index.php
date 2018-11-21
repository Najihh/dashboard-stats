<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Index extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Module/M_Table');
        $this->load->model('Module/M_Login');
    }
    
    public function session()
	{
		if($this->session->userdata('logged_in')) {

		} else {
			redirect(base_url('login'));
		}
	}

	public function index()
	{   
        $this->session();
        $data['title'] = "Dashboard";
        $data['side'] = "1";
		$this->load->view('Templates/V_Sidemenu', $data);
        $this->load->view('Templates/V_Navbar', $data);
        $this->load->view('Module/V_Dashboard');
        $this->load->view('Templates/V_Footer');
    }
    
    public function login()
    {
        $data['title'] = "Login Page";
        $data['loginerror'] = $this->session->flashdata('error');
		$this->load->view('Module/V_Login', $data);
        
    }

    public function validation()
    {
        $data['title'] = "Login Page";
        
        $username = $this->input->post('txtUsername');
        $password = $this->input->post('txtPassword');

        $this->form_validation->set_rules('txtUsername', 'email', 'required');
   		$this->form_validation->set_rules('txtPassword', 'password', 'required');

        if($this->form_validation->run() == FALSE) {
            $error = 'Masukkan Email / Password';
            
            $this->session->set_flashdata('error', $error);
            redirect(base_url('login'));
        }else{
            $login = $this->M_Login->getUser($username, $password);
            if($login == 1){
                $session = array(
                    'logged' => true,
                    'username' => $this->M_Login->getUsername($username)['0']['username']
                );
                $this->session->set_userdata('logged_in', $session);
                redirect(base_url('dashboard'));
            }else{
                $error = 'Email / Password Salah';
            
                $this->session->set_flashdata('error', $error);
                redirect(base_url('login'));
            }
        }
    }

    public function table()
    {   
        $this->session();
        $data['title'] = "Tabel List";
        $data['side'] = "3";
        $data['datatabel'] = $this->M_Table->getData();
        $this->load->view('Templates/V_Sidemenu', $data);
        $this->load->view('Templates/V_Navbar', $data);
        $this->load->view('Module/V_Table', $data);
        $this->load->view('Templates/V_Footer');
    }

    public function test()
    {
        $heroku = $this->M_Table->getData();
        print_r($heroku);
    }
}
