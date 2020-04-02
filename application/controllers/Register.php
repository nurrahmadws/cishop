<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $is_login = $this->session->userdata('is_login');
        if ($is_login) {
            redirect(base_url());
            return;
		}
		$this->load->model('Register_model');
    }

    public function index()
    {
        if (!$_POST) {
            $input = (object) $this->Register_model->getDefaultValues();
        }else{
            $input = (object) $this->input->post(null, true);
        }

        if (!$this->Register_model->validate()) {
            $data['title'] = 'Register';
            $data['input'] = $input;
            $data['page'] = 'pages/auth/register';
            $this->view($data);
            return;
        }

        if ($this->Register_model->run($input)) {
            $this->session->set_flashdata('success', 'Berhasil Melakukan Registrasi');
            redirect(base_url());
        }else{
            $this->session->set_flashdata('error', 'Data Gagal Disimpan');
            redirect(base_url('/register'));
        }
    }
}

/* End of file Register.php */
