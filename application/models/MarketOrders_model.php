<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MarketOrders_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
    }

	public function getMarketOrders($chars)
	{
		$this->db->where('o.characters_eve_idcharacters IN '.$chars);

	}

}
