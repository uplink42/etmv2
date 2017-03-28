<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    /**
     * Returns a list of all users
     * @return array 
     */
    public function getUsers(): array
    {
        $query  = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    /**
     * Returns a list of users filtered by report interval
     * @param  string $interval 
     * @return array           
     */
    public function getUsersByReports(string $interval): array
    {
        $this->db->select('username, iduser');
        $this->db->where('reports', $interval);
        $query  = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    /**
     * Returns a username
     * @param  int    $user_id 
     * @return string          
     */
    public function getUsername(int $user_id): string
    {
        $this->db->where('iduser', $user_id);
        $query  = $this->db->get('user');
        $result = $query->row()->username;

        return $result;
    }

    /**
     * Returns a user's email
     * @param  int    $user_id 
     * @return string          
     */
    public function getUserEmail(int $user_id): string
    {
        $this->db->where('iduser', $user_id);
        $query  = $this->db->get('user');
        $result = $query->row()->email;

        return $result;
    }


    public function getUserProfitSettings(int $user_id) : array
    {
         // get user settings
        $this->db->where('username', $this->etmsession->get('username'));
        $query  = $this->db->get('user');
        $user_settings = $query->row();

        $default_buy_tracking      = $user_settings->default_buy_behaviour  == 1 ? 'buy' : 'sell';
        $default_sell_tracking     = $user_settings->default_sell_behaviour == 1 ? 'sell' : 'buy';
        $cross_character_tracking  = $user_settings->cross_character_profits;
        $ignore_citadel_tax        = $user_settings->ignore_citadel_tax == 1 ? true : false;

        $result = [
            "buy_behaviour"      => $default_buy_tracking,
            "sell_behaviour"     => $default_sell_tracking,
            "x_character"        => $cross_character_tracking,
            "citadel_tax_ignore" => $ignore_citadel_tax
        ];

        return $result;
    }
}
