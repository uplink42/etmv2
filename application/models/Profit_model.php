<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Profit_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'profit';
    protected $alias      = 'p';
    protected $identifier = 'idprofit';
    protected $fields     = [
        'idprofit',
        'transaction_idbuy_buy',
        'transaction_idbuy_sell',
        'profit_unit',
        'timestamp_buy',
        'timestamp_sell',
        'characters_eve_idcharacters_IN',
        'characters_eve_idcharacters_OUT',
        'quantity_profit',
    ];

    protected function parseOptions(array $options = [])
    {
        if (isset($options['sum'])) {
            $this->db->select('COALESCE(sum(profit_unit * quantity_profit),0) as profit');
        }

        if (isset($options['date'])) {
            $this->db->where('date(timestamp_sell)', $options['date']);
        }

        if (isset($options['profit_margin'])) {
            $this->db->select('coalesce(((sum(' . $this->alias . '.profit_unit* ' . $this->alias . '.quantity_profit)/
                sum(t1.price_unit*' . $this->alias . '.quantity_profit))*100),0) as margin');
            $this->db->join('transaction t1', $this->alias . '.transaction_idbuy_buy = t1.idbuy');
            $this->db->join('transaction t2', $this->alias . '.transaction_idbuy_sell = t2.idbuy');
            $this->db->join('characters', 't2.character_eve_idcharacter = characters.eve_idcharacter');
            
        }

        if (isset($options['date_sell'])) {
            $this->db->where('date(t2.time)', $options['date_sell']);
        }

        return parent::parseOptions($options);
    }

    public function countAllProfits()
    {
        $options = [
            'sum' => 1,
        ];

        return parent::getOne($options);
    }

    public function getProfitMargin(int $idCharacter, $date)
    {
        $options = [
            'profit_margin' => 1,
            'date_sell' => $date,
            'characters_eve_idcharacters_OUT' => $idCharacter,
        ];

        return parent::getOne($options);
    }

    public function insertProfit($profitData)
    {
        extract($profitData);
        
        $this->db->query("INSERT IGNORE profit
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
