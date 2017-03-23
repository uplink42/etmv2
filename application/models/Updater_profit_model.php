<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Updater_profit_model extends CI_Model
{
    private $username;
    private $defaultSellTracking;
    private $defaultBuyTracking;
    private $crossCharacterTracking;
    private $ignoreCitadelTax;
    private $charactersList;

    public function beginProfitCalculation(string $username)
    {
        $this->username = $username;
        // get user settings
        $this->db->where('username', $this->username);
        $query  = $this->db->get('user');
        $result = $query->row();

        $this->defaultSellTracking    = $result->default_sell_behaviour;
        $this->defaultBuyTracking     = $result->default_buy_behaviour;
        $this->crossCharacterTracking = $result->cross_character_profits;
        $this->ignoreCitadelTax       = $result->ignore_citadel_tax == 1 ? true : false;

        if ($this->crossCharacterTracking) {
            // aggregated calculation
            $this->calculate();
        } else {
            // individual calculation, get all user characters
            $this->db->select('character_eve_idcharacter');
            $this->db->where('username', $this->username);
            $query                = $this->db->get('v_user_characters');
            $this->charactersList = $query->result();

            foreach ($this->charactersList as $character) {
                $this->calculate((int) $character->character_eve_idcharacter);
            }
        }
    }

    private function calculate(int $character_id = null) : void
    {
        $buy_stack   = array();
        $sell_stack  = array();
        $num_profits = 0;
        $buy_stack   = $this->getStack($character_id, 'Buy');
        $sell_stack  = $this->getStack($character_id, 'Sell');
        $size_buy    = sizeof($buy_stack);
        $size_sell   = sizeof($sell_stack);

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

                // found a match
                if ($sell_stack[$k]['item_eve_iditem'] == $buy_stack[$i]['item_eve_iditem']
                    && $sell_stack[$k]['time'] > $buy_stack[$i]['time']
                    && $buy_stack[$i]['remaining'] > 0
                    && $sell_stack[$k]['remaining'] > 0) {

                    $num_profits++;
                    $profit_q = min($buy_stack[$i]['remaining'], $sell_stack[$k]['remaining']);

                    // update remaining quantity
                    $data_buy = ["remaining" => $buy_stack[$i]['remaining'] - $profit_q];
                    $this->db->where('idbuy', $buy_stack[$i]['idbuy']);
                    $this->db->update('transaction', $data_buy);

                    $data_sell = ["remaining" => $sell_stack[$k]['remaining'] - $profit_q];
                    $this->db->where('idbuy', $sell_stack[$k]['idbuy']);
                    $this->db->update('transaction', $data_sell);

                    // update array
                    $sell_stack[$k]['remaining'] = $sell_stack[$k]['remaining'] - $profit_q;
                    $buy_stack[$i]['remaining']  = $buy_stack[$i]['remaining'] - $profit_q;

                    // find profit data
                    $this->db->select('i.name as itemname,
                        i.eve_iditem as iditem,
                        s.name as stationname,
                        t.station_eve_idstation as stationid,
                        c.eve_idcharacter as characterid,
                        c.name as charactername,
                        t.time as transactiontime');
                    $this->db->from('transaction t');
                    $this->db->join('characters c', 't.character_eve_idcharacter = c.eve_idcharacter');
                    $this->db->join('station s', 't.station_eve_idstation = s.eve_idstation', 'left');
                    $this->db->join('item i', 't.item_eve_iditem = i.eve_iditem', 'left');
                    $this->db->where('t.idbuy', $buy_stack[$i]['idbuy']);
                    $query_buy = $this->db->get('');

                    $this->db->select('i.name as itemname,
                        i.eve_iditem as iditem,
                        s.name as stationname,
                        t.station_eve_idstation as stationid,
                        c.eve_idcharacter as characterid,
                        c.name as charactername,
                        t.time as transactiontime');
                    $this->db->from('transaction t');
                    $this->db->join('characters c', 't.character_eve_idcharacter = c.eve_idcharacter');
                    $this->db->join('station s', 't.station_eve_idstation = s.eve_idstation', 'left');
                    $this->db->join('item i', 't.item_eve_iditem = i.eve_iditem', 'left');
                    $this->db->where('t.idbuy', $sell_stack[$k]['idbuy']);
                    $query_sell = $this->db->get('');

                    // calulate taxes
                    $stationFromID   = $query_buy->row()->stationid;
                    $stationToID     = $query_sell->row()->stationid;
                    $date_buy        = $query_buy->row()->transactiontime;
                    $date_sell       = $query_sell->row()->transactiontime;
                    $characterBuyID  = $query_buy->row()->characterid;
                    $characterSellID = $query_sell->row()->characterid;

                    $CI = &get_instance();
                    $CI->load->model('Tax_Model');

                    // get buy and sell behaviour
                    $buy_behaviour  = $this->defaultBuyTracking == 1 ? 'buy' : 'sell';
                    $sell_behaviour = $this->defaultBuyTracking == 1 ? 'sell' : 'buy';
                    $CI->Tax_Model->tax($stationFromID, $stationToID, $characterBuyID, $characterSellID, $buy_behaviour, $sell_behaviour,
                        $this->ignoreCitadelTax);
                    $transTaxFrom  = $CI->Tax_Model->calculateTaxFrom();
                    $brokerFeeFrom = $CI->Tax_Model->calculateBrokerFrom();
                    $transTaxTo    = $CI->Tax_Model->calculateTaxTo();
                    $brokerFeeTo   = $CI->Tax_Model->calculateBrokerTo();

                    $price_unit_b_taxed  = $buy_stack[$i]['price_unit'] * $brokerFeeFrom * $transTaxFrom;
                    $price_total_b_taxed = $price_unit_b_taxed * $profit_q;
                    $price_unit_s_taxed  = $sell_stack[$k]['price_unit'] * $brokerFeeTo * $transTaxTo;
                    $price_total_s_taxed = $price_unit_s_taxed * $profit_q;

                    // calculate final profit
                    $profit      = ($price_unit_s_taxed - $price_unit_b_taxed) * $profit_q;
                    $profit_unit = ($price_unit_s_taxed - $price_unit_b_taxed);
                    $trans_b     = $buy_stack[$i]["idbuy"];
                    $trans_s     = $sell_stack[$k]["idbuy"];

                    // insert profit
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


    private function getStack(int $character_id = null, string $type) : array
    {
        $this->db->select('t.idbuy, t.item_eve_iditem, t.quantity, t.price_unit, t.time, t.remaining');
        $this->db->from('transaction t');
        $this->db->join('aggr a', 't.character_eve_idcharacter = a.character_eve_idcharacter');
        $this->db->join('user u', 'a.user_iduser = u.iduser');
        $this->db->where('t.remaining > 0');
        $this->db->where('t.transaction_type', $type);
        if (empty($character_id)) {
            $this->db->where('u.username', $this->username);
        } else {
            $this->db->where('a.character_eve_idcharacter', $character_id);
        }

        $this->db->order_by('t.time', 'asc');
        $buy_list = $this->db->get('');

        return $buy_list->result_array();
    }
}
