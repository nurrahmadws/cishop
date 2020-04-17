<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Myorder extends MY_Controller 
{

	private $id;
	public function __construct()
	{
		parent::__construct();
		$is_login = $this->session->userdata('is_login');
		$this->id = $this->session->userdata('id');

		if (!$is_login) {
			redirect(base_url());
			return;
		}
		$this->load->model('Myorder_model');
	}

	public function index()
	{
		$data['title'] = 'Daftar Order';
		$data['content'] = $this->Myorder_model->where('id_user', $this->id)
							->orderBy('date', 'DESC')
							->get();
		$data['page'] = 'pages/myorder/index';
		$this->view($data);
	}

	public function detail($invoice)
	{
		$data['order'] = $this->Myorder_model->where('invoice', $invoice)->first();
		if (!$data['order']) {
			$this->session->set_flashdata('warning', 'Data Tidak Ditemukan');
			redirect(base_url('/myorder'));
		}

		$this->Myorder_model->table = 'orders_detail';
		$data['order_detail'] = $this->Myorder_model->select([
			'orders_detail.id_orders', 'orders_detail.id_product', 'orders_detail.qty', 'orders_detail.subtotal', 'product.title', 'product.image', 'product.price'
		])
		->join('product')
		->where('orders_detail.id_orders' , $data['order']->id)
		->get();
		if($data['order']->status !== 'waiting')
		{
			$this->Myorder_model->table = 'orders_confirm';
			$data['order_confirm'] = $this->Myorder_model->where('id_orders', $data['order']->id)->first();
		}

		$data['page'] = 'pages/myorder/detail';

		$this->view($data);
	}

	public function confirm($invoice)
	{
		$data['order'] = $this->Myorder_model->where('invoice', $invoice)->first();
		if (!$data['order']) {
			$this->session->set_flashdata('warning', 'Data Tidak Ditemukan');
			redirect(base_url('/myorder'));
		}

		if ($data['order']->status !== 'waiting') {
			$this->session->set_flashdata('warning', 'Bukti Transfer Sudah Dikirim');
			return redirect(base_url("myorder/detail/$invoice"));
		}

		if (!$_POST) {
			$data['input'] = (object) $this->Myorder_model->getDefaultValues();
		}else{
			$data['input'] = (object) $this->input->post(null, true);
		}

		if (!empty($_FILES) && $_FILES['image']['name'] !== '') {
			$imageName	= url_title($invoice, '-', true) . '-' . date('YmdHis');
			$upload		= $this->Myorder_model->uploadImage('image', $imageName);
			if ($upload) {
				$data['input']->image	= $upload['file_name'];
			} else {
				redirect(base_url("myorder/confirm/$invoice"));
			}
		}

		if (!$this->Myorder_model->validate()) {
			$data['title']			= 'Konfirmasi Order';
			$data['form_action']	= base_url("myorder/confirm/$invoice");
			$data['page']			= 'pages/myorder/confirm';

			$this->view($data);
			return;
		}

		$this->Myorder_model->table = 'orders_confirm';

		if ($this->Myorder_model->create($data['input'])) {
			$this->Myorder_model->table = 'orders';
			$this->Myorder_model->where('id', $data['input']->id_orders)->update(['status' => 'paid']);
			$this->session->set_flashdata('success', 'Data berhasil disimpan!');
		} else {
			$this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
		}
		redirect(base_url("myorder/confirm/$invoice"));
	}

	public function image_required()
	{
		if (empty($_FILES) || $_FILES['image']['name'] === '') {
			$this->session->set_flashdata('image_error', 'Bukti Transfer Tidak Boleh Kosong');
			return false;
		}
		return true;
	}

}

/* End of file Myorder.php */
