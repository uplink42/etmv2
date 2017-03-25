<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stocklists extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "stocklists";
        $this->load->model('StockLists_model');
    }

    /**
     * Loads the Stock Lists page
     * @param  int    $character_id 
     * @return void               
     */
    public function index(int $character_id) : void
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

    /**
     * Creates a new list and echoes results to the client
     * @return string json 
     */
    public function newList() : void
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

    /**
     * Fetches available lists and 
     * echoes it back to the client
     * @return string json
     */
    public function populateList() : void
    {
        $this->load->model('StockLists_model');
        $lists = $this->StockLists_model->getStockLists($this->user_id);
        echo json_encode($lists);
    }

    /**
     * Gets all items in a list and 
     * echoes it back to the client
     * @param  int    $id_list 
     * @return void          
     */
    public function getItems(int $id_list) : void
    {
        if($this->ValidateRequest->checkStockListOwnership($id_list, $this->user_id)) {
            $result = $this->StockLists_model->getItems($id_list);
            echo json_encode($result);
        } else {
            echo "error";
        }
    }

    /**
     * Searches items by name (autocomplete) 
     * and echoes results back to client
     * @return string json
     */
    public function searchItems() : void
    {
        $input = $_REQUEST['term'] ?? '';
        $result = $this->StockLists_model->queryItems($input);
        echo json_encode($result);
    }

    /**
     * Adds a new item to a list and
     * echoes results to client
     * @return [bool|json]
     */
    public function addItem()
    {
        if (empty($_REQUEST['item-name']) || empty($_REQUEST['list-id'])) {
            return false;
        }
        $list_id = (int)$_REQUEST['list-id'];
        $name    = $_REQUEST['item-name'];
        $user_id = $this->etmsession->get('iduser');

        if($this->ValidateRequest->checkStockListOwnership($list_id, $this->user_id)) {
            $res = $this->StockLists_model->insertItem($name, $list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => Msg::INVALID_REQUEST));
        }
    }

    /**
     * Removes an item from an existing list and
     * echoes results back to client
     * @param  int    $item_id 
     * @param  int    $list_id 
     * @return string json          
     */
    public function removeItem(int $item_id, int $list_id) : void
    {
        if($this->ValidateRequest->checkStockListOwnership($list_id, $this->user_id)) {
            $res = $this->StockLists_model->removeItem($item_id, $list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => Msg::INVALID_REQUEST));
        } 
    }

    /**
     * Removes a stock list and echoes results
     * back to client
     * @param  int    $list_id 
     * @return string json          
     */
    public function removeList(int $list_id) : void
    {
        if($this->ValidateRequest->checkStockListOwnership($list_id, $this->user_id)) {
            $res = $this->StockLists_model->removeList($list_id);
            echo json_encode($res);
        } else {
            echo json_encode(array("notice" => "error", "message" => Msg::INVALID_REQUEST));
        } 
    }
}
