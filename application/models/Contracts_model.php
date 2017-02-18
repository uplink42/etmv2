<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

    /**
     * Returns a list of all contracts for a set of characters, optionally
     * filtered by states and types, and queries the eve API for any 
     * contractor names missing from the database
     * @param  string      $chars  
     * @param  string|null $filter contract type
     * @param  string      $state  contract state
     * @param  int|null    $new    only return last n new contracts
     * @return [array]              
     */
    public function getContracts(string $chars, string $filter = null, string $state, int $new = null) : array
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
        if ($filter) {
            $this->db->where('c.type', $filter);
        }
        if ($state == "active") {
            //active contract types
            $this->db->where("c.status IN
                ('outstanding', 'inProgress')");
            if ($new > 0) {
                $this->db->limit($new);
            }
        } else {
            //inactive contract types
            $this->db->where("c.status IN
                    ('deleted', 'completed', 'failed', 'completedByIssuer', 'completedByContractor', 'cancelled', 'rejected', 'reversed')");
        }

        $query  = $this->db->get('contracts');
        $result = $query->result_array();

        //modify the result array to include character names
        for ($i = 0; $i < count($result); $i++) {
            $issuer      = $result[$i]['issuer_id'];
            $acceptor    = $result[$i]['acceptor_id'];
            $contract_id = $result[$i]['contract_id'];

            $this->db->where('eve_idcharacters', $issuer);

            //check if a character name is already in the public table
            //we cache every new character name in the database for faster lookups in future
            $query           = $this->db->get('characters_public');
            $get_issuer_name = $query->row();

            if ($query->num_rows() == 1) {
                $result[$i]['issuer_name'] = $get_issuer_name->name;
            } else {
                $pheal                     = new Pheal();
                $response                  = $pheal->eveScope->CharacterName(array("ids" => $issuer));
                $name                      = $response->characters[0]->name;
                $result[$i]['issuer_name'] = $name;

                $data = array("eve_idcharacters" => $issuer,
                    "name"                           => $name);
                $this->db->replace('characters_public', $data);
            }

            //repeat the process for acceptor characters
            $this->db->where('eve_idcharacters', $acceptor);
            $query2            = $this->db->get('characters_public');
            $get_acceptor_name = $query->row();

            if ($query2->num_rows() == 1) {
                $result[$i]['acceptor_name'] = $get_acceptor_name->name;
            } else {
                $pheal                       = new Pheal();
                $response                    = $pheal->eveScope->CharacterName(array("ids" => $acceptor));
                $name                        = $response->characters[0]->name;
                $result[$i]['acceptor_name'] = $name;

                $data = array("eve_idcharacters" => $acceptor,
                    "name"                           => $name);
                $this->db->replace('characters_public', $data);
            }
        }
        return $result;
    }
}
