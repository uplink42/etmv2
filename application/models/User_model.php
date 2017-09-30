<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class User_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('msg');
    }

    protected $table      = 'user';
    protected $alias      = 'u';
    protected $identifier = 'iduser';
    protected $fields     = [
        'iduser',
        'username',
        'registration_date',
        'password',
        'reports',
        'email',
        'salt',
        'login_count',
        'updating',
        'default_buy_behaviour',
        'default_sell_behaviour',
        'cross_character_profits',
        'ignore_citadel_tax',
        'ignore_station_tax',
        'ignore_outpost_tax',
        'ignore_buy_tax',
        'ignore_sell_tax',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }

    public function getUserProfitSettings(int $idUser) : array
    {
        $userSettings = $this->getOne(array('iduser' => $idUser));

        $default_buy_tracking      = $userSettings->default_buy_behaviour  == 1 ? 'buy' : 'sell';
        $default_sell_tracking     = $userSettings->default_sell_behaviour == 1 ? 'sell' : 'buy';
        $cross_character_tracking  = $userSettings->cross_character_profits;
        $ignore_citadel_tax        = $userSettings->ignore_citadel_tax == 1 ? true : false;
        $ignore_station_tax        = $userSettings->ignore_station_tax == 1 ? true : false;
        $ignore_outpost_tax        = $userSettings->ignore_outpost_tax == 1 ? true : false;
        $ignore_buy_tax            = $userSettings->ignore_buy_tax     == 1 ? true : false;
        $ignore_sell_tax           = $userSettings->ignore_sell_tax    == 1 ? true : false;
        
        $result = [
            "buy_behaviour"      => $default_buy_tracking,
            "sell_behaviour"     => $default_sell_tracking,
            "x_character"        => $cross_character_tracking,
            "citadel_tax_ignore" => $ignore_citadel_tax,
            "station_tax_ignore" => $ignore_station_tax,
            "outpost_tax_ignore" => $ignore_outpost_tax,
            "sell_tax_ignore"    => $ignore_buy_tax,
            "buy_tax_ignore"     => $ignore_sell_tax,
        ];

        return $result;
    }
}