<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StockLists_model extends CI_Model
{
    const ITEMLIMIT = 200; 

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns all stock lists for a user
     * @param  int    $user_id 
     * @return array         
     */
    public function getStockLists(int $user_id) : array
    {
        $this->db->select('itemlist.name, itemlist.iditemlist');
        $this->db->where('user_iduser', $user_id);
        $this->db->order_by('iditemlist', 'desc');
        $query  = $this->db->get('itemlist');
        $result = $query->result();

        return $result;
    }

    /**
     * Creates a new, empty stock list for a user.
     * Returns the ID if successful
     * @param  int    $user_id 
     * @param  string $name    
     * @return [bool|string]          
     */
    public function createEmptyList(int $user_id, string $name)
    {
        $data = array("user_iduser" => $user_id,
                      "name"        => $name);
        $query = $this->db->insert('itemlist', $data);

        if ($this->db->affected_rows() != 0) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Gets all items in a list
     * @param  int    $id_list 
     * @return array          
     */
    public function getItems(int $id_list) : array
    {
        $this->db->select('i.name as name, i.volume as vol, COALESCE(p.price_evecentral,0) as price, i.eve_iditem as item_id');
        $this->db->from('itemcontents c');
        $this->db->join('item i', 'i.eve_iditem = c.item_eve_iditem');
        $this->db->join('item_price_data p', 'p.item_eve_iditem = i.eve_iditem', 'left');
        $this->db->join('itemlist il', 'il.iditemlist = c.itemlist_iditemlist');
        $this->db->where('il.iditemlist', $id_list);
        $this->db->order_by('c.iditemcontents', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Generates the autocomplete results for item searching
     * @param  string $input 
     * @return array        
     */
    public function queryItems(string $input) : array
    {
        $this->db->select('name as value');
        $this->db->from('item');
        $this->db->where('name', $input);
        $this->db->where("type <> 0");
        $this->db->order_by('name', 'asc');
        $this->db->limit('1');
        $query1  = $this->db->get()->result();

        $this->db->select('name as value');
        $this->db->from('item');
        $this->db->like('name', $input);
        $this->db->where("type <> 0");
        $this->db->order_by('name', 'asc');
        $this->db->limit('15');
        $query2  = $this->db->get()->result();

        if(($key = array_search($query1, $query2)) !== false) {
            unset($query2[$key]);
        }

        $result = array_merge($query1, $query2);
        return $result;
    }
    
    /**
     * Inserts a new item in a list
     * @param  string $name    
     * @param  int    $list_id 
     * @return array          
     */
    public function insertItem(string $name, int $list_id) : array
    {
        $item  = "";
        $limit = self::ITEMLIMIT;
        $this->db->where('name', $name);
        $q1 = $this->db->get('item');

        if ($q1->num_rows() == 0) {
            $notice = "error";
            $msg    = "Invalid item provided";
        } else {
            $this->db->select('count(iditemcontents) as sum');
            $this->db->where('itemlist_iditemlist', $list_id);
            $qtotal = $this->db->get('itemcontents');

            if ($qtotal->row()->sum < $limit) {
                $item_id = $q1->row()->eve_iditem;
                $data    = array("itemlist_iditemlist" => $list_id,
                                 "item_eve_iditem"     => $item_id);
                $q2 = $this->db->query("INSERT IGNORE INTO itemcontents (iditemcontents, itemlist_iditemlist, item_eve_iditem)
                VALUES ('NULL', '$list_id', '$item_id')");

                $notice = "success";
                $msg    = Msg::ITEM_ADD_SUCCESS;
                $item   = $item_id;
            } else {
                $notice = "error";
                $msg    = Msg::ITEM_MAX_REACHED . "(" . $limit . ")";
            }
        }
        return array("notice" => $notice, "message" => $msg, "item" => $item);
    }

    /**
     * Removes an item from a list
     * @param  int    $item_id 
     * @param  int    $list_id 
     * @return array          
     */
    public function removeItem(int $item_id, int $list_id) : array
    {
        $data = array("itemlist_iditemlist" => $list_id,
                      "item_eve_iditem"     => $item_id);

        $query = $this->db->delete('itemcontents', $data);
        if ($this->db->affected_rows() != 0) {
            $notice  = "success";
            $message = Msg::ITEM_DELETE_SUCCESS;
        } else {
            $notice  = "error";
            $message = Msg::ITEM_REMOVE_FAILURE;
        }

        return array("notice" => $notice, "message" => $message);
    }

    /**
     * Removes a stock list and all its contents
     * @param  int    $list_id 
     * @return array          
     */
    public function removeList(int $list_id) : array
    {
        $data = array('iditemlist' => $list_id);
        $this->db->delete('itemlist', $data);

        if ($this->db->affected_rows() != 0) {
            $notice  = "success";
            $message = Msg::LIST_REMOVE_SUCCESS;
        } else {
            $notice  = "error";
            $message = Msg::ITEM_REMOVE_FAILURE;
        }

        return array("notice" => $notice, "message" => $message);
    }
}
