<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions_model extends CI_Model 
{
	const STATUS_PENDING = 0;
	const STATUS_SUCCESS = 1;
	const STATUS_FAILED = 2;

	public function getTxnByToken($token)
	{
		return $this->db
			->where("token", $token)
			->get("transactions");
	}

	public function create($stripeToken, $stripeEmail, $stripeTokenType, $orders)
	{
		$data = [
			'token'      => $stripeToken,
			'email'      => $stripeEmail,
			'type'       => $stripeTokenType,
			'amount'     => $orders['total'],
			'status'     => self::STATUS_PENDING,
			'created_at' => date('Y-m-d h:i:s')
		];

		$this->db->insert("transactions", $data);

		$txn_id = $this->db->insert_id();
		$qry = $this->db->get_where('transactions', ['txn_id' => $txn_id]);

		$txn = $qry->row();

		/**
		 * Save details
		 */
		foreach($orders['items'] as $id => $row) {
			$details = [
				'txn_id'     => $txn_id,
				'qty'        => $row['qty'],
				'price'      => $row['item']->price,
				'item_id'    => $id, 
				'created_at' => date('Y-m-d h:i:s'),
			];

			$this->db->insert("transaction_details", $details);
		}

		return $txn;
	}

	public function updateStatus($txn_id, $status)
	{
		$this->db->set('status', $status, FALSE);
        $this->db->where("txn_id", $txn_id);  
        return $this->db->update("transactions");
	}
}