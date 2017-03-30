<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public  $stationFromID;
    public  $stationToID;
    public  $characterFromID;
    public  $characterToID;
    public  $transFrom;
    public  $transTo;
    private $fromCorpStandingValue;
    private $toCorpStandingValue;
    private $fromFactionStandingValue;
    private $toFactionStandingValue;
    private $brokerLevelFrom;
    private $accountingLevelFrom;
    private $brokerLevelTo;
    private $accountingLevelTo;
    public  $brokerFeeFrom;
    public  $brokerFeeTo;
    private $ignoreCitadelTax;
    private $ignoreOutpostTax;
    private $ignoreStationTax;

    /**
     * Starts the tax calculation process. Dispatches to other
     * methods as needed
     * @param  string $stationFromID
     * @param  string $stationToID
     * @param  string $characterFromID
     * @param  string $characterToID
     * @param  string $transFrom
     * @param  string $transTo
     * @return void
     */
    public function tax(string $stationFromID, string $stationToID, string $characterFromID, string $characterToID, array $settings): void {
        $this->stationFromID     = $stationFromID;
        $this->stationToID       = $stationToID;
        $this->transFrom         = $settings['buy_behaviour'];
        $this->transTo           = $settings['sell_behaviour'];
        $this->characterFromID   = $characterFromID;
        $this->characterToID     = $characterToID;
        $this->ignoreCitadelTax  = $settings['citadel_tax_ignore'];
        $this->ignoreOutpostTax  = $settings['outpost_tax_ignore'];
        $this->ignoreStationTax  = $settings['station_tax_ignore'];

        // non citadels use standings
        if ($this->stationFromID < 1000000000000) {
            $corpIDFrom = $this->getCorpId('from');
            $this->getCorpStanding('from', $corpIDFrom);
            $this->getFactionStanding('from', $this->getFactionID($corpIDFrom));
        }

        if ($this->stationToID < 1000000000000) {
            $corpIDTo = $this->getCorpId('to');
            $this->getCorpStanding('to', $corpIDFrom);
            $this->getFactionStanding('to', $this->getFactionID($corpIDTo));
        }

        $this->getBrokerLevel('from');
        $this->getBrokerLevel('to');
        $this->getAccountingLevel('from');
        $this->getAccountingLevel('to');
    }

    private function getCorpId (string $type): string
    {
        $stationID;
        switch ($type) {
            case 'from':
                $stationID = $this->stationFromID;
            break;
            case 'to':
                $stationID = $this->stationToID;
            break;
        }

        $this->db->select('corporation_eve_idcorporation');
        $this->db->where('eve_idstation', $stationID);
        $query = $this->db->get('station');

        if ($query->row()) {
            return $query->row()->corporation_eve_idcorporation;
        } else {
            return 1;
        }  
    }

    
    private function getFactionID (string $corpID): string
    {
        $this->db->select('faction_eve_idfaction');
        $this->db->where('eve_idcorporation', $corpID);
        $query = $this->db->get('corporation');

        if ($query->row()) {
            return $query->row()->corporation_eve_idcorporation;
        } else {
            return 1;
        }  
    }

    
    private function getCorpStanding(string $type, string $corpID)
    {
        $characterID;
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
            break;
            case 'to':
                $characterID = $this->characterToID;
            break;
        }

        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $characterID);
        $this->db->where('corporation_eve_idcorporation', $corpID);
        $query = $this->db->get('standings_corporation');

        switch ($type) {
            case 'from':
                if ($query->num_rows() == 0) {
                    $this->fromCorpStandingValue = 0;
                } else {
                    $this->fromCorpStandingValue = $query->row()->value;
                }
            break;
            case 'to':
                if ($query->num_rows() == 0) {
                    $this->toCorpStandingValue = 0;
                } else {
                    $this->toCorpStandingValue = $query->row()->value;
                }
            break;
        }
    }

    
    private function getFactionStanding(string $type, string $factionID)
    {
        $characterID;
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
            break;
            case 'to':
                $characterID = $this->characterToID;
            break;
        }

        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $characterID);
        $this->db->where('faction_eve_idfaction', $factionID);
        $query = $this->db->get('standings_faction');

        switch ($type) {
            case 'from':
                if ($query->num_rows() == 0) {
                    $this->fromFactionStandingValue = 0;
                } else {
                    $this->fromFactionStandingValue = $query->row()->value;
                }
            break;
            case 'to':
                if ($query->num_rows() == 0) {
                    $this->toFactionStandingValue = 0;
                } else {
                    $this->toFactionStandingValue = $query->row()->value;
                }
            break;
        }
    }


    private function getBrokerLevel(string $type)
    {
        $characterID;
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
            break;
            case 'to':
                $characterID = $this->characterToID;
            break;
        }

        $this->db->select('broker_relations');
        $this->db->where('eve_idcharacter', $characterID);
        $query = $this->db->get('characters');

        switch ($type) {
            case 'from':
                $this->brokerLevelFrom = $query->row()->broker_relations;
            break;
            case 'to':
                $this->brokerLevelTo = $query->row()->broker_relations;
            break;
        }
    }


    private function getAccountingLevel(string $type)
    {
        $characterID;
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
            break;
            case 'to':
                $characterID = $this->characterToID;
            break;
        }

        $this->db->select('accounting');
        $this->db->where('eve_idcharacter', $characterID);
        $query = $this->db->get('characters');

        switch ($type) {
            case 'from':
                $this->accountingLevelFrom = $query->row()->accounting;
            break;
            case 'to':
                $this->accountingLevelTo = $query->row()->accounting;
            break;
        }
    }


    public function calculateBroker(string $type): float
    {
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
                $stationID   = $this->stationFromID;
            break;

            case 'to':
                $characterID = $this->characterToID;
                $stationID   = $this->stationToID;
            break;
        }

        if ($this->transFrom == 'buy' && $type === 'from' || $this->transFrom == 'sell' && $type === 'to') {
            $station_type = '';
            if ($stationID > 1000000000000) {
                $station_type = 'citadel';
            } else if ($stationID > 60014860 && $stationID < 1000000000000) {
                $type = 'outpost';
            } else {
                $station_type = 'station';
            }

            switch($station_type) {
                case 'station':
                    if ($this->ignoreStationTax) {
                        return 1;
                    } else {
                        return $this->getGenericTax($type);
                    }
                break;

                case 'outpost':
                    if ($this->ignoreOutpostTax) {
                        return 1;
                    } else {
                        return $this->getGenericTax($type);
                    }
                break;

                case 'citadel':
                    $citadelTax = $this->getCitadelTax($type);
                    if ($this->citadelTax) {
                        return 1;
                    } else {
                        return $this->getGenericTax($type);
                    }
                break;
            }
        } else {
            return 1;
        }
    }


    private function getGenericTax(string $type)
    {

        switch ($type) {
            case 'from':
                $faction = $this->fromFactionStandingValue;
                $corp    = $this->fromCorpStandingValue;
                $broker  = $this->brokerLevelFrom;
            break;

            case 'to':
                $faction = $this->toFactionStandingValue;
                $corp    = $this->toCorpStandingValue;
                $broker  = $this->brokerLevelTo;
            break;
        }

        $formula = ((3 - (0.1 * (float) $broker + 0.03 * (float) $faction + 0.02 * (float) $corp)) / 100);

        if ($type == 'from') {
            return (float) $this->brokerFeeFrom = 1 + $formula;
        } else if ($type == 'to') {
            return (float) $this->brokerFeeTo = 1 - $formula;
        }  
    }


    public function calculateTax(string $type): float
    {
        switch ($type) {
            case 'from':
                return 1;
            break;
            case 'to':
                return (float) 1 - ((2 * (1 - (0.1 * $this->accountingLevelTo))) / 100);
            break;
        }
    }


    /**
     * Returns the fixed tax associated with a citadel
     * @param  string $stationID
     * @return float
     */
    private function getCitadelTax(string $type)
    {
        // check if null tax
        if ($this->ignoreCitadelTax) {
            return 1;
        }

        $characterID = '';
        $stationID   = '';
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
                $stationID   = $this->stationFromID;
            break;

            case 'to':
                $characterID = $this->characterToID;
                $stationID   = $this->stationToID;
            break;
        }

        $this->db->select('value');
        $this->db->where('character_eve_idcharacter', $characterID);
        $this->db->where('station_eve_idstation', $stationID);
        $query = $this->db->get('citadel_tax');

        if ($query->num_rows != 0) {
            return $result = 1 + (float)$query->row()->value;
        } else {
            return false;
        }
    }
}