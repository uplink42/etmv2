<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Transactions extends MY_Controller
{
    protected $new;
    protected $transID;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "transactions";
        $this->load->model('ValidateRequest');

        $this->new     = $_REQUEST['new'] ?? null;
        $this->transID = $_REQUEST['transID'] ?? null;

        settype($this->new, 'int');
        settype($this->transID, 'string');
    }

    /**
     * Displays the transactions page
     * @param  int         $character_id 
     * @param  int|integer $interval     
     * @return void               
     */
    public function index($character_id, $interval = 14) : void
    {
        if ($interval > 365) {
            $interval = 365;
        }

        if ($this->enforce($character_id, $user_id = $this->user_id)) {
            $res              = true;
            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars            = $data['chars'];
            $data['selected'] = "transactions";

            $this->load->model('Transactions_model');
            if ($this->transID) {
                $interval = 9999;
                $res = (bool) $this->ValidateRequest->checkTransactionOwnership($this->transID, $this->user_id);
                if (!$res) {
                    $data["notice"]  = "error";
                    $data["message"] = Msg::TRANSACTION_NOT_BELONG;
                }
            }
            $data['interval']     = $interval;
            $data['view']         = 'main/transactions_v';

            $data['layout']['page_title']     = "Transactions";
            $data['layout']['icon']           = "pe-7s-menu";
            $data['layout']['page_aggregate'] = true;
            $this->twig->display('main/_template_v', $data);
        }
    }


    /**
     * Unlinks a transaction and echoes the result back to
     * the client
     * @param  [type] $transaction_id 
     * @return string json                
     */
    public function unlink($transaction_id) : void
    {
        $this->load->model('Transactions_model');
        if ($this->ValidateRequest->checkTransactionOwnership($transaction_id, $this->user_id)) {
            if ($this->Transactions_model->unlinkTransaction($transaction_id)) {
                $this->load->model('common/Log');
                $this->Log->addEntry('unlink' . $transaction_id, $this->etmsession->get('iduser'));
                echo json_encode(array("result" => "true", "msg" => Msg::TRANSACTION_UNLINK_SUCCESS, "type" => "success"));
            } else {
                echo json_encode(array("result" => "false", "msg" => Msg::TRANSACTION_UNLINK_ERROR, "type" => "error"));
            }
        } else {
            echo json_encode(array("result" => "false", "msg" => Msg::INVALID_REQUEST, "type" => "error"));
        }
    }


    public function getTransactionList(int $character_id, int $interval = 1, bool $aggr = false)
    {
        //int $item_id = null, int $new = 0, string $transID
        $params = [ 'interval'     => $interval,
                    'character_id' => $character_id,
                    'aggr'         => $aggr,
                    'new'          => $this->new,
                    'transID'      => $this->transID,
                    'defs'         => $_REQUEST 
        ];

        if ($params['transID'] > 0) {
            $res = (bool) $this->ValidateRequest->checkTransactionOwnership($params['transID'], $this->user_id);
            if (!$res) {
                $data["notice"]  = "error";
                $data["message"] = Msg::TRANSACTION_NOT_BELONG;
                echo json_encode($data);
                return;
            }
        }

        echo $this->buildData($character_id, $aggr, 'getTransactionList', 'Transactions_model', $params); 
    }
}
