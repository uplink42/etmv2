<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index($character_id, $interval = 7)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];
            $data['selected'] = "transactions";

            $this->load->model('Transactions_model');
            $transactions = $this->Transactions_model->getTransactionList($chars, $interval);

            

            $data['transactions'] = $transactions;
            $data['interval'] = $interval;
            $data['view']           = 'main/transactions_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
