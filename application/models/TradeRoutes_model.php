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

    public function insertRoute($user_id, $station_from, $station_to)
    {
        $this->db->select('eve_idstation as s');
        $this->db->where('name', $station_from);
        $q1 = $this->db->get('station');

        $this->db->select('eve_idstation as s');
        $this->db->where('name', $station_to);
        $q2 = $this->db->get('station');

        if($q1->num_rows() !=0 && $q2->num_rows() !=0) {
            $station1 = $q1->row()->s;
            $station2 = $q2->row()->s;

            //check if exists
            $this->db->where('station_eve_idstation_from', $station1);
            $this->db->where('station_eve_idstation_to', $station2);
            $this->db->where('user_iduser', $user_id);
            $check_routes = $this->db->get('traderoutes');

            if($check_routes->num_rows() >0) {
                $res = "error";
                $msg = "Trade Route already exists";
            } else {
            $data = array("user_iduser" => $user_id,
                          "station_eve_idstation_from" => $station1,
                          "station_eve_idstation_to" => $station2);
            $this->db->insert('traderoutes', $data);

            if($this->db->affected_rows() !=0) {
                $res = "success";
                $msg = "Trade Route created successfully";
            } else {
                $res = "error";
                $msg = "Error communicating with database. Try again";
            }
        }

        } else {
            $res = "error";
            $msg = "Invalid stations provided";
        }

        return array("message" => $msg, "notice" => $res);
    }

    public function getRoutes($user_id)
    {
        $this->db->select('s1.name as s1, s2.name as s2, idtraderoute as id');
        $this->db->join('station s1', 's1.eve_idstation = traderoutes.station_eve_idstation_from');
        $this->db->join('station s2', 's2.eve_idstation = traderoutes.station_eve_idstation_to');
        $this->db->where('user_iduser', $user_id);
        $query = $this->db->get('traderoutes');
        $result = $query->result();

        return $result;
    }

    public function checkRouteBelong($route_id, $user_id)
    {
        $this->db->where('user_iduser', $user_id);
        $this->db->where('idtraderoute', $route_id);
        $query = $this->db->get('traderoutes');
        if($query->num_rows() !=0) {
            return true;
        }
        return false;
    }

    public function deleteRoute($route_id)
    {
        $this->db->where('idtraderoute', $route_id);
        $query = $this->db->delete('traderoutes');

        if($query) {
            return true;
        }
        return false;
    }


}
