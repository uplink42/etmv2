<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Core\Config;
use Pheal\Pheal;

Config::getInstance()->cache  = new \Pheal\Cache\FileStorage(FILESTORAGE);
Config::getInstance()->access = new \Pheal\Access\StaticCheck();

class Contracts_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getContracts($chars, $filter = null, $state, $new = null)
    {
        $this->db->distinct();
        $this->db->select('c.eve_idcontracts as contract_id,
                           c.issuer_id as issuer_id,
                           c.acceptor_id as acceptor_id,
                           c.status as status,
                           c.availability as avail,
                           c.type as type,
                           c.creation_date as creation,
                           c.expiration_date as expiration,
                           c.price as price,
                           c.reward as reward,
                           c.fromStation_eve_idstation as from_id,
                           c.characters_eve_idcharacters as char_id,
                           s.name as station');
        $this->db->from('contracts c');
        $this->db->join('station s', 's.eve_idstation = c.fromStation_eve_idstation');
        $this->db->where('c.characters_eve_idcharacters IN ' . $chars);
        if($filter) {
            $this->db->where('c.type', $filter);
        }
        if($state == "active") {
            $this->db->where("c.status IN 
                ('outstanding', 'inProgress')");
                    if($new>0) {
                        $this->db->limit($new);
                    }
        } else {
                $this->db->where("c.status IN 
                    ('deleted', 'completed', 'failed', 'completedByIssuer', 'completedByContractor', 'cancelled', 'rejected', 'reversed')");
        }

        $query = $this->db->get('contracts');
        $result = $query->result_array();

        //modify the query array to include character names
        for ($i=0; $i<count($result); $i++) {
            $issuer = $result[$i]['issuer_id'];
            $acceptor = $result[$i]['acceptor_id'];
            $contract_id = $result[$i]['contract_id'];

            $this->db->where('eve_idcharacters', $issuer);
            $query = $this->db->get('characters_public');
            $get_issuer_name = $query->row();

            if($query->num_rows() ==1) {
                $result[$i]['issuer_name'] = $get_issuer_name->name;
            } else {
                $pheal    = new Pheal();
                $response = $pheal->eveScope->CharacterName(array("ids" => $issuer));
                $name = $response->characters[0]->name;
                $result[$i]['issuer_name'] = $name;

                $data = array("eve_idcharacters" => $issuer,
                              "name" => $name);
                $this->db->insert('characters_public', $data);
            }


            $this->db->where('eve_idcharacters', $acceptor);
            $query2 = $this->db->get('characters_public');
            $get_acceptor_name = $query->row();

            if($query2->num_rows() ==1) {
                $result[$i]['acceptor_name'] = $get_acceptor_name->name;
            } else {
                $pheal    = new Pheal();
                $response = $pheal->eveScope->CharacterName(array("ids" => $acceptor));
                $name = $response->characters[0]->name;
                $result[$i]['acceptor_name'] = $name;

                $data = array("eve_idcharacters" => $acceptor,
                              "name" => $name);
                $this->db->insert('characters_public', $data);
            }
        }
        

        return $result;
    }

}
