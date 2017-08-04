<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model("Item_model", "item");
	}	

	public function index()
	{
		$data['items'] = $this->item->activeItems();

		$this->load->view('shop/index', $data);
	}
}
