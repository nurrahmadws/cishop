<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller 
{
    
    public function __construct()
    {
        parent::__construct();
		//Do your magic here
		$this->load->model('Category_model');
    }
    

    public function index($page = null)
    {
        $data['title']      = 'Admin: Category';
        $data['content']    = $this->Category_model->paginate($page)->get();
        $data['total_rows'] = $this->Category_model->count();
        $data['pagination'] = $this->Category_model->makePagination(base_url('category'), 2, $data['total_rows']);
        $data['page']       = 'pages/category/index';
        $this->view($data);
	}

	public function search($page = null)
	{
		if (isset($_POST['keyword'])) {
			$this->session->set_userdata('keyword', $this->input->post('keyword'));
		} else {
			redirect(base_url('category'));
		}

		$keyword	= $this->session->userdata('keyword');
		$data['title']		= 'Admin: Category';
		$data['content']	= $this->Category_model->like('title', $keyword)->paginate($page)->get();
		$data['total_rows']	= $this->Category_model->like('title', $keyword)->count();
		$data['pagination']	= $this->Category_model->makePagination(
			base_url('category/search'), 3, $data['total_rows']
		);
		$data['page']		= 'pages/category/index';
		
		$this->view($data);
	}

	public function reset()
	{
		$this->session->unset_userdata('keyword');
		redirect(base_url('category'));
	}
	
	public function	create()
	{
		if (!$_POST) {
			$input = (object) $this->Category_model->getDefaultValues();
		}else{
			$input = (object) $this->input->post(null, true);
		}

		if(!$this->Category_model->validate()){
			$data['title'] = 'Tambah Kategori';
			$data['input'] = $input;
			$data['form_action'] = base_url("category/create");
			$data['page'] = 'pages/category/form';

			$this->view($data);
			return;
		}

		if ($this->Category_model->create($input)) {
			$this->session->set_flashdata('success', 'Data Kategori Berhasil Disimpan');
		}else{
			$this->session->set_flashdata('error', 'Data Kategori gagal Disimpan');
		}
		redirect(base_url('category'));
	}

	public function edit($id)
	{
		$data['content'] = $this->Category_model->where('id', $id)->first();
		if(!$data['content'])
		{
			$this->session->set_flashdata('warning', 'Maaf! Data Tidak Ditemukan');
			redirect(base_url('category'));
		}

		if (!$_POST) {
			$data['input'] = $data['content'];
		}else{
			$data['input'] = (object) $this->input->post(null, true);
		}

		if(!$this->Category_model->validate()){
			$data['title'] = 'Edit Kategori';
			$data['form_action'] = base_url("category/edit/$id");
			$data['page'] = 'pages/category/form';

			$this->view($data);
			return;
		}

		if ($this->Category_model->where('id', $id)->update($data['input'])) {
			$this->session->set_flashdata('success', 'Data Berhasil Diperbaharui');
		}else{
			$this->session->set_flashdata('error', 'Data Gagal Diperbaharui');
		}
		redirect(base_url('category'));
	}

	public function delete($id)
	{
		if (!$_POST) {
			redirect(base_url('category'));
		}

		if (!$this->Category_model->where('id', $id)->first()) {
			$this->session->set_flashdata('warning', 'Maaf! Data Tidak Ditemukan');
			redirect(base_url('category'));
		}

		if($this->Category_model->where('id', $id)->delete()){
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
		}else{
			$this->session->set_flashdata('error', 'Data Gagal Dihapus');
		}

		redirect(base_url('category'));
	}

	public function unique_slug()
	{
		$slug = $this->input->post('slug');
		$id = $this->input->post('id');
		$category = $this->Category_model->where('slug', $slug)->first();

		if($category){
			if ($id == $category->id) {
				return true;
			}
			$this->load->library('form_validation');
			$this->form_validation->set_message('unique_slug', '%s sudah digunakan');
			return false;
		}

		return true;
	}
}

/* End of file Category.php */
