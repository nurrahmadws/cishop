<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model('User_model');
	}
	

	public function index($page = null)
	{
		$data['title'] 		= 'Admin: Pengguna';
		$data['content']	= $this->User_model->paginate($page)->get();
		$data['total_rows'] = $this->User_model->count();
		$data['pagination'] = $this->User_model->makePagination(
			base_url('user'), 2, $data['total_rows']
		);
		$data['page']		= 'pages/user/index';

		$this->view($data);
	}

	public function create()
	{
		if (!$_POST) {
			$input	= (object) $this->User_model->getDefaultValues();
		} else {
			$input	= (object) $this->input->post(null, true);
			$this->load->library('form_validation');
			$this->form_validation->set_rules('password', 'password', 'required|min_length[8]');
			$input->password = hashEncrypt($input->password);
		}

		if (!empty($_FILES) && $_FILES['image']['name'] !== '') {
			$imageName	= url_title($input->name, '-', true) . '-' . date('YmdHis');
			$upload		= $this->User_model->uploadImage('image', $imageName);
			if ($upload) {
				$input->image	= $upload['file_name'];
			} else {
				redirect(base_url('user/create'));
			}
		}

		if (!$this->User_model->validate()) {
			$data['title']			= 'Tambah Pengguna';
			$data['input']			= $input;
			$data['form_action']	= base_url('user/create');
			$data['page']			= 'pages/user/form';

			$this->view($data);
			return;
		}

		if ($this->User_model->create($input)) {
			$this->session->set_flashdata('success', 'Data berhasil disimpan!');
		} else {
			$this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
		}

		redirect(base_url('user'));
	}

	public function unique_email()
	{
		$email		= $this->input->post('email');
		$id			= $this->input->post('id');
		$user	= $this->User_model->where('email', $email)->first();

		if ($user) {
			if ($id == $user->id) {
				return true;
			}
			$this->load->library('form_validation');
			$this->form_validation->set_message('unique_email', '%s sudah digunakan!');
			return false;
		}

		return true;
	}

}

/* End of file User.php */
