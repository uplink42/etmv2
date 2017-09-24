<?php defined('BASEPATH') or exit('No direct script access allowed');

class TaxCalculator
{
    public $ci;

    public $stationFromID;
    public $stationToID;
    public $characterFromID;
    public $characterToID;
    public $transFrom;
    public $transTo;
    public $brokerFeeFrom;
    public $brokerFeeTo;

    private $fromCorpStandingValue;
    private $toCorpStandingValue;
    private $fromFactionStandingValue;
    private $toFactionStandingValue;
    private $brokerLevelFrom;
    private $accountingLevelFrom;
    private $brokerLevelTo;
    private $accountingLevelTo;
    private $ignoreCitadelTax;
    private $ignoreOutpostTax;
    private $ignoreStationTax;
    private $ignoreAllBuyTax;
    private $ignoreAllSellTax;

    public function __construct(string $stationFromID, string $stationToID, string $characterFromID, string $characterToID, array $settings)
    {
        $this->ci =&get_instance();
        $this->ci->load->model('Corporation_model', 'corporation');
        $this->ci->load->model('Faction_model', 'faction');
        $this->ci->load->model('Station_model', 'station');
        $this->ci->load->model('Standings_corporation_model', 'standings_corporation');
        $this->ci->load->model('Standings_faction_model', 'standings_faction');
        $this->ci->load->model('Characters_model', 'chars');
        $this->ci->load->model('Citadel_tax_model', 'citadel_tax');

        $this->stationFromID    = $stationFromID;
        $this->stationToID      = $stationToID;
        $this->transFrom        = $settings['buy_behaviour'];
        $this->transTo          = $settings['sell_behaviour'];
        $this->characterFromID  = $characterFromID;
        $this->characterToID    = $characterToID;
        $this->ignoreCitadelTax = $settings['citadel_tax_ignore'];
        $this->ignoreOutpostTax = $settings['outpost_tax_ignore'];
        $this->ignoreStationTax = $settings['station_tax_ignore'];
        $this->ignoreBuyTax     = $settings['buy_tax_ignore'];
        $this->ignoreSellTax    = $settings['sell_tax_ignore'];

        // non citadels use standings
        if ($this->stationFromID < 1000000000000) {
            $corpIDFrom = $this->getCorpId('from');
            $this->getCorpStanding('from', $corpIDFrom);
            $this->getFactionStanding('from', $this->getFactionID($corpIDFrom));
        }
        if ($this->stationToID < 1000000000000) {
            $corpIDTo = $this->getCorpId('to');
            $this->getCorpStanding('to', $corpIDTo);
            $this->getFactionStanding('to', $this->getFactionID($corpIDTo));
        }
        $this->getBrokerLevel('from');
        $this->getBrokerLevel('to');
        $this->getAccountingLevel('from');
        $this->getAccountingLevel('to');
    }

    private function getCorpId(string $type): string
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

        $station = $this->ci->station->getOne(array('eve_idstation' => $stationID));
        if ($station) {
            return $station->corporation_eve_idcorporation;
        } else {
            return 1;
        }
    }

    private function getFactionID(string $corpID): string
    {
        $corporation = $this->ci->corporation->getOne(array('eve_idcorporation' => $corpID));
        if ($corporation) {
            return $corporation->faction_eve_idfaction;
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

        $standings = $this->ci->standings_corporation->getOne(
            array('characters_eve_idcharacters' => $characterID,
                  'corporation_eve_idcorporation' => $corpID)
        );

        switch ($type) {
            case 'from':
                if (!$standings) {
                    $this->fromCorpStandingValue = 0;
                } else {
                    $this->fromCorpStandingValue = $standings->value;
                }
                break;
            case 'to':
                if (!$standings) {
                    $this->toCorpStandingValue = 0;
                } else {
                    $this->toCorpStandingValue = $standings->value;
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

        $standings = $this->ci->standings_faction->getOne(
            array('characters_eve_idcharacters' => $characterID,
                  'faction_eve_idfaction' => $factionID)
        );

        switch ($type) {
            case 'from':
                if (!$standings == 0) {
                    $this->fromFactionStandingValue = 0;
                } else {
                    $this->fromFactionStandingValue = $standings->value;
                }
                break;
            case 'to':
                if (!$standings == 0) {
                    $this->toFactionStandingValue = 0;
                } else {
                    $this->toFactionStandingValue = $standings->value;
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

        $brokerRelations = $this->ci->chars->getOne(array('eve_idcharacter' => $characterID))->broker_relations;
        switch ($type) {
            case 'from':
                $this->brokerLevelFrom = $brokerRelations;
                break;
            case 'to':
                $this->brokerLevelTo = $brokerRelations;
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
        
        $accounting = $this->ci->chars->getOne(array('eve_idcharacter' => $characterID))->accounting;
        switch ($type) {
            case 'from':
                $this->accountingLevelFrom = $accounting;
                break;
            case 'to':
                $this->accountingLevelTo = $accounting;
                break;
        }
    }

    public function calculateBroker(string $type): float
    {
        switch ($type) {
            case 'from':
                $characterID = $this->characterFromID;
                $stationID   = $this->stationFromID;
                if ($this->ignoreBuyTax) {
                    return 1;
                }
                break;
            case 'to':
                $characterID = $this->characterToID;
                $stationID   = $this->stationToID;
                if ($this->ignoreSellTax) {
                    return 1;
                }
                break;
        }

        if ($this->transFrom == 'buy' && $type === 'from' || $this->transTo == 'sell' && $type === 'to') {
            $station_type = $this->getStationType($stationID);

            switch ($station_type) {
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
                    if ($citadelTax) {
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

        $tax = $this->ci->citadel_tax->getOne(
            array('character_eve_idcharacter' => $characterID,
                  'station_eve_idstation' => $stationID,
            )
        );

        if (!$tax) {
            return $result = 1 + (float) $tax->value;
        } else {
            return false;
        }
    }

    private function getStationType($stationID): string
    {
        $type = 'citadel';
        if ($stationID < 1000000000000) {
            $station = $this->ci->station->getOne(array('eve_idstation' => $stationID));

            if ($station) {
                $owner = $station->corporation_eve_idcorporation;
                if ($owner == 1) {
                    $type = 'outpost';
                } else {
                    $type = 'station';
                }
            }
        }
        return $type;
    }
}
