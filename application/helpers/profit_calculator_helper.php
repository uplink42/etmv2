<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ProfitCalculator
{
	private $idUser;
    private $settings;
    private $charactersList;
    private $ci;

    public function __construct(string $idUser)
    {
    	$this->idUser = $idUser;

    	$this->ci = &get_instance();
    	$this->ci->load->model('User_model', 'user');
    	$this->ci->load->model('Aggr_model', 'aggr');
    	$this->ci->load->model('Transactions_model', 'transactions');
        $this->load->helper('tax_calculator_helper');
    }

    public function beginProfitCalculation()
    {
        // get user settings
        $this->settings = $this->ci->user->getUserProfitSettings($this->idUser);
        if ($this->settings['x_character']) {
            // aggregated calculation
            $this->calculate();
        } else {
            // individual calculation, get all user characters
            $this->charactersList = $this->ci->aggr->getOne(array('user_iduser' => $this->idUser));
            foreach ($this->charactersList as $character) {
                $this->calculate($character->id_character);
            }
        }
    }

    private function calculate(int $idCharacter = null) : void
    {
        $buyStack   = [];
        $sellStack  = [];
        $num_profits = 0;

        $buyStack   = $this->ci->transactions->getStack($idCharacter, 'Buy', $this->idUser);
        $sellStack  = $this->ci->transactions->getStack($idCharacter, 'Sell', $this->idUser);
        $sizeBuy    = sizeof($buyStack);
        $sizeSell   = sizeof($sellStack);

        for ($i = 0; $i <= $sizeBuy - 1; $i++) {
            $quantity_b_calc = $buyStack[$i]['quantity'];
            for ($k = 0; $k <= $sizeSell - 1; $k++) {
                // found a match
                if ($sellStack[$k]['item_eve_iditem'] == $buyStack[$i]['item_eve_iditem']
                    && $sellStack[$k]['time'] > $buyStack[$i]['time']
                    && $buyStack[$i]['remaining'] > 0
                    && $sellStack[$k]['remaining'] > 0) {
                    $num_profits++;
                    $profit_q = min($buyStack[$i]['remaining'], $sellStack[$k]['remaining']);

                    // update remaining quantity
                    $dataBuy = array("remaining" => $buyStack[$i]['remaining'] - $profit_q);
                    $this->ci->transactions->update($buyStack[$i]['idbuy'], $dataBuy);

                    $dataSell = array("remaining" => $sellStack[$k]['remaining'] - $profit_q);
                    $this->ci->transactions->update($sellStack[$i]['idbuy'], $dataSell);

                    // update array
                    $sellStack[$k]['remaining'] = $sellStack[$k]['remaining'] - $profit_q;
                    $buyStack[$i]['remaining']  = $buyStack[$i]['remaining'] - $profit_q;

                    // calulate taxes
                    $stationFromID   = $buyStack[$i]['station_id'];
                    $stationToID     = $sellStack[$k]['station_id'];
                    $date_buy        = $buyStack[$i]['transaction_time'];
                    $date_sell       = $sellStack[$k]['transaction_time'];
                    $characterBuyID  = $buyStack[$i]['character_id'];
                    $characterSellID = $sellStack[$k]['character_id'];

                    // get buy and sell behaviour
                    $tax           = new TaxCalculator($stationFromID, $stationToID, $characterBuyID, $characterSellID, $this->settings);
                    $transTaxFrom  = $tax->calculateTax('from');
                    $brokerFeeFrom = $tax->calculateBroker('from');
                    $transTaxTo    = $tax->calculateTax('to');
                    $brokerFeeTo   = $tax->calculateBroker('to');

                    $price_unit_b_taxed  = $buyStack[$i]['price_unit'] * $brokerFeeFrom * $transTaxFrom;
                    $price_total_b_taxed = $price_unit_b_taxed * $profit_q;
                    $price_unit_s_taxed  = $sellStack[$k]['price_unit'] * $brokerFeeTo * $transTaxTo;
                    $price_total_s_taxed = $price_unit_s_taxed * $profit_q;

                    // calculate final profit
                    $profit      = ($price_unit_s_taxed - $price_unit_b_taxed) * $profit_q;
                    $profit_unit = ($price_unit_s_taxed - $price_unit_b_taxed);
                    $trans_b     = $buyStack[$i]["idbuy"];
                    $trans_s     = $sellStack[$k]["idbuy"];

                    $data = [
                        'transaction_idbuy_buy' => $trans_b,
                        'transaction_idbuy_sell' => $trans_s,
                        'profit_unit' => $profit_unit,
                        'timestamp_buy' => $date_buy,
                        'timestamp_sell' => $date_sell,
                        'characters_eve_idcharacters_IN' => $characterBuyID,
                        'characters_eve_idcharacters_OUT' => $characterSellID,
                        'quantity_profit' => $profit_q,
                    ];

                    $this->profit->insertOrIgnore($data);
                }
            }
        }
        $this->character_new_profits = $num_profits;
    }

    /*private function getStack(int $idCharacter = null, string $type) : array
    {
        $options = [
        	'remaining_gt' => 0,
        	'transaction_type' => $type,
        	'order_by' => 'time',
        	'order_by_dir' => 'asc',
        ];

        if (empty($idCharacter)) {
        	$options['id_user'] = $this->idUser;
        } else {
        	$options['character_eve_idcharacter'] = $this->idCharacter;
        }

        return $this->transactions->getAll($options, true);
    }*/
}