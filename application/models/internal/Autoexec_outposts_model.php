<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Pheal;

class Autoexec_outposts_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getOutposts()
    {
        $pheal        = new Pheal();
        $response     = $pheal->eveScope->ConquerableStationList();
        $outpostsList = array();

        foreach ($response->outposts as $outposts) {
            $idoutposts   = $outposts['stationID'];
            $name         = $outposts['stationName'];
            $eve_idsystem = $outposts['solarSystemID'];

            array_push($outpostsList,
                array("eve_idstation"               => $idoutposts,
                    "name"                          => $this->db->escape($name),
                    "system_eve_idsystem"           => $eve_idsystem,
                    "corporation_eve_idcorporation" => 1)
            );
        }

        return $this->insertData($outpostsList);
    }

    public function getCitadels()
    {
        $url = "https://stop.hammerti.me.uk/api/citadel/all";
        $result = json_decode(file_get_contents($url), JSON_UNESCAPED_SLASHES);

        $citadelList = [];

        foreach ($result as $key => $row) {
            $id        = $key;
            $system_id = $row['systemId'];
            $name      = $row['name'];
            $type      = $row['typeName'] == "" ? "unknown type" : $row['typeName'];
            $nametype  = $name . " (" . $type . ")";

            if($system_id != 0) {
                array_push($citadelList,
                    array("eve_idstation"                 => $id,
                          "name"                          => $this->db->escape($nametype),
                          "system_eve_idsystem"           => $system_id,
                          "corporation_eve_idcorporation" => 1)
                );
            }
        }

        return $this->insertData($citadelList);
    }

    public function insertData($data)
    {
        $this->db->trans_start();
        $this->db->query(
            batch("station",
                array('eve_idstation', 'name', 'system_eve_idsystem', 'corporation_eve_idcorporation'), $data)
        );
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $count = count($data);
            return $count;
        }
    }

}
