<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller 
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
		$this->load->model('Cart_model');
	}
	
	public function index()
	{
		$data['title'] = 'Keranjang Belanja';
		$data['content'] = $this->Cart_model->select([
			'cart.id', 'cart.qty', 'cart.subtotal', 'product.title', 'product.image', 'product.price'
			])
			->join('product')
			->where('cart.id_user', $this->id)
			->get();
		$data['page'] = 'pages/cart/index';
		return $this->view($data);
	}
		
	public function add()
	{
		if (!$_POST || $this->input->post('qty') < 1) {
			$this->session->set_flashdata('error', 'Kuantitas Produk Tidak Boleh kosong');
			redirect(base_url());
		}else{
			$input = (object) $this->input->post(null, true);
			$this->Cart_model->table = 'product';
			$product = $this->Cart_model->where('id', $input->id_product)->first();
			$subtotal	= $product->price * $input->qty;
			
			$this->Cart_model->table = 'cart';
			$cart = $this->Cart_model->where('id_user', $this->id)->where('id_product', $input->id_product)->first();
			
			if ($cart) {
				$data = [
					'qty'		=> $cart->qty + $input->qty,
					'subtotal'	=> $cart->subtotal + $subtotal
				];
				
				if($this->Cart_model->where('id', $cart->id)->update($data))
				{
					$this->session->set_flashdata('success', 'Produk Berhasil Ditambahkan');
				}else{
					$this->session->set_flashdata('error', 'Produk Gagal Ditambahkan');
				}
				redirect(base_url(''));
			}
			
			$data = [
				'id_user'	=> $this->id,
				'id_product'=> $input->id_product,
				'qty'		=> $input->qty,
				'subtotal'	=> $subtotal
			];
			
			if($this->Cart_model->create($data))
			{
				$this->session->set_flashdata('success', 'Produk Berhasil Ditambahkan');
			}else{
				$this->session->set_flashdata('error', 'Produk Gagal Ditambahkan');
			}
			redirect(base_url(''));
		}
	}
		
	public function update($id)
	{
		if (!$_POST || $this->input->post('qty') < 1) {
			$this->session->set_flashdata('error', 'Kuantitas Produk Tidak Boleh kosong');
			redirect(base_url('cart/index'));
		}
		
		$data['content'] = $this->Cart_model->where('id', $id)->first();
		if(!$data['content'])
		{
			$this->session->set_flashdata('warning', 'Data Tidak Ditemukan');
			redirect(base_url('cart/index'));
		}
		
		$data['input'] = (object) $this->input->post(null, true);
		$this->Cart_model->table = 'product';
		$product = $this->Cart_model->where('id', $data['content']->id_product)->first();
		
		$subtotal = $data['input']->qty * $product->price;
		$cart = [
			'qty'		=> $data['input']->qty,
			'subtotal'	=> $subtotal
		];
		
		$this->Cart_model->table = 'cart';
		
		if($this->Cart_model->where('id', $id)->update($cart))
		{
			$this->session->set_flashdata('success', 'Produk Berhasil Ditambahkan');
		}else{
			$this->session->set_flashdata('error', 'Produk Gagal Ditambahkan');
		}
		redirect(base_url('cart'));
	}
		
	public function delete($id)
	{
		if (!$_POST) {
			redirect(base_url('cart'));
		}
		
		if (!$this->Cart_model->where('id', $id)->first()) {
			$this->session->set_flashdata('warning', 'Maaf! Data Tidak Ditemukan');
			redirect(base_url('cart'));
		}
		
		if($this->Cart_model->where('id', $id)->delete()){
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
		}else{
			$this->session->set_flashdata('error', 'Data Gagal Dihapus');
		}
		
		redirect(base_url('cart'));
	}
		
	}
	
	/* End of file Cart.php */
	