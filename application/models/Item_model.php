<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

    public function __construct()
    {
        parent::__construct();
    }

    public function activeItems()
    {
    	return $this->db
			->where("status", self::STATUS_ACTIVE)
			->get("items");

    }

    function getItem($item_id=0)
    {
        return $this->db
            ->where("item_id", $item_id)
            ->get("items");
    }
   
}
