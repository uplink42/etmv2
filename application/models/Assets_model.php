<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Assets_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Datatables', 'dt');
    }

    /**
     * Returns the daily asset evolution for a set of characters
     * @param  string $chars 
     * @return array        
     */
    public function getAssetEvolution(string $chars): array
    {
        $this->db->select('sum(total_assets) as a');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where("date>= (now() - INTERVAL 30 DAY)");
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query  = $this->db->get('net_history', 'date');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Gets all assets by each region in known space for a 
     * set of characters
     * @param  string $chars 
     * @return array        
     */
    public function getRegionData(string $chars): array
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
                        "total_value"   => $total_value,
                        "total_value_b" => $total_value/1000000000,
                        "region_id"     => $region_id));
            }
        }
        return $data;
    }

    /**
     * Returns the region name or all regions by id
     * @param  int    $region_id 
     * @return string            
     */
    public function getRegionName(int $region_id): string
    {
        if ($region_id != 0) {
            $this->db->select('name');
            $this->db->where('eve_idregion', $region_id);
            $query = $this->db->get('region');

            if ($query->num_rows() > 0) {
                $result = $query->row();
                return $result->name;
            } else {
                return Msg::REGION_NOT_FOUND;
            }
        } else {
            return "All";
        }
    }

    /**
     * Returns the sum of assets for a set of characters
     * @param  string $chars 
     * @return string        
     */
    public function getCurrentAssetTotals(string $chars): string
    {
        $this->db->select('sum(networth) as a');
        $this->db->where('eve_idcharacter IN ' . $chars);
        $query  = $this->db->get('characters');
        $result = $query->row()->a;

        return $result;
    }


    /**
     * Returns the list of all assets, optionally filtered by 
     * region or only significant assets
     * @param  int          $region_id   
     * @param  string       $chars       
     * @param  bool|boolean $significant 
     * @return string json                    
     */
    public function getAssetsList(array $configs): string
    {
        extract($configs);
        $this->db->start_cache();
        $this->db->select('a.item_eve_iditem as item_id,
            a.quantity as quantity,
            i.name as item_name,
            st.name as loc_name,
            i.eve_iditem as item_id,
            pr.price_evecentral as unit_value,
            c.name as owner,
            i.volume as unit_volume,
            (i.volume*a.quantity) as total_volume,
            (pr.price_evecentral*a.quantity) as total_value');
        $this->db->from('assets a');
        $this->db->join('item i', 'i.eve_iditem = a.item_eve_iditem');
        $this->db->join('characters c', 'c.eve_idcharacter = a.characters_eve_idcharacters');
        $this->db->join('station st', 'st.eve_idstation = a.locationID', 'left');
        $this->db->join('system sys1', 'sys1.eve_idsystem = st.system_eve_idsystem', 'left');
        $this->db->join('system sys2', 'sys2.eve_idsystem = a.locationID', 'left');
        $this->db->join('region r', 'r.eve_idregion = sys1.region_eve_idregion');
        $this->db->join('item_price_data pr', 'pr.item_eve_iditem = a.item_eve_iditem');
        $this->db->where('c.eve_idcharacter IN ' . $chars);

        if ($region_id != 0) {
            $this->db->where('r.eve_idregion', $region_id);
        }


        $result  = $this->dt->generate($defs, 'i.name');

        /*$this->db->select('a.item_eve_iditem as item_id,
            a.quantity as quantity,
            i.name as item_name,
            i.eve_iditem as item_id,
            sys.name as loc_name,
            pr.price_evecentral as unit_value,
            c.name as owner,
            i.volume as unit_volume,
            (i.volume*a.quantity) as total_volume,
            (pr.price_evecentral*a.quantity) as total_value');
        $this->db->from('assets a');
        $this->db->join('item i', 'i.eve_iditem = a.item_eve_iditem');
        $this->db->join('characters c', 'c.eve_idcharacter = a.characters_eve_idcharacters');
        $this->db->join('system sys', 'sys.eve_idsystem = a.locationID');
        $this->db->join('region r', 'r.eve_idregion = sys.region_eve_idregion');
        $this->db->join('item_price_data pr', 'pr.item_eve_iditem = a.item_eve_iditem');
        $this->db->where('c.eve_idcharacter IN' . $chars);

        if ($region_id != 0) {
            $this->db->where('r.eve_idregion', $region_id);
        }

        $query2 = $this->db->get_compiled_select();
        $query = $this->db->query($query1." UNION ".$query2);*/

        /*$query2  = $this->db->get();
        $result2 = $query2->result_array();
        $count2  = $query2->num_rows();
        $total   = $count1 + $count2;*/

        $sorted = sortData($result['data'], $defs);
        $data = json_encode(['data'            => injectIcons($sorted, true), 
                             'draw'            => (int)$result['draw'], 
                             'recordsTotal'    => $result['max'],
                             'recordsFiltered' => $result['max']]);
        return $data;
    }

   
    /**
     * Sends the required data to build the asset distribution chart
     * @param  array  $data 
     * @return string json       
     */
    public function buildAssetDistributionChart(array $data): string
    {
        $arrData["chart"] = array(
            "bgColor"                   => "#44464f",
            "paletteColors"             => "#0075c2,#1aaf5d,#f2c500,#3399ff,#ffcc99,#ff5050,#ff9900,#00802b,#009999,#666699,#ccffcc",
            "showBorder"                => "0",
            "use3DLighting"             => "0",
            "showShadow"                => "0",
            "enableSmartLabels"         => "0",
            "startingAngle"             => "0",
            "showPercentValues"         => "1",
            "showPercentInTooltip"      => "0",
            "decimals"                  => "1",
            "captionFontSize"           => "0",
            "subcaptionFontSize"        => "0",
            "subcaptionFontBold"        => "0",
            "toolTipColor"              => "#000000",
            "toolTipBorderThickness"    => "0",
            "toolTipBgColor"            => "#ffffff",
            "toolTipBgAlpha"            => "80",
            "toolTipBorderRadius"       => "2",
            "toolTipPadding"            => "5",
            "showHoverEffect"           => "1",
            "showLegend"                => "0",
            "pieSliceDepth"             => "20",
            "useDataPlotColorForLabels" => "1",
            "numberSuffix"              => " ISK"
        );

        $arrData["data"] = [];
        $region_names    = [];
        $region_values   = [];
        foreach ($data as $key => $value) {
            array_push($region_names, $key);
            array_push($region_values, $value[0]['total_value']);
        }

        for ($i = 0; $i < count($region_names); $i++) {
            array_push($arrData["data"], array("label" => (string) $region_names[$i],
                "value"                                    => (string) $region_values[$i]));
        }

        $arrData["chart"];
        $jsonEncodedData = json_encode($arrData);
        return $jsonEncodedData;
    }
}
