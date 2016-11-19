<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Core\Config;
use Pheal\Pheal;

Config::getInstance()->cache  = new \Pheal\Cache\FileStorage(FILESTORAGE);
Config::getInstance()->access = new \Pheal\Access\StaticCheck();

class Updater_model extends CI_Model
{

    //account related
    private $user_id;
    private $username;
    private $account_characters = [];
    private $is_updating;

    //character specific
    private $character_id;
    private $character_name;
    private $character_balance;
    private $character_networth;
    private $character_orders;
    private $character_escrow;

    private $character_broker_level;
    private $character_accounting_level;
    private $character_corp_standings;
    private $character_faction_standings;
    private $character_new_transactions;
    private $character_new_contracts;
    private $character_new_orders;
    private $character_new_profits;

    public function init(string $username)
    {
        $this->username = $username;

        $this->db->select('iduser');
        $this->db->where('username', $username);
        $query         = $this->db->get('user');
        $user_id       = $query->result();
        $this->user_id = $user_id;

        /*$this->db->select('updating');
        $this->db->where('username', $username);
        $query = $this->db->get('user');
        $updating = $query->result();
        $this->update_state = $updating;*/

        $this->db->select('character_eve_idcharacter');
        $this->db->where('username', $username);
        $query                    = $this->db->get('v_user_characters');
        $this->account_characters = $query->result_array();
    }

    //retrurns a list of current database characters
    public function resultTable(string $username): array
    {
        $data = array(
            "character"   => array(),
            "total"       => array(),
            "grand_total" => array(),
        );

        $balance_total  = 0;
        $networth_total = 0;
        $escrow_total   = 0;
        $sell_total     = 0;
        $grand_total    = 0;

        $this->db->select('character_eve_idcharacter');
        $this->db->where('username', $username);
        $query                    = $this->db->get('v_user_characters');
        $this->account_characters = $query->result_array();

        foreach ($this->account_characters as $char) {
            $this->db->where('eve_idcharacter', $char['character_eve_idcharacter']);
            $get_data = $this->db->get('characters');

            $id       = $get_data->row()->eve_idcharacter;
            $name     = $get_data->row()->name;
            $balance  = $get_data->row()->balance;
            $networth = $get_data->row()->networth;
            $escrow   = $get_data->row()->escrow;
            $sell     = $get_data->row()->total_sell;

            $character_data = array(
                "id"       => $id,
                "name"     => $name,
                "balance"  => $balance,
                "networth" => $networth,
                "escrow"   => $escrow,
                "sell"     => $sell,
            );

            $total_data = array(
                "balance_total"  => $balance_total += $balance,
                "networth_total" => $networth_total += $networth,
                "escrow_total"   => $escrow_total += $escrow,
                "sell_total"     => $sell_total += $sell,
            );

            array_push($data['character'], $character_data);
            $data['total'] = $total_data;
        }
        $grand_total += $balance_total + $networth_total + $escrow_total + $sell_total;
        $data['grand_total'] = $grand_total;

        return $data;
    }

    public function getKeys(string $username)
    {
        $this->db->select('api.apikey, api.vcode, characters.eve_idcharacter');
        $this->db->from('api');
        $this->db->join('characters', 'characters.api_apikey = api.apikey');
        $this->db->join('aggr', 'aggr.character_eve_idcharacter = characters.eve_idcharacter');
        $this->db->join('user', 'aggr.user_iduser = user.iduser');
        $this->db->where('user.username', $username);
        $query = $this->db->get('');

        $user_keys = $query->result_array();
        return $user_keys;
    }


    //gets the assigned API keys for each character in the current account
    //and validates them accordingly, removing any characters with invalid permissions
    //returns a list of characters removed, otherwise returns false
    public function processAPIKeys(array $user_keys, string $username)
    {

        /*$query = $this->db->query("SELECT api.apikey, api.vcode, characters.eve_idcharacter
        FROM api
        JOIN characters on characters.api_apikey = api.apikey
        JOIN aggr on aggr.character_eve_idcharacter = characters.eve_idcharacter
        JOIN user on aggr.user_iduser = user.iduser
        WHERE user.username = '$username'");*/

        foreach ($user_keys as $apis) {
            $apikey = (int) $apis['apikey'];
            $vcode  = $apis['vcode'];

            $char_id = $apis['eve_idcharacter'];
            $pheal   = new Pheal($apikey, $vcode, "account");

            try {
                $response = $pheal->APIKeyInfo();
            } catch (\Pheal\Exceptions\PhealException $e) {
                //check if expired
                if ($e->getMessage() == 'Key has expired. Contact key owner for access renewal.') {
                    $this->checkCharacterKeys($apikey, $vcode, $char_id);
                } else {
                    //unknown error
                    return false;
                }

            }
            //proceed to check permissions and remove any invalid keys
            $this->checkCharacterKeys($apikey, $vcode, $char_id);

            //count user keys again (check if none left)
            if (count($this->getKeys($username)) != 0) {
                return true;
            } 

            return false;
            
            //Important! Must ensure the reply from the server is not empty
            /*if (!isset($response) || $response == "") {
                return false;
            } else {*/
            //}
        }
        //return $removed_characters;
    }

    public function checkCharacterKeys($apikey, $vcode, $char_id) 
    {
        //check if permissions are correct
        //if not, we remove any invalid keys and characters
        $result = $this->validateAPIKey($apikey, $vcode, $char_id);
        if ($result < 1 && $result != false) {
            $this->db->select('name');
            $this->db->where('eve_idcharacter', $char_id);
            $query          = $this->db->get('characters');
            $character_name = $query->row()->name;

            $this->db->where('character_eve_idcharacter', $char_id);
            $query = $this->db->get('aggr');
            $res = $query->row()->user_iduser;

            //Invalid access mask or API key not found. Delete API from account
            //Note that none of the characters data is actually removed from the account,
            //but only the user association
            $data = array(
                "user_iduser"               => $res,
                "character_eve_idcharacter" => $char_id,
            );

            $this->db->delete('aggr', $data);
            log_message('error', 'deleted '. $char_id .' from ' . $res);
        }
    }

    //checks if the current API key is valid and has all necessary permissions for the current character
    public function validateAPIKey(int $apikey, string $vcode, string $char)
    {
        try {
            $phealAPI = new Pheal($apikey, $vcode, "account");
            $response = $phealAPI->APIKeyInfo();

            $accessMask = $response->key->accessMask;
            $expiry     = $response->key->expires;

            $apichars = array();

            foreach ($response->key->characters as $row) {
                $char_api = $row->characterID;
                array_push($apichars, $char_api);
            }
        } catch (\Pheal\Exceptions\PhealException $e) {
            //communication error, abort
            return false;
        }

        if ($accessMask == "" && $response) {
            return -4; //api key does not exist
        } else if ($accessMask != '82317323' && $accessMask != '1073741823' && $response) {
            return -3; //api key has invalid permissions
        } else if (!in_array($char, $apichars) && $response) {
            return -2; //character does not belong to API key
        } else if (!isset($expiry) && $response) {
            return -1; //key has expired
        } else {
            return 1; //everything is ok
        }
    }

    //after removing invalid keys and characters, iterate trough existing characters
    //and begin the update procedure
    public function iterateAccountCharacters()
    {
        
        //if ($this->isLocked($this->username)) {
        //$this->resultTable();
        //} else {
        foreach ($this->account_characters as $characters) {
            //begin character specific operations
            $this->character_id = $characters['character_eve_idcharacter'];

            $this->db->where('eve_idcharacter', $this->character_id);
            $query = $this->db->get('characters');

            $this->character_name     = $query->row()->name;
            $this->character_balance  = $query->row()->balance;
            $this->character_escrow   = $query->row()->escrow;
            $this->character_networth = $query->row()->networth;
            $this->character_orders   = $query->row()->total_sell;
            $this->apikey             = $query->row()->api_apikey;

            $this->db->where('apikey', $this->apikey);
            $query       = $this->db->get('api');
            $vcode       = $query->row()->vcode;
            $this->vcode = $vcode;

            //get character data
            $this->getWalletBalance();
            $this->getBrokerRelationsLevel();
            $this->getAccountingLevel();
            $this->getCorpStandings();
            $this->getFactionStandings();

            $this->db->trans_start();
            $this->getTransactions();
            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                return false;
            }

            $this->getContracts();
            $this->getMarketOrders();
            $this->getAssets();
            $this->setNewInfo();
            $this->updateCharacterInfo();
        }
        
        
        return true;
    }


    private function getWalletBalance()
    {
        $pheal    = new Pheal($this->apikey, $this->vcode, "char");
        $response = $pheal->AccountBalance(array("characterID" => $this->character_id));

        foreach ($response->accounts as $row) {
            $balance                 = $row->balance;
            $this->character_balance = $balance;
        }
    }

    private function getBrokerRelationsLevel()
    {
        $this->character_broker_level = '0';
        $pheal                        = new Pheal($this->apikey, $this->vcode, "char");
        $response                     = $pheal->CharacterSheet(array("characterID" => $this->character_id));
        foreach ($response->skills as $skills) {
            if (($skills->typeID) == 3446) {
                $this->character_broker_level = $skills->level;
            }
        }
    }

    private function getAccountingLevel()
    {
        $this->character_accounting_level = '0';
        $pheal                            = new Pheal($this->apikey, $this->vcode, "char");
        $response                         = $pheal->CharacterSheet(array("characterID" => $this->character_id));
        foreach ($response->skills as $skills) {
            if (($skills->typeID) == 16622) {
                $this->character_accounting_level = $skills->level;
            }
        }
    }

    private function getCorpStandings()
    {
        //corp standings
        $pheal  = new Pheal($this->apikey, $this->vcode, "char");
        $result = $pheal->Standings(array("characterID" => $this->character_id));

        $corpStandingsArray = array();
        foreach ($result->characterNPCStandings->NPCCorporations as $corpStandings) {
            $data = array("idstandings_corporation" => "null",
                "characters_eve_idcharacters"           => $this->character_id,
                "corporation_eve_idcorporation"         => $corpStandings->fromID,
                "value"                                 => $corpStandings->standing);
            array_push($corpStandingsArray, $data);
        }

        $this->character_corp_standings = $corpStandingsArray;
        if (count($corpStandingsArray) > 0) {
            batch("standings_corporation",
                array('idstandings_corporation', 'characters_eve_idcharacters', 'corporation_eve_idcorporation', 'value'), $corpStandingsArray);
        }
    }

    private function getFactionStandings()
    {
        //faction standings
        $pheal  = new Pheal($this->apikey, $this->vcode, "char");
        $result = $pheal->Standings(array("characterID" => $this->character_id));

        $factionStandingsArray = array();
        foreach ($result->characterNPCStandings->factions as $factionStandings) {
            $data = array("idstandings_faction" => "null",
                "characters_eve_idcharacters"       => $this->character_id,
                "faction_eve_idfaction"             => $factionStandings->fromID,
                "value"                             => $factionStandings->standing);
            array_push($factionStandingsArray, $data);
        }

        $this->character_faction_standings = $factionStandingsArray;
        if (count($factionStandingsArray) > 0) {
            batch("standings_faction",
                array('idstandings_faction', 'characters_eve_idcharacters', 'faction_eve_idfaction', 'value'), $factionStandingsArray);
        }
    }

    private function getTransactions($refID = false)
    {
        $pheal    = new Pheal($this->apikey, $this->vcode, "char");
        $response = $pheal->WalletTransactions(array("characterID" => $this->character_id));

        if ($refID != false) {
            $response = $pheal->WalletTransactions(array("fromID" => $refID));
        }

        $this->db->select('COALESCE(max(transkey),0) AS val');
        $this->db->where('character_eve_idcharacter', $this->character_id);
        $query              = $this->db->get('transaction');
        $latest_transaction = $query->row()->val;

        $transactions = array();

        //only update transactions not in db already
        foreach ($response->transactions as $row) {
            if ($row->transactionID > $latest_transaction) {
                $data = array("idbuy"       => "null",
                    "time"                      => $this->db->escape($row->transactionDateTime),
                    "quantity"                  => $this->db->escape($row->quantity),
                    "price_unit"                => $this->db->escape($row->price),
                    "price_total"               => $this->db->escape($row->price * $row->quantity),
                    "transaction_type"          => $this->db->escape($row->transactionType),
                    "character_eve_idcharacter" => $this->db->escape($this->character_id),
                    "station_eve_idstation"     => $this->db->escape($row->stationID),
                    "item_eve_iditem"           => $this->db->escape($row->typeID),
                    "transkey"                  => $this->db->escape($row->transactionID),
                    "client"                    => $this->db->escape($row->clientName),
                    "remaining"                 => $this->db->escape($row->quantity));
                array_push($transactions, $data);
            }
        }

        if (!empty($transactions)) {
            batch_ignore("transaction",
                array('idbuy',
                    'time',
                    'quantity',
                    'price_unit',
                    'price_total',
                    'transaction_type',
                    'character_eve_idcharacter',
                    'station_eve_idstation',
                    'item_eve_iditem',
                    'transkey',
                    'client',
                    'remaining'),
                $transactions);

            $this->character_new_transactions = count($transactions);

            if (count($transactions) == 2560) {
                //check if we exceed the max transactions per request
                $refID = end($transactions['transkey']);
                $this->character_new_transactions += 2560;
                $this->getTransactions($refID); //pass the last transaction as request again
            }
        } else {
            $this->character_new_transactions = 0;
        }
    }

    private function getContracts()
    {
        $pheal    = new Pheal($this->apikey, $this->vcode, "char");
        $response = $pheal->Contracts(array("characterID" => $this->character_id));

        $this->db->select('COALESCE(max(eve_idcontracts),0) AS val');
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $query         = $this->db->get('contracts');
        $contracts     = array();
        $old_contracts = [];
        $new_contracts = [];

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
                "characters_eve_idcharacters"   => $this->db->escape($this->character_id),
            );

            array_push($contracts, $data);
            array_push($new_contracts, $row->contractID);
        }

        //count how many new contracts were inserted
        $this->db->select('eve_idcontracts');
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $query = $this->db->get('contracts');
        $old   = $query->result();

        foreach ($old as $row) {
            array_push($old_contracts, $row->eve_idcontracts);
        }

        $duplicates = 0;
        foreach ($new_contracts as $new) {
            foreach ($old_contracts as $old) {
                if ($new == $old) {
                    $duplicates++;
                }
            }
        }

        $this->character_new_contracts = count($new_contracts) - $duplicates;

        if (!empty($contracts)) {
            batch("contracts",
                array('eve_idcontracts',
                    'issuer_id',
                    'acceptor_id',
                    'status',
                    'availability',
                    'type',
                    'creation_date',
                    'expiration_date',
                    'completed_date',
                    'price',
                    'reward',
                    'colateral',
                    'fromStation_eve_idstation',
                    'toStation_eve_idstation',
                    'characters_eve_idcharacters'),
                $contracts);
        } else {
            $this->character_new_contracts = 0;
        }
    }

    private function getMarketOrders()
    {
        $pheal                  = new Pheal($this->apikey, $this->vcode, "char");
        $response               = $pheal->MarketOrders(array("characterID" => $this->character_id));
        $this->character_escrow = 0;
        $market_orders          = [];
        $new_orders             = [];
        $old_orders             = [];

        foreach ($response->orders as $row) {
            //Eve API reports order states with these codes
            switch ($row->orderState) {
                case '0':
                    $order_state = "open";
                    $this->character_escrow += $row->escrow;
                    break;
                case '1':
                    $order_state = "closed";
                    break;
                case '2':
                    $order_state = "expired";
                    break;
                case '3':
                    $order_state = "canceled";
                    break;
                case '4':
                    $order_state = "pending";
                    break;
                case '5':
                    $order_state = "character_deleted";
                    break;
            }

            $data = array("idorders"      => "null",
                "eve_item_iditem"             => $this->db->escape($row->typeID),
                "station_eve_idstation"       => $this->db->escape($row->stationID),
                "characters_eve_idcharacters" => $this->db->escape($this->character_id),
                "price"                       => $this->db->escape($row->price),
                "volume_remaining"            => $this->db->escape($row->volRemaining),
                "duration"                    => $this->db->escape($row->duration),
                "escrow"                      => $this->db->escape($row->escrow),
                "type"                        => $this->db->escape($row->bid ? "buy" : "sell"),
                "order_state"                 => $this->db->escape($order_state),
                "order_range"                 => $this->db->escape($row->range),
                "date"                        => $this->db->escape($row->issued),
                "transkey"                    => $this->db->escape($row->orderID),
            );

            array_push($market_orders, $data);
            if ($order_state == 'open') {
                array_push($new_orders, $row->orderID);
            }
        }

        $this->db->select('transkey');
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $query = $this->db->get('orders');
        $old   = $query->result();

        foreach ($old as $row) {
            array_push($old_orders, $row->transkey);
        }

        $duplicates = 0;
        foreach ($new_orders as $new) {
            foreach ($old_orders as $old) {
                if ($new == $old) {
                    $duplicates++;
                }
            }
        }
        $this->character_new_orders = count($new_orders) - $duplicates;

        //remove old orders
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $this->db->delete('orders');

        if (!empty($market_orders)) {
            batch("orders",
                array('idorders',
                    'eve_item_iditem',
                    'station_eve_idstation',
                    'characters_eve_idcharacters',
                    'price',
                    'volume_remaining',
                    'duration',
                    'escrow',
                    'type',
                    'order_state',
                    'order_range',
                    'date',
                    'transkey'),
                $market_orders);
        } else {
            $this->character_new_orders = 0;
        }

        $this->db->select('coalesce(sum(orders.volume_remaining * item_price_data.price_evecentral),0) AS grand_total');
        $this->db->from('orders');
        $this->db->join('item_price_data', 'item_price_data.item_eve_iditem = orders.eve_item_iditem');
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $this->db->where('orders.order_state', 'open');
        $this->db->where('orders.type', 'sell');
        $query = $this->db->get('');
        /*$query = $this->db->query("SELECT coalesce(sum(orders.volume_remaining * item_price_data.price_evecentral),0) AS grand_total
        FROM orders
        JOIN item_price_data ON item_price_data.item_eve_iditem = orders.eve_item_iditem
        WHERE characters_eve_idcharacters = '$this->character_id' AND orders.order_state = 'open' AND orders.type = 'sell'");*/

        $this->character_orders = $query->row()->grand_total;
    }

    //fetches assets 4 levels deep in containers
    private function getAssets()
    {
        $pheal    = new Pheal($this->apikey, $this->vcode, "char");
        $response = $pheal->AssetList(array("characterID" => $this->character_id));

        $i           = 0; //for iterating each asset
        $index_asset = 0; //for iterating the final array with all assets

        foreach ($response->assets as $assets) {
            $typeID_asset   = $assets['typeID'];
            $locationID     = $assets['locationID'];
            $quantity_asset = $assets['quantity'];
            $i++;
            $assetList[$i] = array("idassets" => "NULL",
                "characters_eve_idcharacters"     => $this->character_id,
                "item_eve_iditem"                 => $typeID_asset,
                "quantity"                        => $quantity_asset,
                "locationID"                      => $locationID);

            if (isset($assets->contents)) {
                foreach ($assets->contents as $assets_inside) {
                    $typeID_sub   = $assets_inside['typeID'];
                    $quantity_sub = $assets_inside['quantity'];
                    $i++;
                    $assetList[$i] = array("idassets" => "NULL",
                        "characters_eve_idcharacters"     => $this->character_id,
                        "item_eve_iditem"                 => $typeID_sub,
                        "quantity"                        => $quantity_sub,
                        "locationID"                      => $locationID);

                    if (isset($assets_inside->contents)) {
                        foreach ($assets_inside->contents as $assets_inside_2) {
                            $typeID_sub_sub   = $assets_inside_2['typeID'];
                            $quantity_sub_sub = $assets_inside_2['quantity'];
                            $i++;
                            $assetList[$i] = array("idassets" => "NULL",
                                "characters_eve_idcharacters"     => $this->character_id,
                                "item_eve_iditem"                 => $typeID_sub_sub,
                                "quantity"                        => $quantity_sub_sub,
                                "locationID"                      => $locationID);

                            if (isset($assets_inside_2->contents)) {
                                foreach ($assets_inside_2->contents as $assets_inside_3) {
                                    $typeID_sub_sub_sub   = $assets_inside_3['typeID'];
                                    $quantity_sub_sub_sub = $assets_inside_3['quantity'];
                                    $i++;
                                    $assetList[$i] = array("idassets" => "NULL",
                                        "characters_eve_idcharacters"     => $this->character_id,
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

        //first, delete existing assets
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $this->db->delete('assets');
            
        if (!empty($assetList)) {
            
            batch("assets",
                array('idassets',
                    'characters_eve_idcharacters',
                    'item_eve_iditem',
                    'quantity',
                    'locationID'),
                $assetList);
        }

        $this->db->select('coalesce(SUM(assets.quantity * item_price_data.price_evecentral),0) AS grand_total');
        $this->db->from('assets');
        $this->db->join('item_price_data', 'item_price_data.item_eve_iditem = assets.item_eve_iditem');
        $this->db->where('characters_eve_idcharacters', $this->character_id);
        $query = $this->db->get('');
        /*$query = $this->db->query("SELECT coalesce(SUM(assets.quantity * item_price_data.price_evecentral),0) AS grand_total
        FROM assets
        JOIN item_price_data ON item_price_data.item_eve_iditem = assets.item_eve_iditem
        WHERE assets.`characters_eve_idcharacters` =  '$this->character_id'");*/

        $this->character_networth = $query->row()->grand_total;
    }

    public function updateCharacterInfo()
    {
        $data = array(
            "balance"          => $this->character_balance,
            "escrow"           => $this->character_escrow,
            "total_sell"       => $this->character_orders,
            "broker_relations" => $this->character_broker_level,
            "accounting"       => $this->character_accounting_level,
            "networth"         => $this->character_networth,
        );

        $this->db->where('eve_idcharacter', $this->character_id);
        $this->db->update('characters', $data);
    }

    public function setNewInfo()
    {
        $data = array(
            "characters_eve_idcharacters" => $this->character_id,
            "contracts"                   => $this->character_new_contracts,
            "orders"                      => $this->character_new_orders,
            "transactions"                => $this->character_new_transactions,
        );

        $this->db->replace("new_info", $data);
    }

    public function calculateProfits()
    {
        $buy_stack  = array();
        $sell_stack = array();

        $num_profits = 0;

        //buy list
        $this->db->select('transaction.idbuy, transaction.item_eve_iditem, transaction.quantity, transaction.price_unit, transaction.time, transaction.remaining');
        $this->db->from('transaction');
        $this->db->join('aggr', 'transaction.character_eve_idcharacter = aggr.character_eve_idcharacter');
        $this->db->join('user', 'aggr.user_iduser = user.iduser');
        $this->db->where('transaction.remaining > 0');
        $this->db->where('transaction.transaction_type', 'Buy');
        $this->db->where('user.username', $this->username);
        $this->db->order_by('time', 'asc');
        $buy_list = $this->db->get('');
        $buy_stack = $buy_list->result_array();

        //sell list
        $this->db->select('transaction.idbuy, transaction.item_eve_iditem, transaction.quantity, transaction.price_unit, transaction.time, transaction.remaining');
        $this->db->from('transaction');
        $this->db->join('aggr', 'transaction.character_eve_idcharacter = aggr.character_eve_idcharacter');
        $this->db->join('user', 'aggr.user_iduser = user.iduser');
        $this->db->where('transaction.remaining > 0');
        $this->db->where('transaction.transaction_type', 'Sell');
        $this->db->where('user.username', $this->username);
        $this->db->order_by('time', 'asc');
        $sell_list = $this->db->get('');
        $sell_stack = $sell_list->result_array();

        $size_buy  = sizeof($buy_stack);
        $size_sell = sizeof($sell_stack);

        for ($i = 0; $i <= $size_buy - 1; $i++) {
            $buy_stack[$i]['idbuy'];
            $buy_stack[$i]['item_eve_iditem'];
            $buy_stack[$i]['remaining'];
            $buy_stack[$i]['time'];
            $buy_stack[$i]['price_unit'];

            $quantity_b_calc = $buy_stack[$i]['quantity'];

            for ($k = 0; $k <= $size_sell - 1; $k++) {
                $sell_stack[$k]['idbuy'];
                $sell_stack[$k]['item_eve_iditem'];
                $sell_stack[$k]['remaining'];
                $sell_stack[$k]['time'];
                $sell_stack[$k]['price_unit'];

                //found a match
                if ($sell_stack[$k]['item_eve_iditem'] == $buy_stack[$i]['item_eve_iditem'] 
                    && $sell_stack[$k]['time'] > $buy_stack[$i]['time'] 
                    && $buy_stack[$i]['remaining'] > 0 
                    && $sell_stack[$k]['remaining'] > 0) {

                    $num_profits++;
                    $profit_q = min($buy_stack[$i]['remaining'], $sell_stack[$k]['remaining']);

                    //update remaining quantity
                    $data_buy = ["remaining" => $buy_stack[$i]['remaining'] - $profit_q];
                    $this->db->where('idbuy', $buy_stack[$i]['idbuy']);
                    $this->db->update('transaction', $data_buy);

                    $data_sell = ["remaining" => $sell_stack[$k]['remaining'] - $profit_q];
                    $this->db->where('idbuy', $sell_stack[$k]['idbuy']);
                    $this->db->update('transaction', $data_sell);

                    //update array
                    $sell_stack[$k]['remaining'] = $sell_stack[$k]['remaining'] - $profit_q;
                    $buy_stack[$i]['remaining']  = $buy_stack[$i]['remaining'] - $profit_q;

                    //find profit data
                    $this->db->select('item.name as itemname,
                        item.eve_iditem as iditem,
                        station.name as stationname,
                        transaction.station_eve_idstation as stationid,
                        characters.eve_idcharacter as characterid,
                        characters.name as charactername,
                        transaction.time as transactiontime');
                    $this->db->from('transaction');
                    $this->db->join('characters', 'transaction.character_eve_idcharacter = characters.eve_idcharacter');
                    $this->db->join('station', 'transaction.station_eve_idstation = station.eve_idstation', 'left');
                    $this->db->join('item', 'transaction.item_eve_iditem = item.eve_iditem', 'left');
                    $this->db->where('transaction.idbuy', $buy_stack[$i]['idbuy']);
                    $query_buy = $this->db->get('');


                    $this->db->select('item.name as itemname,
                        item.eve_iditem as iditem,
                        station.name as stationname,
                        transaction.station_eve_idstation as stationid,
                        characters.eve_idcharacter as characterid,
                        characters.name as charactername,
                        transaction.time as transactiontime');
                    $this->db->from('transaction');
                    $this->db->join('characters', 'transaction.character_eve_idcharacter = characters.eve_idcharacter');
                    $this->db->join('station', 'transaction.station_eve_idstation = station.eve_idstation', 'left');
                    $this->db->join('item', 'transaction.item_eve_iditem = item.eve_iditem', 'left');
                    $this->db->where('transaction.idbuy', $sell_stack[$k]['idbuy']);
                    $query_sell = $this->db->get('');

                    
                    //calulate taxes
                    $stationFromID   = $query_buy->row()->stationid;
                    $stationToID     = $query_sell->row()->stationid;
                    $date_buy        = $query_buy->row()->transactiontime;
                    $date_sell       = $query_sell->row()->transactiontime;
                    $characterBuyID  = $query_buy->row()->characterid;
                    $characterSellID = $query_sell->row()->characterid;

                    $CI = &get_instance();
                    $CI->load->model('Tax_Model');
                    $CI->Tax_Model->tax($stationFromID, $stationToID, $characterBuyID, $characterSellID, "buy", "sell");
                    $transTaxFrom  = $CI->Tax_Model->calculateTaxFrom();
                    $brokerFeeFrom = $CI->Tax_Model->calculateBrokerFrom();
                    $transTaxTo    = $CI->Tax_Model->calculateTaxTo();
                    $brokerFeeTo   = $CI->Tax_Model->calculateBrokerTo();

                    $price_unit_b_taxed  = $buy_stack[$i]['price_unit'] * $brokerFeeFrom * $transTaxFrom;
                    $price_total_b_taxed = $price_unit_b_taxed * $profit_q;
                    $price_unit_s_taxed  = $sell_stack[$k]['price_unit'] * $brokerFeeTo * $transTaxTo;
                    $price_total_s_taxed = $price_unit_s_taxed * $profit_q;

                    //calculate final profit
                    $profit      = ($price_unit_s_taxed - $price_unit_b_taxed) * $profit_q;
                    $profit_unit = ($price_unit_s_taxed - $price_unit_b_taxed);
                    $trans_b = $buy_stack[$i]["idbuy"];
                    $trans_s = $sell_stack[$k]["idbuy"];

                    //insert profit
                    $add_profit = $this->db->query("INSERT IGNORE profit
                        (idprofit,
                        transaction_idbuy_buy,
                        transaction_idbuy_sell,
                        profit_unit,
                        timestamp_buy,
                        timestamp_sell,
                        characters_eve_idcharacters_IN,
                        characters_eve_idcharacters_OUT,
                        quantity_profit) VALUES
                        (NULL,
                        '$trans_b',
                        '$trans_s',
                        '$profit_unit',
                        '$date_buy',
                        '$date_sell',
                        '$characterBuyID',
                        '$characterSellID',
                        '$profit_q')");

                }
            }
        }

        $this->character_new_profits = $num_profits;
    }

    //Update each character's total profit, sales, etc for this day
    public function updateTotals(bool $global = false, string $user = null)
    {
        if (!$global) {
            $username = $this->username;
        } else {
            $username = $user;
        }

        $this->db->select('name, character_eve_idcharacter');
        $this->db->where('username', $username);
        $character_list = $this->db->get('v_user_characters');

        $dt = new DateTime();
        $tz = new DateTimeZone('Europe/Lisbon');
        $dt->setTimezone($tz);
        $date_today = $dt->format('Y-m-d');

        foreach ($character_list->result() as $row) {
            $this->character_id = $row->character_eve_idcharacter;

            $this->character_id;

            //sum of sales
            $this->db->select('coalesce(sum(price_total),0) as sum');
            $this->db->where('character_eve_idcharacter', $this->character_id);
            $this->db->where('transaction_type', 'Sell');
            $this->db->where('date(time)', $date_today);
            $sales_sum     = $this->db->get('transaction');
            $sales_sum_val = $sales_sum->row()->sum;

            //sum of purchases
            $this->db->select('coalesce(sum(price_total),0) as sum');
            $this->db->where('character_eve_idcharacter', $this->character_id);
            $this->db->where('transaction_type', 'Buy');
            $this->db->where('date(time)', $date_today);
            $purchases_sum     = $this->db->get('transaction');
            $purchases_sum_val = $purchases_sum->row()->sum;

            //sum of profits
            $this->db->select('coalesce(SUM(profit_unit*quantity_profit),0) as sum');
            $this->db->where('date(timestamp_sell)', $date_today);
            $this->db->where('characters_eve_idcharacters_OUT', $this->character_id);
            $profits_sum     = $this->db->get('profit');
            $profits_sum_val = $profits_sum->row()->sum;

            //profit margin
            $this->db->select('coalesce(((sum(profit.profit_unit*profit.quantity_profit)/sum(t1.price_unit*profit.quantity_profit))*100),0) as margin');
            $this->db->from('profit');
            $this->db->join('transaction t1', 'profit.transaction_idbuy_buy = t1.idbuy');
            $this->db->join('transaction t2', 'profit.transaction_idbuy_sell = t2.idbuy');
            $this->db->join('characters', 't2.character_eve_idcharacter = characters.eve_idcharacter');
            $this->db->where('characters.eve_idcharacter', $this->character_id);
            $this->db->where('date(t2.time)', $date_today);
            $margin = $this->db->get('');

            /*$margin = $this->db->query("select coalesce(((sum(profit.profit_unit*profit.quantity_profit)/sum(t1.price_unit*profit.quantity_profit))*100),0) as margin
            from profit
            join transaction t1 on profit.transaction_idbuy_buy = t1.idbuy
            join transaction t2 on profit.transaction_idbuy_sell = t2.idbuy
            join characters on t2.character_eve_idcharacter = characters.eve_idcharacter
            where characters.eve_idcharacter = '$this->character_id'
            and date(t2.time) = '$date_today'");*/
            $margin_val = $margin->row()->margin;

            $data = array(
                "characters_eve_idcharacters" => $this->character_id,
                "date"                        => $date_today,
                "total_buy"                   => $purchases_sum_val,
                "total_sell"                  => $sales_sum_val,
                "total_profit"                => $profits_sum_val,
                "margin"                      => $margin_val,
            );

            $this->db->replace('history', $data);
        }

        /*$this->is_updating = 0;
        $data              = ["updating" => $this->is_updating];
        $this->db->where('username', $this->username);
        $this->db->update("user", $data);
        log_message('error', $this->db->last_query());*/
        if (!$global) {
            return $character_list->result();
        }
    }

    public function getAPIKeys(int $id_user): array
    {
        $this->db->select('api.apikey as key');
        $this->db->from('api');
        $this->db->join('characters', 'characters.api_apikey = api.apikey');
        $this->db->join('aggr', 'aggr.character_eve_idcharacter = characters.eve_idcharacter');
        $this->db->join('user', 'user.iduser = aggr.user_iduser');
        $this->db->where('user.iduser', $id_user);
        $query = $this->db->get('');

        $result = $query->result();
        return $result;
    }

    public function lock(string $username)
    {
        $data = array("updating" => 1);
        $this->db->where('username', $username);
        $this->db->update('user', $data);
    }

    public function release(string $username)
    {
        $data = array("updating" => 0);
        $this->db->where('username', $username);
        $this->db->update('user', $data);
    }

    public function isLocked(string $username): bool
    {
        $this->db->select('updating');
        $this->db->where('username', $username);
        $query = $this->db->get('user');

        $result = $query->row()->updating;

        if ($result == '1') {
            return true;
        }

        return false;
    }

    public function getChangeLog(bool $recent = false): array
    {
        $this->db->select('*');
        $this->db->from('changelog');

        $recent ? $this->db->limit('3') : "";
        $this->db->order_by('date', 'desc');
        $query  = $this->db->get('');
        $result = $query->result();

        return $result;
    }
}
