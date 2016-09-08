<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StockLists_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStockLists($user_id)
    {
        $this->db->where('user_iduser', $user_id);
        $this->db->order_by('iditemlist', 'desc');
        $query  = $this->db->get('itemlist');
        $result = $query->result();

        return $result;
    }

    public function createEmptyList($user_id, $name)
    {
        $data = array("user_iduser" => $user_id,
            "name"                      => $name);
        $query = $this->db->insert('itemlist', $data);

        if ($this->db->affected_rows() != 0) {
            return true;
        }
        return false;
    }

    public function getItems($id_list)
    {
        $this->db->select('i.name as name, i.volume as vol, p.price_evecentral as price, i.eve_iditem as id');
        $this->db->from('itemcontents c');
        $this->db->join('item i', 'i.eve_iditem = c.item_eve_iditem');
        $this->db->join('item_price_data p', 'p.item_eve_iditem = i.eve_iditem');
        $this->db->join('itemlist il', 'il.iditemlist = c.itemlist_iditemlist');
        $this->db->where('il.iditemlist', $id_list);
        $query = $this->db->get();
    }

    public function queryItems($input)
    {
        $this->db->select('name as value');
        $this->db->from('item');
        $this->db->like('name', $input);
        $this->db->limit('5');
        $query  = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function insertItem($name, $list_id)
    {
        $this->db->where('name', $name);
        $q1 = $this->db->get('item');

        if ($q1->num_rows() == 0) {
            $notice = "error";
            $msg = "Invalid item provided";
        } else {
            $item_id = $q1->row()->eve_iditem;
            $data    = array("itemlist_iditemlist" => $list_id,
                             "item_eve_iditem"       => $item_id);
            $q2 = $this->db->insert_string("itemcontents", $data);
            //duplicate verification
            if ($this->db->affected_rows() == 0) {
                $notice = "error";
                $msg = "Item already exists in this list";
            } else {
                $notice = "success";
                $msg = "Item added successfully";
            }
        }

        return array("notice" => $notice, "message" => $msg);
    }

    public function checkListBelong($list_id, $user_id)
    {
        $this->db->select('itemlist.iditemlist');
        $this->db->from('itemlist');
        $this->db->join('user', 'user.iduser = itemlist.user_iduser');
        $this->db->where('user.iduser', $user_id);
        $this->db->where('itemlist.iditemlist', $list_id);
        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }

}
