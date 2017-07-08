<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Itemhistory extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->page = "itemhistory";
        $this->load->model('Itemhistory_model', 'history');
        $this->load->model('common/Msg');
    }

    /**
     * Loads the item history page
     * @param  int         $character_id
     * @param  int|integer $interval
     * @param  int|null    $item_id
     * @return void
     */
    public function index($character_id, $interval = 30, $item_id = null): void
    {
        if ($this->enforce($character_id, $this->user_id)) {
            $this->Log->addEntry("visit " . $this->page, $this->user_id);
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "itemhistory";
            $data['interval'] = $interval;
            $data['item_id']  = $item_id;
            $data['view']     = 'main/itemhistory_v';

            $data['layout']['page_title']     = "Item History";
            $data['layout']['icon']           = "pe-7s-info";
            $data['layout']['page_aggregate'] = true;

            $this->twig->display('main/_template_v', $data);
        }
    }

    public function getItemStats($item_id)
    {
        $character_id = $this->input->post('chars');
        $interval     = $this->input->post('interval');
        $aggr         = $this->input->post('aggr');

        if (!$item_id || !$character_id || !$interval || !$aggr) {
            echo Msg::INVALID_REQUEST;
            return;
        }

        $params = ['interval' => $interval,
                   'item_id'  => $item_id];

        echo $this->buildData($character_id, $aggr, 'getStatsByInterval', 'Itemhistory_model', $params);
    }
}
