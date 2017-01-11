<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use Pheal\Pheal;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_outposts_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getOutposts(): int
    {
        $pheal        = new Pheal();
        $response     = $pheal->eveScope->ConquerableStationList();
        $outpostsList = array();

        foreach ($response->outposts as $outposts) {
            $idoutposts   = $outposts['stationID'];
            $name         = $outposts['stationName'];
            $eve_idsystem = $outposts['solarSystemID'];

            array_push($outpostsList,
                array("eve_idstation"           => $idoutposts,
                    "name"                          => $this->db->escape($name),
                    "system_eve_idsystem"           => $eve_idsystem,
                    "corporation_eve_idcorporation" => 1)
            );
        }

        return $this->insertData($outpostsList);
    }

    public function getCitadels(): int
    {
        $url    = "https://stop.hammerti.me.uk/api/citadel/all";
        $result = json_decode(file_get_contents($url), true);

        $citadelList = [];

        foreach ($result as $key => $row) {
            $id        = $key;
            $system_id = $row['systemId'];
            $name      = $row['name'];
            $type      = $row['typeName'] == "" ? "unknown type" : $row['typeName'];
            $nametype  = $name . " (" . $type . ")";

            if ($system_id != 0) {
                array_push($citadelList,
                    array("eve_idstation"           => $id,
                        "name"                          => $this->db->escape($nametype),
                        "system_eve_idsystem"           => $system_id,
                        "corporation_eve_idcorporation" => 1)
                );
            }
        }

        return $this->insertData($citadelList);
    }

    public function insertData(array $data): int
    {
        $this->db->trans_start();
        $this->db->query(
            chunk_ignore("station",
                array('eve_idstation', 'name', 'system_eve_idsystem', 'corporation_eve_idcorporation'), $data)
        );
        $this->db->trans_complete();

        if ($this->db->trans_status() === true) {
            $count = (int) count($data);
            return $count;
        }
    }

}
