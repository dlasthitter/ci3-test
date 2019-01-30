<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Shop extends REST_Controller 
{
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['addtocart_post']['limit'] = 500; 
        $this->methods['removeitem_post']['limit'] = 500; 

		$this->load->model("Item_model", "item");
		$this->load->model("Transactions_model", "transactions");
    }

    /**
     * @api {get} shop/addtocart Add to cart
     * @apiVersion 1.0.0
     * @apiGroup Shop
     * @apiName Add to cart
     *
     * @apiParam {Number} id Product ID
     * @apiParam {Number} qty Quantity
     */
    public function addtocart_post()
    {
    	$id = $this->input->post('id');
		$qty = (int)$this->input->post('qty');

		$orders = getOrders($this);

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

		//$orderQty = 0;

		//if (isset($orders['items'])) {
		//	$orderQty = (array_key_exists($id, $orders['items'])) ? $orders['items'][$id]['qty'] : 0;
		//}

		$orderQty = $qty;
	
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

		$orders['total'] = calculateTotal($orders);

		$this->session->set_userdata("orders", $orders);

		$json['success'] = TRUE;

		if (ENVIRONMENT != 'testing') {
			$json['orderHtml'] = $this->load->view('shop/order', ['orders' => $orders], TRUE);
		}

        return $this->set_response($json, REST_Controller::HTTP_CREATED); 
    }

	/**
     * @api {get} shop/removeitem Remove item from the cart
     * @apiVersion 1.0.0
     * @apiGroup Shop
     * @apiName Remove item
     *
     * @apiParam {Number} id Product ID
     */
    public function removeitem_post()
	{
		$orders = getOrders($this);

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
			$orders['total'] = calculateTotal($orders);
		}

		$this->session->set_userdata("orders", $orders);

		$json['success'] = TRUE;

		if (ENVIRONMENT != 'testing') {
			$json['orderHtml'] = $this->load->view('shop/order', ['orders' => $orders], TRUE);
		}

        return $this->set_response($json, REST_Controller::HTTP_CREATED); 
	}

	/**
     * @api {get} shop/teststripe Test Stripe payment gateway
     * @apiVersion 1.0.0
     * @apiGroup Shop
     * @apiName Test Stripe payment
     *
     */
	public function teststripe_post()
	{
		$data['stripe'] = $this->config->item('stripe');
		\Stripe\Stripe::setApiKey($data['stripe']['secret_key']);

		$result = \Stripe\Token::create(
            array(
                "card" => array(
                    "number" => "4242424242424242",
				    "exp_month" => 1,
				    "exp_year" => 2022,
				    "cvc" => "314"
                )
            )
        );

        $token = $result['id'];

        $charge = \Stripe\Charge::create(array(
              "amount" => 50*100,
              "currency" => "usd",
              "card" => $token,
              "description" => "Charge for test@example.com" 
        ));

        $json['success'] = TRUE;

        return $this->set_response($json, REST_Controller::HTTP_CREATED); 
	}
}