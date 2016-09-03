<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Assets_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAssetEvolution($chars)
    {
        $this->db->select('sum(total_assets) as a');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where("date>= (now() - INTERVAL 30 DAY)");
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('net_history', 'date');
        log_message('error', $this->db->last_query());
        $result = $query->result_array();

        return $result;
    }

    public function getRegionData($chars)
    {
        $this->db->where('isKS', '1');
        $this->db->order_by('name');
        $query   = $this->db->get('region');
        $regions = $query->result();

        $data = [];
        foreach ($regions as $row) {
            $region_id   = $row->eve_idregion;
            $region_name = $row->name;

            $query = $this->db->query('select sum(quantity) as quantity_f, sum(total_value) as value_f
                    from (
                    SELECT count(assets.idassets) as quantity, sum(assets.quantity*item_price_data.price_evecentral) as total_value
                    FROM assets
                    JOIN system ON system.eve_idsystem = assets.locationID
                    JOIN region ON region.eve_idregion = system.region_eve_idregion
                    JOIN item_price_data on assets.item_eve_iditem = item_price_data.item_eve_iditem
                    WHERE assets.characters_eve_idcharacters IN ' . $chars . ' AND region.eve_idregion = ' . $region_id .
                ' UNION
                    SELECT count(assets.idassets) as quantity, sum(assets.quantity*item_price_data.price_evecentral) as total_value
                    FROM assets
                    JOIN station ON station.eve_idstation = assets.locationID
                    JOIN system ON system.eve_idsystem = station.system_eve_idsystem
                    JOIN region ON region.eve_idregion = system.region_eve_idregion
                    JOIN item_price_data on assets.item_eve_iditem = item_price_data.item_eve_iditem
                    WHERE assets.characters_eve_idcharacters IN ' . $chars . ' AND region.eve_idregion = ' . $region_id . ') a');

            $total_items = $query->row()->quantity_f;

            if ($total_items > 0) {
                $total_value        = $query->row()->value_f;
                $data[$region_name] = [];

                array_push($data[$region_name],
                    array("total_items" => $total_items,
                        "total_value"       => $total_value,
                        "region_id"         => $region_id));
            }

        }
        return $data;
    }

    public function getRegionName($region_id)
    {
        if ($region_id != 0) {
            $this->db->select('name');
            $this->db->where('eve_idregion', $region_id);
            $query = $this->db->get('region');

            if ($query->num_rows() > 0) {
                $result = $query->row();
                return $result->name;
            } else {
                return "Unknown region";
            }
        } else {
            return "All";
        }
    }

    public function getCurrentAssetTotals($chars)
    {
        $this->db->select('sum(networth) as a');
        $this->db->where('eve_idcharacter IN ' . $chars);
        $query  = $this->db->get('characters');
        $result = $query->row()->a;
        return $result;
    }

    public function getRegionRatio($chars)
    {
        //get sum of all assets for current char
        //get sum of all assets for current char in current region

    }

    public function getAssetsList($region_id, $chars, $significant=1)
    {
        $this->db->select('a.item_eve_iditem as item_id,
            a.quantity as quantity,
            i.name as item_name,
            st.name as loc_name,
            i.eve_iditem as item_id,
            pr.price_evecentral as unit_value,
            c.name as owner,
            (pr.price_evecentral*a.quantity) as total_value');
        $this->db->from('assets a');
        $this->db->join('item i', 'i.eve_iditem = a.item_eve_iditem');
        $this->db->join('characters c', 'c.eve_idcharacter = a.characters_eve_idcharacters');
        $this->db->join('station st', 'st.eve_idstation = a.locationID', 'left');
        $this->db->join('system sys', 'sys.eve_idsystem = st.system_eve_idsystem');
        $this->db->join('region r', 'r.eve_idregion = sys.region_eve_idregion');
        $this->db->join('item_price_data pr', 'pr.item_eve_iditem = a.item_eve_iditem');
        $this->db->where('c.eve_idcharacter IN ' . $chars);
       
        if ($region_id != "all") {
            $this->db->where('r.eve_idregion', $region_id);
        }

        if($significant==1) {
            $this->db->having('(a.quantity*pr.price_evecentral) > 500000');
        }

        $query1  = $this->db->get();
        $result1 = $query1->result_array();

        $this->db->select('a.item_eve_iditem as item_id,
            a.quantity as quantity,
            i.name as item_name,
            i.eve_iditem as item_id,
            sys.name as loc_name,
            pr.price_evecentral as unit_value,
            c.name as owner,
            (pr.price_evecentral*a.quantity) as total_value');
        $this->db->from('assets a');
        $this->db->join('item i', 'i.eve_iditem = a.item_eve_iditem');
        $this->db->join('characters c', 'c.eve_idcharacter = a.characters_eve_idcharacters');
        $this->db->join('system sys', 'sys.eve_idsystem = a.locationID');
        $this->db->join('region r', 'r.eve_idregion = sys.region_eve_idregion');
        $this->db->join('item_price_data pr', 'pr.item_eve_iditem = a.item_eve_iditem');
        $this->db->where('c.eve_idcharacter IN' . $chars);

        if ($region_id != "all") {
            $this->db->where('r.eve_idregion', $region_id);
        }

        if($significant==1) {
            $this->db->having('(a.quantity*pr.price_evecentral) > 500000');
        }

        $query2  = $this->db->get();
        $result2 = $query2->result_array();

        $result = array_merge($result1, $result2);
        return $result;
    }

    public function getWorthSignificant($chars)
    {
        $this->db->select('sum(a.quantity*pr.price_evecentral) as total');
        $this->db->from('assets a');
        $this->db->join('item i', 'i.eve_iditem = a.item_eve_iditem');
        $this->db->join('characters c', 'c.eve_idcharacter = a.characters_eve_idcharacters');
        $this->db->join('item_price_data pr', 'pr.item_eve_iditem = a.item_eve_iditem');
        $this->db->where('c.eve_idcharacter IN ' . $chars);
        $this->db->where('(a.quantity*pr.price_evecentral) > 500000');

        $query1  = $this->db->get();
        $significant = $query1->row()->total;

        //get total worth
        $this->db->select('sum(networth) as sum');
        $this->db->where('eve_idcharacter IN '. $chars);
        $query3 = $this->db->get('characters');
        $total = $query3->row()->sum;

        $percent = ($significant/$total) *100;

        return $percent;
    }

}
