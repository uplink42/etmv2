<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public $stationFromID;
    public $stationToID;

    public $character_from;
    public $character_to;

    public $transFrom;
    public $transTo;

    private $fromCorpStandingValue;
    private $toCorpStandingValue;
    private $fromFactionStandingValue;
    private $toFactionStandingValue;

    private $level_broker_from;
    private $level_acc_from;

    private $level_broker_to;
    private $level_acc_to;

    public $brokerFeeFrom;
    public $brokerFeeTo;

    public $transTaxFrom = 1; // transaction tax is only for selling
    public $transTaxTo;

    public function tax($stationFromID, $stationToID, $character_from, $character_to, $transFrom, $transTo)
    {
        $this->stationFromID  = $stationFromID;
        $this->stationToID    = $stationToID;
        $this->transFrom      = $transFrom;
        $this->transTo        = $transTo;
        $this->character_from = $character_from;
        $this->character_to   = $character_to;

        $this->getFromCorpStanding($this->getCorpOwnerIDFromStation());
        $this->getToCorpStanding($this->getCorpOwnerIDToStation());
        $this->getFromFactionStanding($this->getFactionOwnerIDFromStation($this->getCorpOwnerIDFromStation()));
        $this->getToFactionStanding($this->getFactionOwnerIDToStation($this->getCorpOwnerIDToStation()));

        $this->getBrokerFromLevel();
        $this->getAccountingFromLevel();
        $this->getBrokerToLevel();
        $this->getAccountingToLevel();
    }

    public function getCorpOwnerIDFromStation()
    {
        $this->db->select('corporation_eve_idcorporation');
        $this->db->where('eve_idstation', $this->stationFromID);
        $query = $this->db->get('station');

        return $result = $query->row()->corporation_eve_idcorporation;
    }

    public function getCorpOwnerIDToStation()
    {
        $this->db->select('corporation_eve_idcorporation');
        $this->db->where('eve_idstation', $this->stationToID);
        $query = $this->db->get('station');

        return $result = $query->row()->corporation_eve_idcorporation;
    }

    public function getFactionOwnerIDFromStation($corpOwnerIDFromStation)
    {
        $this->db->select('faction_eve_idfaction');
        $this->db->where('eve_idcorporation', $corpOwnerIDFromStation);
        $query = $this->db->get('corporation');

        return $result = $query->row()->faction_eve_idfaction;
    }

    public function getFactionOwnerIDToStation($corpOwnerIDToStation)
    {
        $this->db->select('faction_eve_idfaction');
        $this->db->where('eve_idcorporation', $corpOwnerIDToStation);
        $query = $this->db->get('corporation');

        return $result = $query->row()->faction_eve_idfaction;
    }

    public function getFromCorpStanding($corpOwnerIDFromStation)
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_from);
        $this->db->where('corporation_eve_idcorporation', $corpOwnerIDFromStation);
        $query = $this->db->get('standings_corporation');

        if ($query->num_rows() == 0) {
            return $this->fromCorpStandingValue = 0;
        } else {
            return $this->fromCorpStandingValue = $query->row()->value;
        }
    }

    public function getToCorpStanding($corpOwnerIDToStation)
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_to);
        $this->db->where('corporation_eve_idcorporation', $corpOwnerIDToStation);
        $query = $this->db->get('standings_corporation');

        if ($query->num_rows() == 0) {
            return $this->toCorpStandingValue = 0;
        } else {
            return $this->toCorpStandingValue = $query->row()->value;
        }
    }

    public function getFromFactionStanding($factionOwnerIDFromStation)
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_from);
        $this->db->where('faction_eve_idfaction', $factionOwnerIDFromStation);
        $query = $this->db->get('standings_faction');

        if ($query->num_rows() == 0) {
            return $this->fromFactionStandingValue = 0;
        } else {
            return $this->fromFactionStandingValue = $query->row()->value;
        }
    }

    public function getToFactionStanding($factionOwnerIDToStation)
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_to);
        $this->db->where('faction_eve_idfaction', $factionOwnerIDToStation);
        $query = $this->db->get('standings_faction');

        if ($query->num_rows() == 0) {
            return $this->toFactionStandingValue = 0;
        } else {
            return $this->toFactionStandingValue = $query->row()->value;
        }
    }

    public function getBrokerFromLevel()
    {
        $this->db->select('broker_relations');
        $this->db->where('eve_idcharacter', $this->character_from);
        $query = $this->db->get('characters');

        return $this->level_broker_from = $query->row()->broker_relations;
    }

    public function getBrokerToLevel()
    {
        $this->db->select('broker_relations');
        $this->db->where('eve_idcharacter', $this->character_to);
        $query = $this->db->get('characters');

        return $this->level_broker_to = $query->row()->broker_relations;
    }

    public function getAccountingFromLevel()
    {
        $this->db->select('accounting');
        $this->db->where('eve_idcharacter', $this->character_from);
        $query = $this->db->get('characters');

        return $this->level_acc_from = $query->row()->accounting;
    }

    public function getAccountingToLevel()
    {
        $this->db->select('accounting');
        $this->db->where('eve_idcharacter', $this->character_to);
        $query = $this->db->get('characters');

        return $this->level_acc_to = $query->row()->accounting;
    }

    public function calculateBrokerFrom()
    {
        if ($this->transFrom == 'buy') {

            if ($this->stationFromID > 1000000000000 && $this->getCitadelTax($stationFromID)) {
                return
                $this->brokerFeeFrom = $this->getCitadelTax();

            } else {
                return
                $this->brokerFeeFrom = 1 + ((3 - (0.1 * (float) $this->level_broker_from + 0.03 * (float) $this->fromFactionStandingValue + 0.02 * (float) $this->fromCorpStandingValue)) / 100);
            }
        } else {
            return 1;
        }
    }

    public function calculateBrokerTo()
    {
        if ($this->transTo == 'sell') {
            if ($this->stationToID > 1000000000000 && $this->getCitadelTax($stationToID)) {
                return
                $this->brokerFeeFrom = $this->getCitadelTax();

            } else {
                return
                $this->brokerFeeTo = 1 - ((3 - (0.1 * (float) $this->level_broker_to + 0.03 * (float) $this->toFactionStandingValue + 0.02 * (float) $this->toCorpStandingValue)) / 100);
            }
        } else {
            return 1;
        }
    }

    public function calculateTaxFrom()
    {
        return $this->transTaxFrom;
    }

    public function calculateTaxTo()
    {
        return
        $this->transTaxTo = 1 - ((2 * (1 - (0.1 * $this->level_acc_to))) / 100); //returns in 0.x
    }

    private function getCitadelTax($stationID)
    {
        $this->db->select('value');
        $this->db->where('character_eve_idcharacter', $this->character_from);
        $this->db->where('station_eve_idstation', $stationID);
        $query = $this->db->get('citadel_tax');

        if ($query->num_rows != 0) {
            return
            $result = $query->row();
        } else {
            return false;
        }
    }
}
