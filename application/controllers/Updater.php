<?php defined('BASEPATH') or exit('No direct script access allowed');
use Pheal\Core\Config;
use Pheal\Pheal;

Config::getInstance()->cache  = new \Pheal\Cache\FileStorage(FILESTORAGE);
Config::getInstance()->access = new \Pheal\Access\StaticCheck();

final class Updater extends CI_Controller
{
    private $idUser;
    private $username;
    private $characters;

    private $idCharacter;
    private $characterBalance;
    private $characterNetworth;
    private $characterOrders;
    private $characterEscrow;
    private $apikey;
    private $characterBrokerLevel;
    private $characterAccountingLevel;
    private $characterOrdersSum;

    private $newCharacterTransactions;
    private $newCharacterContracts;
    private $newCharacterOrders;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Aggr_model', 'aggr');
        $this->load->model('Api_keys_model', 'keys');
        $this->load->model('Characters_model', 'chars');
        $this->load->model('Transactions_model', 'transactions');
        $this->load->model('Contracts_model', 'contracts');
        $this->load->model('Market_orders_model', 'orders');
        $this->load->model('Assets_model', 'assets');
        $this->load->model('New_info_model', 'new_info');

        $this->load->helper('validation_helper');
        $this->load->helper('updater_helper');
        $this->load->helper('msg_helper');
        $this->load->helper('log_helper');
        $this->load->helper('profit_calculator_helper');

        $this->load->library('twig');
    }

    public function index()
    {
        $updater        = new UpdaterHelper();
        $this->username = $this->etmsession->get('username');
        $this->idUser   = $this->etmsession->get('iduser');
        if (empty($this->idUser)) {
            redirect('login');
            return;
        }

        // initialize
        try {
            $this->characters = $this->aggr->getAll(array('user_iduser' => $this->idUser));
        } catch (Throwable $e) {
            log_message('error', $this->username . ' was unable to update : ' . $e->getMessage());
            buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
            $updater->release($this->idUser);
            $this->displayResultTable();
            return;
        }

        // check if API server is up
        if (!$updater->testEndpoint()) {
            log_message('error', $this->username . ' was unable to update because the API server is down');
            buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
            $this->removeDirectory(FILESTORAGE . 'public/public/server');
            $updater->release($this->idUser);
            $this->displayResultTable($username);
            return;
        }

        // check if user is already updating
        if ($updater->isLocked($this->idUser)) {
            log_message('error', $this->username . ' is already updating!');
            buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
            $this->displayResultTable($this->idUser);
            return;
        }

        // check if user has any keys
        $keys = $this->aggr->getAll(array('user_iduser' => $this->idUser));
        if (count($keys) == 0) {
            log_message('error', $this->username . ' has no keys');
            $updater->release($this->idUser);
            // no keys, so prompt for new one
            $this->askForKey();
            return;
        }

        // validate existing keys
        $keysStatus = $this->processAPIKeys($keys);
        if (!$keysStatus) {
            log_message('error', $this->username . ' was unable to connect to verify key status');
            $updater->release($this->idUser);
            // unable to connect to verify keys
            buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
            $this->displayResultTable($this->idUser);
            return;
        }

        foreach ($keysStatus as $key => $val) {
            $invalidKeys = [];
            if ($val < 1) {
                array_push($invalidKeys, $key);
            }
            if (count($invalidKeys) > 0) {
                $updater->release($this->idUser);
                log_message('error', 'Invalid Keys detected for ' . $this->username);
                // invalid key
                buildMessage('error', Msg::OFFLINE_MODE_NOTICE_KEY . ' ' . implode($invalidKeys, ','));
                $this->displayResultTable($this->idUser);
                return;
            }
        }

        // begin update
        $updater->lock($this->idUser);
        try {
            $resultIterate = $this->iterateAccountCharacters();
            if (!$resultIterate) {
                $updater->release($this->idUser);
                log_message('error', $this->username . ' iterate failed');
                // transaction failed for some reason
                // forward user to offline mode
                buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
                $this->displayResultTable($this->idUser);
                return;
            }

            // profits and history
            $this->db->trans_start();
            $profitCalc = new ProfitCalculator($this->idUser);
            $profitCalc->beginProfitCalculation();
            $this->updateTotals();
            $this->db->trans_complete();

            // something went wrong while calculating profits, abort
            if ($this->db->trans_status() === false) {
                $updater->release($username);
                buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
                $this->displayResultTable($this->idUser);
                return;
            }

            // successfully updated
            buildMessage('success', Msg::UPDATE_SUCCESS);
            $tableData = $this->displayResultTable($this->idUser);
            $updater->release($this->idUser);

            $data['cl']        = Log::getChangeLog();
            $data['cl_recent'] = Log::getChangeLog(true);
            $data['table']     = array($tableData);
            $data['view']      = "login/select_v";
            $data['SESSION']   = $_SESSION; // not part of MY_Controller
            $data['no_header'] = 1;

            Log::addEntry('update', $this->idUser);
            Log::addLogin($this->idUser);

            // finally, load the next page
            $this->twig->display('main/_template_v', $data);
        } catch (Throwable $e) {
            // if an exception happens during update (this is a bug on Eve's API)
            log_message('error', $this->username . ' had an error iterating characters: ' . $e->getMessage());
            // cache is now corrupted for 24 hours, remove cached data
            $problematicKeys = $this->aggr->getAll(array('user_iduser' => $this->idUser));
            Log::addEntry('clear', $this->idUser);

            // todo: check error code?
            foreach ($problematicKeys as $row) {
                log_message('error', $this->username . ' is deleting cache folder');
                buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
                $key = $row->apikey;
                $dir = FILESTORAGE . $key;
                $updater->removeDirectory($dir);
                $updater->release($this->idUser);
                $this->displayResultTable($this->idUser);
            }
        }

    }

    private function iterateAccountCharacters()
    {
        foreach ($this->characters as $character) {
            $this->idCharacter = $character->id_character;
            $characterData     = $this->chars->getOne(array('eve_idcharacter' => $this->idCharacter));

            $this->characterBalance  = $characterData->balance;
            $this->characterEscrow   = $characterData->escrow;
            $this->characterNetworth = $characterData->networth;
            $this->characterOrders   = $characterData->total_sell;
            $this->apikey            = $characterData->api_apikey;
            $this->vcode             = $this->keys->getOne(array('apikey' => $characterData->api_apikey))->vcode;

            // get character data
            $this->getWalletBalance();
            $this->getBrokerRelationsLevel();
            $this->getAccountingLevel();
            $this->getCorpStandings();
            $this->getFactionStandings();
            $this->getTransactions();
            $this->getContracts();
            $this->getMarketOrders();
            $this->getAssets();
            $this->setNewInfo();
            $this->updateCharacterInfo();
        }

        return true;
    }

    /**
     * Begins checking each key's validity (e.g expired or wrong permissions)
     * @param  array  $user_keys
     * @param  string $username
     * @return bool            validation result
     */
    private function processAPIKeys(array $userKeys)
    {
        $result = [];
        foreach ($userKeys as $apis) {
            $apikey = (int) $apis->apikey;
            $vcode  = $apis->vcode;
            $idChar = $apis->id_character;

            $pheal = new Pheal($apikey, $vcode, "account");
            try {
                $response = $pheal->APIKeyInfo();
            } catch (Throwable $e) {
                log_message('error', $this->username . ' had an error processing api keys: ' . $e->getMessage());
                return false;
            }

            $result[$apikey] = $this->validateAPIKey($apikey, $vcode, $idChar);
        }

        return $result;
    }

    /**
     * Performs validation checks for each key's result
     * @param  int    $apikey
     * @param  string $vcode
     * @param  string $char
     * @return [bool|int]      validation result
     */
    public function validateAPIKey(int $apikey, string $vcode, string $char)
    {
        try {
            $phealAPI   = new Pheal($apikey, $vcode, "account");
            $response   = $phealAPI->APIKeyInfo();
            $accessMask = $response->key->accessMask;
            $expiry     = $response->key->expires;
            $apichars   = array();
            foreach ($response->key->characters as $row) {
                $char_api = $row->characterID;
                array_push($apichars, $char_api);
            }
        } catch (Throwable $e) {
            log_message('error', $this->username . ' had an error validating api keys: ' . $e->getMessage());
            return false;
        }
        if ($accessMask == "" && $response) {
            return -4; //api key does not exist
        } else if ($accessMask != MASK_PERSONAL_KEY && $accessMask != MASK_FULL_KEY && $response) {
            return -3; //api key has invalid permissions
        } else if (!in_array($char, $apichars) && $response) {
            return -2; //character does not belong to API key
        } else if (!isset($expiry) && $response) {
            return -1; //key has expired
        } else {
            return 1; //everything is ok
        }
        return true;
    }

    private function getWalletBalance(): void
    {
        $this->characterBalance = 0;
        $pheal                  = new Pheal($this->apikey, $this->vcode, "char");
        $response               = $pheal->AccountBalance(array("characterID" => $this->idCharacter));
        foreach ($response->accounts as $row) {
            if ($row['accountKey'] == 1000) {
                // master wallet
                $balance                = $row->balance;
                $this->characterBalance = $balance;
            }
        }
    }

    private function getBrokerRelationsLevel(): void
    {
        $this->characterBrokerLevel = '0';
        $pheal                      = new Pheal($this->apikey, $this->vcode, "char");
        $response                   = $pheal->CharacterSheet(array("characterID" => $this->idCharacter));
        foreach ($response->skills as $skills) {
            if (($skills->typeID) == 3446) {
                $this->characterBrokerLevel = $skills->level;
            }
        }
    }

    private function getAccountingLevel(): void
    {
        $this->characterAccountingLevel = '0';
        $pheal                          = new Pheal($this->apikey, $this->vcode, "char");
        $response                       = $pheal->CharacterSheet(array("characterID" => $this->idCharacter));
        foreach ($response->skills as $skills) {
            if (($skills->typeID) == 16622) {
                $this->characterAccountingLevel = $skills->level;
            }
        }
    }

    private function getCorpStandings(): void
    {
        $pheal             = new Pheal($this->apikey, $this->vcode, "char");
        $result            = $pheal->Standings(array("characterID" => $this->idCharacter));
        $corpStandingsData = array();

        foreach ($result->characterNPCStandings->NPCCorporations as $corpStandings) {
            $data = array("idstandings_corporation" => "null",
                "characters_eve_idcharacters"           => $this->idCharacter,
                "corporation_eve_idcorporation"         => $corpStandings->fromID,
                "value"                                 => $corpStandings->standing);
            array_push($corpStandingsData, $data);
        }

        if (count($corpStandingsData) > 0) {
            // mass insert
            batch("standings_corporation",
                array('idstandings_corporation', 'characters_eve_idcharacters', 'corporation_eve_idcorporation', 'value'), $corpStandingsData);
        }
    }

    private function getFactionStandings(): void
    {
        $pheal                = new Pheal($this->apikey, $this->vcode, "char");
        $result               = $pheal->Standings(array("characterID" => $this->idCharacter));
        $factionStandingsData = array();

        foreach ($result->characterNPCStandings->factions as $factionStandings) {
            $data = array("idstandings_faction" => "null",
                "characters_eve_idcharacters"       => $this->idCharacter,
                "faction_eve_idfaction"             => $factionStandings->fromID,
                "value"                             => $factionStandings->standing);
            array_push($factionStandingsData, $data);
        }

        if (count($factionStandingsData) > 0) {
            batch("standings_faction",
                array('idstandings_faction', 'characters_eve_idcharacters', 'faction_eve_idfaction', 'value'), $factionStandingsData);
        }
    }

    private function getTransactions($refID = false): void
    {
        $this->newCharacterTransactions = 0;
        $pheal                          = new Pheal($this->apikey, $this->vcode, "char");
        $response                       = $pheal->WalletTransactions(array("characterID" => $this->idCharacter));
        if ($refID != false) {
            $response = $pheal->WalletTransactions(array("fromID" => $refID));
        }

        $latestTransaction = $this->transactions->getLatestTransaction($this->idCharacter)->val;
        if (!$latestTransaction) {
            $latestTransaction = 0;
        }

        // only update transactions not in db already
        $transactions = array();
        foreach ($response->transactions as $row) {
            if ($row->transactionID > $latestTransaction) {
                $data = array("idbuy"       => "null",
                    "time"                      => $this->db->escape($row->transactionDateTime),
                    "quantity"                  => $this->db->escape($row->quantity),
                    "price_unit"                => $this->db->escape($row->price),
                    "price_total"               => $this->db->escape($row->price * $row->quantity),
                    "transaction_type"          => $this->db->escape($row->transactionType),
                    "character_eve_idcharacter" => $this->db->escape($this->idCharacter),
                    "station_eve_idstation"     => $this->db->escape($row->stationID),
                    "item_eve_iditem"           => $this->db->escape($row->typeID),
                    "transkey"                  => $this->db->escape($row->transactionID),
                    "client"                    => $this->db->escape($row->clientName),
                    "remaining"                 => $this->db->escape($row->quantity));
                array_push($transactions, $data);
            }
        }

        // insert all transactions at once
        if (!empty($transactions)) {
            $this->transactions->batchInsert($transactions);
            $this->newCharacterTransactions = count($transactions);
            if (count($transactions) == 2560) {
                //check if we exceed the max transactions per request
                $refID = end($transactions['transkey']);
                $this->newCharacterTransactions += 2560;
                $this->getTransactions($refID); //pass the last transaction as request again
            }
        } else {
            $this->newCharacterTransactions = 0;
        }
    }

    private function getContracts(): void
    {
        $this->newCharacterContracts = 0;
        $contracts                   = [];
        $oldContracts                = [];
        $newContracts                = [];

        $pheal    = new Pheal($this->apikey, $this->vcode, "char");
        $response = $pheal->Contracts(array("characterID" => $this->idCharacter));
        foreach ($response->contractList as $row) {
            $data = array("eve_idcontracts" => $this->db->escape($row->contractID),
                "issuer_id"                     => $this->db->escape($row->issuerID),
                "acceptor_id"                   => $this->db->escape($row->acceptorID),
                "status"                        => $this->db->escape($row->status),
                "availability"                  => $this->db->escape($row->availability),
                "type"                          => $this->db->escape($row->type),
                "creation_date"                 => $this->db->escape($row->dateIssued),
                "expiration_date"               => $this->db->escape($row->dateExpired),
                "completed_date"                => $this->db->escape($row->dateCompleted),
                "price"                         => $this->db->escape($row->price),
                "reward"                        => $this->db->escape($row->reward),
                "colateral"                     => $this->db->escape($row->collateral),
                "fromStation_eve_idstation"     => $this->db->escape($row->startStationID),
                "toStation_eve_idstation"       => $this->db->escape($row->endStationID),
                "characters_eve_idcharacters"   => $this->db->escape($this->idCharacter),
            );
            array_push($contracts, $data);
            array_push($newContracts, $row->contractID);
        }

        // count how many new contracts were inserted
        $old = $this->contracts->getAll(array('characters_eve_idcharacters' => $this->idCharacter));
        foreach ($old as $row) {
            array_push($oldContracts, $row->eve_idcontracts);
        }

        $duplicates = 0;
        foreach ($newContracts as $new) {
            foreach ($oldContracts as $old) {
                if ($new == $old) {
                    $duplicates++;
                }
            }
        }

        $this->newCharacterContracts = count($newContracts) - $duplicates;
        if (!empty($contracts)) {
            $this->contracts->batchInsert($contracts);
        } else {
            $this->newCharacterContracts = 0;
        }
    }

    private function getMarketOrders(): void
    {
        $this->newCharacterOrders = 0;
        $this->characterEscrow    = 0;
        $marketOrders             = [];
        $newOrders                = [];
        $oldOrders                = [];
        $orderState               = "-1";

        $pheal    = new Pheal($this->apikey, $this->vcode, "char");
        $response = $pheal->MarketOrders(array("characterID" => $this->idCharacter));
        foreach ($response->orders as $row) {
            //Eve API reports order states with these codes
            switch ($row->orderState) {
                case '0':
                    $orderState = "open";
                    $this->characterEscrow += $row->escrow;
                    break;
                case '1':
                    $orderState = "closed";
                    break;
                case '2':
                    $orderState = "expired";
                    break;
                case '3':
                    $orderState = "canceled";
                    break;
                case '4':
                    $orderState = "pending";
                    break;
                case '5':
                    $orderState = "character_deleted";
                    break;
            }

            $data = array("idorders"      => "null",
                "eve_item_iditem"             => $this->db->escape($row->typeID),
                "station_eve_idstation"       => $this->db->escape($row->stationID),
                "characters_eve_idcharacters" => $this->db->escape($this->idCharacter),
                "price"                       => $this->db->escape($row->price),
                "volume_remaining"            => $this->db->escape($row->volRemaining),
                "duration"                    => $this->db->escape($row->duration),
                "escrow"                      => $this->db->escape($row->escrow),
                "type"                        => $this->db->escape($row->bid ? "buy" : "sell"),
                "order_state"                 => $this->db->escape($orderState),
                "order_range"                 => $this->db->escape($row->range),
                "date"                        => $this->db->escape($row->issued),
                "transkey"                    => $this->db->escape($row->orderID),
            );
            array_push($marketOrders, $data);
            if ($orderState == 'open') {
                array_push($newOrders, $row->orderID);
            }
        }

        $old = $this->orders->getAll(array('characters_eve_idcharacters' => $this->idCharacter));
        foreach ($old as $row) {
            array_push($oldOrders, $row->transkey);
        }

        $duplicates = 0;
        foreach ($newOrders as $new) {
            foreach ($oldOrders as $old) {
                if ($new == $old) {
                    $duplicates++;
                }
            }
        }

        $this->characterNewOrders = count($newOrders) - $duplicates;
        $this->orders->delete(array('characters_eve_idcharacters' => $this->idCharacter));

        if (!empty($marketOrders)) {
            $this->orders->batchInsert($marketOrders);
        } else {
            $this->newCharacterOrders = 0;
        }

        $this->characterOrdersSum = $this->orders->getSum($this->idCharacter)->grand_total;
    }

    private function getAssets(): void
    {
        $pheal       = new Pheal($this->apikey, $this->vcode, "char");
        $response    = $pheal->AssetList(array("characterID" => $this->idCharacter));
        $i           = 0;
        $index_asset = 0;

        foreach ($response->assets as $assets) {
            $typeID_asset   = $assets['typeID'];
            $locationID     = $assets['locationID'];
            $quantity_asset = $assets['quantity'];
            $i++;
            $assetList[$i] = array("idassets" => "NULL",
                "characters_eve_idcharacters"     => $this->idCharacter,
                "item_eve_iditem"                 => $typeID_asset,
                "quantity"                        => $quantity_asset,
                "locationID"                      => $locationID);
            if (isset($assets->contents)) {
                foreach ($assets->contents as $assets_inside) {
                    $typeID_sub   = $assets_inside['typeID'];
                    $quantity_sub = $assets_inside['quantity'];
                    $i++;
                    $assetList[$i] = array("idassets" => "NULL",
                        "characters_eve_idcharacters"     => $this->idCharacter,
                        "item_eve_iditem"                 => $typeID_sub,
                        "quantity"                        => $quantity_sub,
                        "locationID"                      => $locationID);
                    if (isset($assets_inside->contents)) {
                        foreach ($assets_inside->contents as $assets_inside_2) {
                            $typeID_sub_sub   = $assets_inside_2['typeID'];
                            $quantity_sub_sub = $assets_inside_2['quantity'];
                            $i++;
                            $assetList[$i] = array("idassets" => "NULL",
                                "characters_eve_idcharacters"     => $this->idCharacter,
                                "item_eve_iditem"                 => $typeID_sub_sub,
                                "quantity"                        => $quantity_sub_sub,
                                "locationID"                      => $locationID);
                            if (isset($assets_inside_2->contents)) {
                                foreach ($assets_inside_2->contents as $assets_inside_3) {
                                    $typeID_sub_sub_sub   = $assets_inside_3['typeID'];
                                    $quantity_sub_sub_sub = $assets_inside_3['quantity'];
                                    $i++;
                                    $assetList[$i] = array("idassets" => "NULL",
                                        "characters_eve_idcharacters"     => $this->idCharacter,
                                        "item_eve_iditem"                 => $typeID_sub_sub_sub,
                                        "quantity"                        => $quantity_sub_sub_sub,
                                        "locationID"                      => $locationID);
                                }
                            }
                        }
                    }
                }
            }
        }

        // first, delete existing assets
        $this->assets->delete(array('characters_eve_idcharacters' => $this->idCharacter));
        if (!empty($assetList)) {
            $this->assets->batchInsert($assetList);
        }

        $this->characterNetworth = $this->assets->getNetworth($this->idCharacter)->grand_total;
    }

    public function setNewInfo(): void
    {
        $data = array(
            "contracts"                   => $this->newCharacterContracts,
            "orders"                      => $this->newCharacterOrders,
            "transactions"                => $this->newCharacterTransactions,
            "characters_eve_idcharacters" => $this->idCharacter,
        );

        $this->db->replace('new_info', $data);
    }

    private function updateCharacterInfo()
    {
        $data = array(
            "balance"          => $this->characterBalance,
            "escrow"           => $this->characterEscrow,
            "total_sell"       => $this->characterOrdersSum,
            "broker_relations" => $this->characterBrokerLevel,
            "accounting"       => $this->characterAccountingLevel,
            "networth"         => $this->characterNetworth,
        );

        $this->chars->update($this->idCharacter, $data);
    }

    /**
     * Update each character's total profit, sales, etc for this day
     * @param  bool|boolean $global global update flag
     * @param  string|null  $user   username
     * @return array              result list, only for non global update
     */
    private function updateTotals($global = false)
    {
        $max_days      = 0;
        $characterList = $this->aggr->getAll(array('user_iduser' => $this->idUser));

        if ($global) {
            $max_days = 2;
        }

        foreach ($characterList as $row) {
            $dt = new DateTime();
            $tz = new DateTimeZone('Europe/Lisbon');
            $dt->setTimezone($tz);

            for ($i = 0; $i <= $max_days; $i++) {
                if ($i == 0) {
                    $date = $dt->format('Y-m-d');
                } else {
                    $date = date_sub($dt, date_interval_create_from_date_string('1 days'))->format('Y-m-d');
                }

                // sum of sales
                $optionsBuy = [
                    'transaction_type'          => 'Sell',
                    'character_eve_idcharacter' => $this->idCharacter,
                    'date'                      => $date,
                    'sum'                       => 1,
                ];
                $salesSumValue = $this->transactions->getOne($optionsBuy)->sum;

                // sum of purchases
                $optionsSell = [
                    'transaction_type'          => 'Buy',
                    'character_eve_idcharacter' => $this->idCharacter,
                    'date'                      => $date,
                    'sum'                       => 1,
                ];
                $purchasesSumValue = $this->transactions->getOne($optionsSell)->sum;

                // sum of profits
                $optionsProfit = [
                    'sum'                             => 1,
                    'date'                            => $date,
                    'characters_eve_idcharacters_OUT' => $this->idCharacter,
                ];
                $profitsSumValue = $this->profits->getOne($optionsProfit)->profit;

                //profit margin
                $marginValue = $this->profits->getProfitMargin($this->idCharacter, $date)->margin;

                $data = array(
                    "characters_eve_idcharacters" => $this->idCharacter,
                    "date"                        => $date,
                    "total_buy"                   => $purchasesSumValue,
                    "total_sell"                  => $salesSumValue,
                    "total_profit"                => $purchasesSumValue,
                    "margin"                      => $marginValue,
                );

                $this->db->replace('history', $data);
            }
        }

        if (!$global) {
            return $characterList;
        }
    }

    private function displayResultTable()
    {
        $balanceTotal  = 0;
        $networthTotal = 0;
        $escrowTotal   = 0;
        $sellTotal     = 0;
        $grandTotal    = 0;

        $data = array(
            "character"   => array(),
            "total"       => array(),
            "grand_total" => array(),
        );

        foreach ($this->characters as $character) {
            $characterData = $this->chars->getOne(array('eve_idcharacter' => $character->id_character));
            $id       = $characterData->eve_idcharacter;
            $name     = $characterData->name;
            $balance  = $characterData->balance;
            $networth = $characterData->networth;
            $escrow   = $characterData->escrow;
            $sell     = $characterData->total_sell;

            $personalData = array(
                "id"       => $id,
                "name"     => $name,
                "balance"  => $balance,
                "networth" => $networth,
                "escrow"   => $escrow,
                "sell"     => $sell,
            );

            $totalData = array(
                "balance_total"  => $balanceTotal += $balance,
                "networth_total" => $networthTotal += $networth,
                "escrow_total"   => $escrowTotal += $escrow,
                "sell_total"     => $sellTotal += $sell,
            );

            array_push($data['character'], $personalData);
            $data['total'] = $totalData;
        }

        $grandTotal = $balanceTotal + $networthTotal + $escrowTotal + $sellTotal;
        $data['grand_total'] = $grandTotal;

        return $data;
    }
}
