<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_market_types_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getItems()
    {
        $url      = "https://crest-tq.eveonline.com/market/types/";
        $items    = json_decode(file_get_contents($url), true);
        $total_pages = $items['pageCount_str'];

        log_message('error', $total_pages);
        for ($i = 2; $i <= $total_pages; $i++) {
            $request = "https://crest-tq.eveonline.com/market/types/?page=" . $i;
            $response = json_decode(file_get_contents($request), true);
            $total_items = count($response['items']);
            for ($k = 0; $k < $total_items; $k++) {
                array_push($items['items'], $response['items'][$k]);
            }
        }

        log_message('error', count($items['items']));
    }
}
