<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TradeRoutes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function queryStations($input)
    {
        $this->db->select('name as value');
        $this->db->where('eve_idstation < 1000000000000');
        $this->db->like('name', $input);
        $this->db->limit('5');
        $query = $this->db->get('station');
        $result = $query->result_array();

        for ($i=0; $i<count($result); $i++) {
            if($result[$i]['value'] == 'Jita IV - Moon 4 - Caldari Navy Assembly Plant' ||
               $result[$i]['value'] == 'Amarr VIII (Oris) - Emperor Family Academy' ||
               $result[$i]['value'] == 'Rens VI - Moon 8 - Brutor Tribe Treasury' ||
               $result[$i]['value'] == 'Dodixie IX - Moon 20 - Federation Navy Assembly Plant' ||
               $result[$i]['value'] == 'Hek VIII - Moon 12 - Boundless Creation Factory') {
                $result[$i]['value'] = "TRADE HUB: " . $result[$i]['value'];
            }
        }

        return $result;
    }


}
