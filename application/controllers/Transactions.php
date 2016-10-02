<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends MY_Controller
{
    protected $new;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        ini_set('memory_limit', '-1');
        $this->page = "Transactions";

        if(isset($_REQUEST['new'])) {
            $this->new = $_REQUEST['new'];
        };
    }

    public function index($character_id, $interval = 14, $transaction_id = 0)
    {
        if($interval>365) $interval = 365;
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];
            $data['selected'] = "transactions";

            $this->load->model('Transactions_model');
            $transactions = $this->Transactions_model->getTransactionList($chars, $interval, $this->new);
            $count = $transactions['count'];
            if($transactions['count'] >200) {
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

    public function unlink($transaction_id)
    {
        $this->load->model('Transactions_model');
        if($this->ValidateRequest->checkTransactionOwnership($transaction_id, $this->session->iduser)) {
            if($this->Transactions_model->unlinkTransaction($transaction_id)) {
                echo json_encode(array("result" => "true", "msg" => Msg::TRANSACTION_UNLINK_SUCCESS, "type" => "success"));
            } else {
                echo json_encode(array("result" => "false", "msg" => Msg::TRANSACTION_UNLINK_ERROR, "type" => "error"));
            }
        } else {
            echo json_encode(array("result" => "false", "msg" => Msg::INVALID_REQUEST, "type" => "error"));
        }
    }
}
