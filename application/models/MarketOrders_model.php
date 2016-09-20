<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MarketOrders_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
    }

	public function getMarketOrders($chars, $type)
	{
		$this->db->select('o.eve_item_iditem as item_id, 
						   i.name as item_name, 
						   s.eve_idstation as station_id,
						   o.price as price_unit,
						   o.volume_remaining as vol,
						   (o.price*o.volume_remaining) as price_total,
						   o.escrow as escrow,
						   o.order_range as range,
						   o.date as date,
						   o.characters_eve_idcharacters as character_id,
						   c.name as character,
                           sys.eve_idsystem as idsystem');
        $this->db->from('orders o');
		$this->db->join('item i', 'i.eve_iditem = o.eve_item_iditem', 'left');
		$this->db->join('station s', 's.eve_idstation = o.station_eve_idstation');
        $this->db->join('characters c', 'c.eve_idcharacter = o.characters_eve_idcharacters');
        $this->db->join('system sys', 'sys.eve_idsystem = s.system_eve_idsystem');
        $this->db->where('o.characters_eve_idcharacters IN '.$chars);
        $this->db->where('o.type', $type);
        $query = $this->db->get();

        $result = $query->result_array();
        return $result;
	}

}
