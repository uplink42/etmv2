<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TradeRoutes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generates autocomplete results for station names
     * @param  string $input 
     * @return [array]        
     */
    public function queryStations(string $input): array
    {
        $this->db->select('name as value');
        $this->db->where('eve_idstation < 1000000000000');
        $this->db->like('name', $input);
        $this->db->limit('10');
        $query  = $this->db->get('station');
        $result = $query->result_array();

        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['value'] == 'Jita IV - Moon 4 - Caldari Navy Assembly Plant' ||
                $result[$i]['value'] == 'Amarr VIII (Oris) - Emperor Family Academy' ||
                $result[$i]['value'] == 'Rens VI - Moon 8 - Brutor Tribe Treasury' ||
                $result[$i]['value'] == 'Dodixie IX - Moon 20 - Federation Navy Assembly Plant' ||
                $result[$i]['value'] == 'Hek VIII - Moon 12 - Boundless Creation Factory') {
                $result[$i]['value'] = "TRADE HUB: " . $result[$i]['value'];
            }
        }
        return $result;
    }

    /**
     * Creates a new trade route between 2 stations for a user
     * @param  int    $user_id      
     * @param  string $station_from 
     * @param  string $station_to   
     * @return [array]               
     */
    public function insertRoute(int $user_id, string $station_from, string $station_to): array
    {
        $this->db->select('eve_idstation as s');
        $this->db->where('name', $station_from);
        $q1 = $this->db->get('station');

        $this->db->select('eve_idstation as s');
        $this->db->where('name', $station_to);
        $q2 = $this->db->get('station');

        if ($q1->num_rows() != 0 && $q2->num_rows() != 0) {
            $station1 = $q1->row()->s;
            $station2 = $q2->row()->s;

            //check if exists
            $this->db->where('station_eve_idstation_from', $station1);
            $this->db->where('station_eve_idstation_to', $station2);
            $this->db->where('user_iduser', $user_id);
            $check_routes = $this->db->get('traderoutes');

            if ($check_routes->num_rows() > 0) {
                $res = "error";
                $msg = "Trade Route already exists";
            } else {
                $data = array("user_iduser"  => $user_id,
                    "station_eve_idstation_from" => $station1,
                    "station_eve_idstation_to"   => $station2);
                $this->db->insert('traderoutes', $data);

                if ($this->db->affected_rows() != 0) {
                    $res = "success";
                    $msg = Msg::ROUTE_CREATE_SUCCESS;
                } else {
                    $res = "error";
                    $msg = Msg::DB_ERROR;
                }
            }

        } else {
            $res = "error";
            $msg = Msg::STATION_NOT_FOUND;
        }

        return array("message" => $msg, "notice" => $res);
    }

    /**
     * Returns the list of all trade routes for a user
     * @param  int    $user_id 
     * @return [array]          
     */
    public function getRoutes(int $user_id): array
    {
        $this->db->select('s1.name as s1, s2.name as s2, idtraderoute as id');
        $this->db->join('station s1', 's1.eve_idstation = traderoutes.station_eve_idstation_from');
        $this->db->join('station s2', 's2.eve_idstation = traderoutes.station_eve_idstation_to');
        $this->db->where('user_iduser', $user_id);
        $query  = $this->db->get('traderoutes');
        $result = $query->result();

        return $result;
    }

    /**
     * Deletes a trade route
     * @param  int    $route_id 
     * @return [bool]           
     */
    public function deleteRoute(int $route_id): bool
    {
        $this->db->where('idtraderoute', $route_id);
        $query = $this->db->delete('traderoutes');

        if ($query) {
            return true;
        }
        return false;
    }
}
