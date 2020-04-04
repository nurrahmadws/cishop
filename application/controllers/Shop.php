<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class SHop extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Shop_model');
	}

	public function sortby($sort, $page = null)
	{
		$data['title'] = 'Belanja';
		$data['content']	= $this->Shop_model->select(
			[
				'product.id', 'product.title AS product_title', 'product.image', 'product.description',
				'product.price', 'product.is_available',
				'category.title AS category_title','category.slug AS category_slug'
			]
		)
		->join('category')
		->where('product.is_available', 1)
		->orderBy('product.price', $sort)
		->paginate($page)
		->get();
		$data['total_rows']	= $this->Shop_model->where('product.is_available', 1)->count();
		$data['pagination']	= $this->Shop_model->makePagination(
			base_url("shop/shortby/$sort"), 4, $data['total_rows']
		);
        $data['page'] = 'pages/home/index';
        $this->view($data);
	}

	public function category($category, $page = null)
	{
		$data['title'] = 'Belanja';
		$data['content']	= $this->Shop_model->select(
			[
				'product.id', 'product.title AS product_title', 'product.image', 'product.description',
				'product.price', 'product.is_available',
				'category.title AS category_title', 'category.slug AS category_slug'
			]
		)
		->join('category')
		->where('product.is_available', 1)
		->where('category.slug', $category)
		->paginate($page)
		->get();
		$data['total_rows']	= $this->Shop_model->where('product.is_available', 1)->where('category.slug', $category)->join('category')->count();
		$data['pagination']	= $this->Shop_model->makePagination(
			base_url("shop/category/$category"), 4, $data['total_rows']
		);
		$data['category'] = ucwords(str_replace('-', ' ', $category));
        $data['page'] = 'pages/home/index';
        $this->view($data);
	}
}

/* End of file SHop.php */
