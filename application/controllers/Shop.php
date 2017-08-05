<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model("Item_model", "item");
		$this->load->model("Transactions_model", "transactions");
	}	

	public function index()
	{
		//$this->session->unset_userdata("orders");
		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$data['items'] = $this->item->activeItems();

		$data['orderHtml'] = $this->load->view('shop/order', ['orders' => $orders], TRUE);

		render($this, 'shop/index', $data);
	}

	public function checkout()
	{	
		$data['stripe'] = $this->config->item('stripe');

		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$data['orders'] = $orders;
		
		render($this, 'shop/checkout', $data);
	}

	public function charge()
	{
		$data['stripe'] = $this->config->item('stripe');

		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$stripeEmail     = $this->input->post('stripeEmail');
		$stripeToken     = $this->input->post('stripeToken');
		$stripeTokenType = $this->input->post('stripeTokenType');

		$txn = $this->transactions->getTxnByToken($stripeToken);

		if (!$txn->result_id->num_rows AND !empty($orders)) {
			$txn = $this->transactions->create($stripeToken, $stripeEmail, $stripeTokenType, $orders);
		} else {
			$txn = $txn->row();
		}

		try {
			\Stripe\Stripe::setApiKey($data['stripe']['secret_key']);
			$customer = \Stripe\Customer::create(array(
				'email'  => $stripeEmail,
				'source' => $stripeToken
			));

			$charge = \Stripe\Charge::create(array(
			  'customer' => $customer->id,
			  'amount'   => str_replace('.', '', (string) number_format($orders['total'], 2, '.', '')),
			  'currency' => 'usd'
			));
			
			$data['total'] = $orders['total'];

			/**
			 * Update quantity
			 */
			foreach($orders['items'] as $id => $row) {
				$this->item->deductQty($id, $row['qty']);
			}

			$this->session->unset_userdata("orders");

			$this->transactions->updateStatus($txn->txn_id, Transactions_model::STATUS_SUCCESS);

			render($this, 'shop/success', $data);
		} catch(Exception $e) {
			$data['error'] = $e->getMessage();

			if ($txn->status == Transactions_model::STATUS_PENDING) {
				$this->transactions->updateStatus($txn->txn_id, Transactions_model::STATUS_FAILED);
			}

			render($this, 'error', $data);
		}
	}
}
