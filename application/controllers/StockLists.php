<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stocklists extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "stocklists";
        $this->load->model('stocklists_model');
    }

    public function index(int $character_id)
    {
        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "stocklists";
            $data['view']     = 'main/stocklists_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function newList()
    {
        $name = $this->security->xss_clean($this->input->post('list-name'));
        $res = $this->StockLists_model->createEmptyList($this->user_id, $name);
        if ($res) {
            $data['notice']  = "success";
            $data['message'] = Msg::LIST_CREATE_SUCCESS;
            $data['id'] = $res;

        } else {
            $data['notice']  = "error";
            $data['message'] = Msg::LIST_CREATE_ERROR;
        }

        echo json_encode($data);
    }

    public function populateList()
    {
        $lists = $this->StockLists_model->getStockLists($this->user_id);

        echo json_encode($lists);
    }

    public function getItems(int $id_list)
    {
        if($this->ValidateRequest->checkStockListOwnership($id_list, $this->user_id)) {
            $result = $this->StockLists_model->getItems($id_list);
            echo json_encode($result);
        } else {
            echo "error";
        }
    }

    public function searchItems()
    {
        $input = $_REQUEST['term'] ?? '';
        $result = $this->StockLists_model->queryItems($input);

        echo json_encode($result);
    }

    public function addItem()
    {
        if (empty($_REQUEST['item-name']) || empty($_REQUEST['list-id'])) {
            return false;
        }
        $list_id = (int)$_REQUEST['list-id'];
        $name    = $_REQUEST['item-name'];
        $user_id = $this->session->iduser;

        if($this->ValidateRequest->checkStockListOwnership($list_id, $this->user_id)) {
            $res = $this->StockLists_model->insertItem($name, $list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => Msg::INVALID_REQUEST));
        }
    }

    public function removeItem(int $item_id, int $list_id)
    {
        if($this->ValidateRequest->checkStockListOwnership($list_id, $this->user_id)) {
            $res = $this->StockLists_model->removeItem($item_id, $list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => Msg::INVALID_REQUEST));
        } 
    }

    public function removeList(int $list_id)
    {
        if($this->ValidateRequest->checkStockListOwnership($list_id, $this->user_id)) {
            $res = $this->StockLists_model->removeList($list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => Msg::INVALID_REQUEST));
        } 
    }

}
