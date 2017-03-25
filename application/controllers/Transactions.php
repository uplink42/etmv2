<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends MY_Controller
{
    protected $new;
    protected $transID;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "transactions";

        $this->new     = $_REQUEST['new'] ?? '';
        $this->transID = $_REQUEST['transID'] ?? '';

        settype($this->new, 'int');
        settype($this->transID, 'string');
    }

    /**
     * Displays the transactions page
     * @param  int         $character_id 
     * @param  int|integer $interval     
     * @return void               
     */
    public function index(int $character_id, int $interval = 14) : void
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
                $this->load->model('ValidateRequest');
                $res = (bool) $this->ValidateRequest->checkTransactionOwnership($this->transID, $this->user_id);
                if (!$res) {
                    $data["notice"]  = "error";
                    $data["message"] = Msg::TRANSACTION_NOT_BELONG;
                }
            }
            $transactions = $this->Transactions_model->getTransactionList($chars, $interval, $this->new, $this->transID, $res);

            $count = $transactions['count'];
            if ($transactions['count'] > 200) {
                $img = false;
            } else {
                $img = true;
            }

            $data['img']          = $img;
            $data['transactions'] = $this->injectIcons($transactions['result'], 'object');
            $data['interval']     = $interval;
            $data['view']         = 'main/transactions_v';
            $this->load->view('main/_template_v', $data);
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
}
