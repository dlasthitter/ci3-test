<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Shop extends REST_Controller {
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['addtocart_post']['limit'] = 500; 
        $this->methods['removeitem_post']['limit'] = 500; 

		$this->load->model("Item_model", "item");
		$this->load->model("Transactions_model", "transactions");
    }

    public function addtocart_post()
    {
    	$id = $this->input->post('id');
		$qty = (int)$this->input->post('qty');

		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		/**
		 * Check if item found and has enough quantity
		 */
		$item = $this->item->getItem($id);

		if (!$item->result_id->num_rows) {
			return $this->set_response([
				'success' => FALSE,
				'error'   => 'Item not found',
			], REST_Controller::HTTP_CREATED);
		}

		if ($qty <= 0) {
			return $this->set_response([
				'success' => FALSE,
				'error'   => 'Please enter a valid quantity',
			], REST_Controller::HTTP_CREATED);
		}

		$item = $item->row();

		$orderQty = 0;

		if (isset($orders['items'])) {
			$orderQty = (array_key_exists($id, $orders['items'])) ? $orders['items'][$id]['qty'] : 0;
		}

		$orderQty += $qty;
	
		if ($orderQty > $item->qty) {
			return $this->set_response([
				'success' => FALSE,
				'error'   => 'Only ' . $item->qty . ' item(s) available.',
			], REST_Controller::HTTP_CREATED);
		}

		$orders['items'][$id] = [
			'item' => $item,
			'qty'  => $orderQty
		];

		$total = 0;

		foreach($orders['items'] as $order) {
			$total += $order['item']->price * $order['qty'];
		}

		$orders['total'] = $total;

		$this->session->set_userdata("orders", $orders);

        return $this->set_response([
			'success'   => TRUE,
			'orderHtml' => $this->load->view('shop/order', ['orders' => $orders], TRUE)
		], REST_Controller::HTTP_CREATED); 
    }

    public function removeitem_post()
	{
		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$id = $this->input->post('id');
		
		$item = $this->item->getItem($id);

		if (!$item->result_id->num_rows) {
			return $this->set_response([
				'success' => FALSE,
				'error'   => 'Item not found',
			], REST_Controller::HTTP_CREATED);
		}

		if (isset($orders['items']) AND array_key_exists($id, $orders['items'])) {
			unset($orders['items'][$id]);
		}

		if (!isset($orders['items']) OR empty($orders['items'])) {
			$orders = [];
		}

		if ($orders) {
			$total = 0;

			foreach($orders['items'] as $order) {
				$total += $order['item']->price * $order['qty'];
			}

			$orders['total'] = $total;
		}

		$this->session->set_userdata("orders", $orders);

		return $this->set_response([
			'success'   => TRUE,
			'orderHtml' => $this->load->view('shop/order', ['orders' => $orders], TRUE)
		], REST_Controller::HTTP_CREATED);
	}
}