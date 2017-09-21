<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

final class Autoexec_sum extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        if (ENVIRONMENT !== 'development' && !is_cli()) {
            show_404();
        }

        $this->load->model('Stats_model', 'stats');
        $this->load->model('Profit_model', 'profit');
        $this->load->model('Api_keys_model', 'keys');
        $this->load->model('Characters_model', 'characters');
        $this->load->model('Transactions_model', 'transactions');

        $data = [
            "profit"       => $this->profit->countAllProfits()->profit,
            "transactions" => $this->transactions->countAll(),
            "api_keys"     => $this->keys->countAll(),
            "characters"   => $this->characters->countAll(),
        ];

        $data = $this->stats->saveStats($data);
    }
}
