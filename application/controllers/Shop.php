<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model("Item_model", "item");
	}	

	public function index()
	{
		//$this->session->unset_userdata("orders");

		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$data['items'] = $this->item->activeItems();

		$data['orderHtml'] = $this->load->view('shop/order', ['orders' => $orders], TRUE);

		$this->load->view('header', $data);
		$this->load->view('shop/index', $data);
		$this->load->view('footer', $data);
	}

	public function checkout()
	{
		$stripe = array(
		  "secret_key"      => "sk_test_8xxcMFYiQqqsT3iGL1s7SDsi",
		  "publishable_key" => "pk_test_a78TDAY3K7ocfXBVBZIYC141"
		);

		/*
		\Stripe\Stripe::setApiKey($stripe['secret_key']);

		\Stripe\Stripe::setApiKey('sk_test_8xxcMFYiQqqsT3iGL1s7SDsi');
		$charge = \Stripe\Charge::create(array('amount' => 2000, 'currency' => 'usd', 'source' => 'tok_189fqt2eZvKYlo2CTGBeg6Uq' ));
		echo $charge;
		*/

		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$data = ['orders' => $orders];
		$data['stripe'] = $stripe;

		$this->load->view('header', $data);
		$this->load->view('shop/checkout', $data);
		$this->load->view('footer', $data);
	}

	public function charge()
	{
		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$token  = $_POST['stripeToken'];

		$stripeEmail = $this->input->post('stripeEmail');
		$stripeToken = $this->input->post('stripeToken');
		$stripeTokenType = $this->input->post('stripeTokenType');

		$stripe = array(
		  "secret_key"      => "sk_test_8xxcMFYiQqqsT3iGL1s7SDsi",
		  "publishable_key" => "pk_test_a78TDAY3K7ocfXBVBZIYC141"
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		$customer = \Stripe\Customer::create(array(
			'email' => $stripeEmail,
			'source'  => $stripeToken
		));

		$charge = \Stripe\Charge::create(array(
		  'customer' => $customer->id,
		  'amount'   => str_replace('.', '', (string) number_format($orders['total'], 2)),
		  'currency' => 'usd'
		));

	  	echo '<h1>Successfully charged $' . $orders['total'] . '!</h1>';
	}

	public function addToCart()
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
			return json([
				'success' => FALSE,
				'error' => 'Item not found',
			]);
		}

		if ($qty <= 0) {
			return json([
				'success' => FALSE,
				'error' => 'Please enter a valid quantity',
			]);
		}

		$item = $item->result()[0];

		$orderQty = (array_key_exists($id, $orders)) ? $orders['items'][$id]['qty'] : 0;

		$orderQty += $qty;

		if ($orderQty > $item->qty) {
			return json([
				'success' => FALSE,
				'error' => 'Only ' . $item->qty . ' item(s) available.',
			]);
		}

		$orders['items'][$id] = [
			'item' => $item,
			'qty' => $orderQty
		];

		$total = 0;

		foreach($orders['items'] as $order) {
			$total += $order['item']->price * $order['qty'];
		}

		$orders['total'] = $total;

		$this->session->set_userdata("orders", $orders);

		return json([
			'success' => TRUE,
			'orderHtml' => $this->load->view('shop/order', ['orders' => $orders], TRUE)
		]);

	}

	public function removeItem()
	{
		$orders = $this->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		$id = $this->input->post('id');
		
		$item = $this->item->getItem($id);

		if (!$item->result_id->num_rows) {
			return json([
				'success' => FALSE,
				'error' => 'Item not found',
			]);
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

		return json([
			'success' => TRUE,
			'orderHtml' => $this->load->view('shop/order', ['orders' => $orders], TRUE)
		]);
	}


}
