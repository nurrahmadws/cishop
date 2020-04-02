<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $is_login = $this->session->userdata('is_login');

        if($is_login)
        {
            redirect(base_url());
            return;
		}
		$this->load->model('Login_model');
    }

    public function index()
    {
        if (!$_POST) {
            $input = (object) $this->Login_model->getDefaultValues();
        }else{
            $input = (object) $this->input->post(null, true);
        }

        if (!$this->Login_model->validate()) {
            $data['title'] = 'Login';
            $data['input'] = $input;
            $data['page']  = 'pages/auth/login';

            $this->view($data);
            return;
        }

        if ($this->Login_model->run($input)) {
            $this->session->set_flashdata('success', 'Berhasil Melakukan Login');
            redirect(base_url());
        }else{
            $this->session->set_flashdata('error', 'Gagal Melakukan Login');
            redirect(base_url('/login'));
        }
    }

}

/* End of file Login.php */
