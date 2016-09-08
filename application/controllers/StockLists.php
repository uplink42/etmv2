<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockLists extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "stocklists";
            $data['view']     = 'main/stocklists_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function newList()
    {
        $name = $this->security->xss_clean($this->input->post('list-name'));
        $this->load->model('StockLists_model');
        if ($this->StockLists_model->createEmptyList($this->session->iduser, $name)) {
            $data['notice']  = "success";
            $data['message'] = "Stock List created successfully. You may now add some items to it";

        } else {
            $data['notice']  = "error";
            $data['message'] = "Could not create Stock List";
        }

        echo json_encode($data);
    }

    public function populateList()
    {
        $this->load->model('StockLists_model');
        $lists = $this->StockLists_model->getStockLists($this->session->iduser);

        echo json_encode($lists);
    }

    public function getItems($id_list)
    {
        $this->load->model('StockLists_model');
        $result = $this->StockLists_model->getItems($id_list);

        return json_encode($result);
    }

    public function searchItems()
    {
        $input = $_REQUEST['term'];

        $this->load->model('StockLists_model');
        $result = $this->StockLists_model->queryItems($input);

        echo json_encode($result);
    }

    public function addItem()
    {
        if (empty($_REQUEST['item-name']) || empty($_REQUEST['list-id'])) {
            return false;
        }
        $list_id = $_REQUEST['list-id'];
        $name    = $_REQUEST['item-name'];
        $user_id = $this->session->iduser;

        $this->load->model('StockLists_model');
        if($this->StockLists_model->checkListBelong($list_id, $user_id)) {
            $res = $this->StockLists_model->insertItem($name, $list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => "Invalid request"));
        }
    }

}
