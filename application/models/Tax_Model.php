<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax_model extends CI_Model
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

    public $transTaxFrom = 1;
    public $transTaxTo;

    private $ignoreCitadelTax;

    /**
     * Starts the tax calculation process. Dispatches to other
     * methods as needed
     * @param  string $stationFromID
     * @param  string $stationToID
     * @param  string $character_from
     * @param  string $character_to
     * @param  string $transFrom
     * @param  string $transTo
     * @return void
     */
    public function tax(string $stationFromID, string $stationToID, string $character_from, string $character_to,
        string $transFrom, string $transTo, bool $ignoreCitadelTax): void {
        $this->stationFromID    = $stationFromID;
        $this->stationToID      = $stationToID;
        $this->transFrom        = $transFrom;
        $this->transTo          = $transTo;
        $this->character_from   = $character_from;
        $this->character_to     = $character_to;
        $this->ignoreCitadelTax = $ignoreCitadelTax;

        if ($stationFromID < 1000000000000) {
            $this->getFromCorpStanding($this->getCorpOwnerIDFromStation());
            $this->getFromFactionStanding($this->getFactionOwnerIDFromStation($this->getCorpOwnerIDFromStation()));
        }

        if ($stationToID < 1000000000000) {
            $this->getToCorpStanding($this->getCorpOwnerIDToStation());
            $this->getToFactionStanding($this->getFactionOwnerIDToStation($this->getCorpOwnerIDToStation()));
        }

        $this->getBrokerFromLevel();
        $this->getAccountingFromLevel();
        $this->getBrokerToLevel();
        $this->getAccountingToLevel();
    }

    /**
     * Gets the corporation ID that owns the origin station
     * @return string
     */
    public function getCorpOwnerIDFromStation(): string
    {
        $this->db->select('corporation_eve_idcorporation');
        $this->db->where('eve_idstation', $this->stationFromID);
        $query = $this->db->get('station');

        return $result = $query->row()->corporation_eve_idcorporation;
    }

    /**
     * Gets the corporation ID that owns the destination station
     * @return string
     */
    public function getCorpOwnerIDToStation(): string
    {
        $this->db->select('corporation_eve_idcorporation');
        $this->db->where('eve_idstation', $this->stationToID);
        $query = $this->db->get('station');

        return $result = $query->row()->corporation_eve_idcorporation;
    }

    /**
     * Gets the faction ID that owns the origin station
     * @param  [type] $corpOwnerIDFromStation
     * @return string
     */
    public function getFactionOwnerIDFromStation(string $corpOwnerIDFromStation): string
    {
        $this->db->select('faction_eve_idfaction');
        $this->db->where('eve_idcorporation', $corpOwnerIDFromStation);
        $query = $this->db->get('corporation');

        return $result = $query->row()->faction_eve_idfaction;
    }

    /**
     * Gets the faction ID that owns the origin station
     * @param  [type] $corpOwnerIDFromStation
     * @return string
     */
    public function getFactionOwnerIDToStation(string $corpOwnerIDToStation): string
    {
        $this->db->select('faction_eve_idfaction');
        $this->db->where('eve_idcorporation', $corpOwnerIDToStation);
        $query = $this->db->get('corporation');

        return $result = $query->row()->faction_eve_idfaction;
    }

    /**
     * Gets the corp standing value for the origin station and character
     * @param  string $corpOwnerIDFromStation
     * @return [float]
     */
    public function getFromCorpStanding(string $corpOwnerIDFromStation): float
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_from);
        $this->db->where('corporation_eve_idcorporation', $corpOwnerIDFromStation);
        $query = $this->db->get('standings_corporation');

        if ($query->num_rows() == 0) {
            return (float) $this->fromCorpStandingValue = 0;
        } else {
            return (float) $this->fromCorpStandingValue = $query->row()->value;
        }
    }

    /**
     * Gets the corp standing value for the destination station and character
     * @param  string $corpOwnerIDToStation
     * @return [float]
     */
    public function getToCorpStanding(string $corpOwnerIDToStation): float
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_to);
        $this->db->where('corporation_eve_idcorporation', $corpOwnerIDToStation);
        $query = $this->db->get('standings_corporation');

        if ($query->num_rows() == 0) {
            return (float) $this->toCorpStandingValue = 0;
        } else {
            return (float) $this->toCorpStandingValue = $query->row()->value;
        }
    }

    /**
     * Gets the faction standing value for the origin station and character
     * @param  string $factionOwnerIDFromStation
     * @return [float]
     */
    public function getFromFactionStanding(string $factionOwnerIDFromStation): float
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_from);
        $this->db->where('faction_eve_idfaction', $factionOwnerIDFromStation);
        $query = $this->db->get('standings_faction');

        if ($query->num_rows() == 0) {
            return (float) $this->fromFactionStandingValue = 0;
        } else {
            return (float) $this->fromFactionStandingValue = $query->row()->value;
        }
    }

    /**
     * Gets the faction standing value for the destination station and character
     * @param  string $factionOwnerIDToStation
     * @return [float]
     */
    public function getToFactionStanding(string $factionOwnerIDToStation): float
    {
        $this->db->select('value');
        $this->db->where('characters_eve_idcharacters', $this->character_to);
        $this->db->where('faction_eve_idfaction', $factionOwnerIDToStation);
        $query = $this->db->get('standings_faction');

        if ($query->num_rows() == 0) {
            return (float) $this->toFactionStandingValue = 0;
        } else {
            return (float) $this->toFactionStandingValue = $query->row()->value;
        }
    }

    /**
     * Get the broker relations skill level from the origin character
     * @return int
     */
    public function getBrokerFromLevel(): int
    {
        $this->db->select('broker_relations');
        $this->db->where('eve_idcharacter', $this->character_from);
        $query = $this->db->get('characters');

        return (int) $this->level_broker_from = $query->row()->broker_relations;
    }

    /**
     * Get the broker relations skill level from the destination character
     * @return int
     */
    public function getBrokerToLevel(): int
    {
        $this->db->select('broker_relations');
        $this->db->where('eve_idcharacter', $this->character_to);
        $query = $this->db->get('characters');

        return (int) $this->level_broker_to = $query->row()->broker_relations;
    }

    /**
     * Get the accounting level for the origin character
     * @return int
     */
    public function getAccountingFromLevel(): int
    {
        $this->db->select('accounting');
        $this->db->where('eve_idcharacter', $this->character_from);
        $query = $this->db->get('characters');

        return (int) $this->level_acc_from = $query->row()->accounting;
    }

    /**
     * Get the accounting level for the destination character
     * @return int
     */
    public function getAccountingToLevel(): int
    {
        $this->db->select('accounting');
        $this->db->where('eve_idcharacter', $this->character_to);
        $query = $this->db->get('characters');

        return (int) $this->level_acc_to = $query->row()->accounting;
    }

    /**
     * Calculates the origin broker fee
     * @return [float]
     */
    public function calculateBrokerFrom(): float
    {
        if ($this->transFrom == 'buy') {
            $fromCitadelTax = $this->getCitadelTax($this->stationFromID);
            if ($this->stationFromID > 1000000000000 && $fromCitadelTax) {
                return
                $this->brokerFeeFrom = $fromCitadelTax;
            } else {
                return
                $this->brokerFeeFrom = 1 + ((3 - (0.1 * (float) $this->level_broker_from + 0.03 *
                    (float) $this->fromFactionStandingValue + 0.02 * (float) $this->fromCorpStandingValue)) / 100);
            }
        } else {
            return 1;
        }
    }

    /**
     * Calculates the destination broker fee
     * @return [float]
     */
    public function calculateBrokerTo(): float
    {
        if ($this->transTo == 'sell') {
            $toCitadelTax = $this->getCitadelTax($this->stationToID);
            if ($this->stationToID > 1000000000000 && $toCitadelTax) {
                return (float) $this->brokerFeeFrom = $toCitadelTax;
            } else {
                return
                $this->brokerFeeTo = 1 - ((3 - (0.1 * (float) $this->level_broker_to + 0.03 *
                    (float) $this->toFactionStandingValue + 0.02 * (float) $this->toCorpStandingValue)) / 100);
            }
        } else {
            return 1;
        }
    }

    /**
     * Calculates the origin transaction tax (alwyays zero)
     * @return [float]
     */
    public function calculateTaxFrom(): float
    {
        return $this->transTaxFrom;
    }

    /**
     * Calculates the destination transaction tax
     * @return [float]
     */
    public function calculateTaxTo(): float
    {
        return (float) $this->transTaxTo = 1 - ((2 * (1 - (0.1 * $this->level_acc_to))) / 100);
    }

    /**
     * Returns the fixed tax associated with a citadel
     * @param  string $stationID
     * @return float
     */
    private function getCitadelTax(string $stationID): ? float
    {
        if ($this->ignoreCitadelTax) {
            return 0;
        }

        $this->db->select('value');
        $this->db->where('character_eve_idcharacter', $this->character_from);
        $this->db->where('station_eve_idstation', $stationID);
        $query = $this->db->get('citadel_tax');

        if ($query->num_rows != 0) {
            return (float) $result = $query->row();
        } else {
            return false;
        }
    }
}
